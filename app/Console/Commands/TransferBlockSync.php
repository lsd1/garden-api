<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

use Hht\Bitcoin\Facades\Bitcoin;

use App\Repositories\User\UserAccountRepository as UserAccount;
use App\Repositories\User\UserCountRepository as UserCount;
use App\Repositories\User\UserAccountUpdateRepository as UserAccountUpdate;

use App\Repositories\Transfer\TransferLogRepository as TransferLog;
use App\Repositories\Transfer\TransferBlockRepository as TransferBlock;


class TransferBlockSync extends Command
{

	protected $signature = 'ebcwallet:blockSync';

	protected $description = 'ebcwallet block sysnc';

	public function handle() {
		
		//同步用户数据
		$this->syncAccount();

		$res = app(UserAccountUpdate::class)->getUpdateList(20);

		foreach ($res as $row)
		{
			$trans = $this->getTransferList($row);
			
			if (count($trans) > 0)
			{
				DB::beginTransaction();
				try {
					
					// 插入block
					app(TransferBlock::class)->inserts($trans);
					$txid = '';

					$total = 0;
					
					foreach ($trans as $tran)
					{
						if ($tran['category'] == 'receive')
						{
							$dd = $this->getTransaction($tran['txid']);
							$txid = $tran['txid'];

							if ($dd)
							{
								$total += $dd['amount'];
	
								if (! empty($dd['fromAddress']))
								{
									list($ebc1, $ebc2) = to_ebc($dd['amount']);
									list($febc1, $febc2) = to_ebc($dd['fee']);

									// 非本地交易
									app(TransferLog::class)->create([
										'coinType' => 1, 'status' => 1, 'comment' => $tran['comment'], 'datetime' => date('Y-m-d H:i:s'),
										'fromUserId' => 0, 'fromAccountId' => 0, 
										'fromAccount' => $dd['fromAccount'], 'fromAddress' => $dd['fromAddress'],
										'toUserId' => $row->userId, 'toAccountId' => $row->accountId, 
										'toAccount' => $tran['account'], 'toAddress' => $tran['address'],
										'changeAmount1' => $ebc1, 'changeAmount2' => $ebc2, 'changeFee' => $febc2,
										'txid' => $tran['txid']
									]);
								} else {
									// 本地交易
									app(TransferLog::class)->update(['status' => 1], $tran['txid'], 'txid');
								}
							}
							
						}
					}
					
					if ($total > 0)
					{
						$count = app(userCount::class)->getOneByUserAccount($row->userId, $row->accountId, true);
						list($aebc1, $aebc2) = to_ebc($total);
						list($count->ebc1, $count->ebc2) = ebc_add($count->ebc1, $count->ebc2, $aebc1, $aebc2);
						$count->save();
					}
					
					if (! empty($txid))
					{
						$row->txid = $txid;
					}

					$row->updatetime = date('Y-m-d H:i:s');
					$row->save();

					DB::commit();
				} catch (Exception $e) {
					DB::rollback();
				}
			} else {
				$row->updatetime = date('Y-m-d H:i:s');
				$row->save();
			}
		}

		$this->comment(PHP_EOL . "ebcwallet block sysnc success " . date('Y-m-d H:i:s') . PHP_EOL);
	}

	private function getTransaction($txid) {
		
		$data = [];

		$res = Bitcoin::bitcoiner('easyblockchain')->gettransaction($txid);
		
		if (! Bitcoin::bitcoiner('easyblockchain')->getError())
		{
			if (isset($res['details']))
			{
				if (count($res['details']) == 2)
				{
					// 本地交易
					foreach ($res['details'] as $row)
					{
						if ($row['category'] == 'send')
						{
							$data['fromAccount'] = $row['account'];
							$data['fromAddress'] = '';
							$data['fee'] = abs($row['fee']);
						} else {
							$data['amount'] = abs($row['amount']);
						}
					}
				} else {
					// 非本地交易
					$data = $this->getTransaction($res['vin'][0]['txid']);
					$data['fromAccount'] = '';
					$data['amount'] = abs($res['details'][0]['amount']);
				}
				
			} else {
				$data['fee'] = abs($res['vout'][0]['value']);
				$data['fromAddress'] = $res['vout'][0]['scriptPubKey']['addresses'][0];
			}
		}

		return $data;

	}
	
	private function getTransferList($accountUpdate) {

		$list = [];

		$res = Bitcoin::bitcoiner('easyblockchain')->listtransactions($accountUpdate->account, 100);
		if (! Bitcoin::bitcoiner('easyblockchain')->getError() && $res)
		{
			foreach ($res as $row) 
			{
				if (in_array($row['category'], ['send', 'receive']))
				{
					$data['account'] = $row['account'];
					$data['address'] = $row['address'];
					$data['category'] = $row['category'];
					$data['amount'] = abs($row['amount']);
					$data['txid'] = $row['txid'];
					$data['time'] = date('Y-m-d H:i:s', $row['time']);
					$data['timereceived'] = date('Y-m-d H:i:s', $row['timereceived']);
					$data['confirmations'] = isset($row['confirmations']) ? $row['confirmations'] : 0;
					$data['blockhash'] = isset($row['blockhash']) ? $row['blockhash'] : 0;
					$data['blockindex'] = isset($row['blockindex']) ? $row['blockindex'] : 0;
					$data['blocktime'] = date('Y-m-d H:i:s', $row['blocktime']);
					$data['comment'] = isset($row['comment']) ? $row['comment'] : '';
					$data['message'] = isset($row['message']) ? $row['message'] : '';
					
					$list[] = $data;

					if (strcmp($row['txid'], $accountUpdate->txid) == 0)
					{
						$list = [];
					}
				}
				
			}
		}
		
		return $list;

	}

	private function syncAccount() {
		
		$maxid = app(UserAccountUpdate::class)->getMaxAccountId();
		$res = DB::table('user_account')
			->where('id', '>', $maxid)
			->orderBy('id', 'desc')
			->take(100)
			->get()
			->toArray();

		$data = [];
		foreach ($res as $row)
		{
			$data[] = [
				'userId' => $row->userId,
				'accountId' => $row->id,
				'account' => $row->account,
				'isFirst' => 0,
				'txid' => '',
				'updatetime' => $row->datetime
			];
		}

		app(UserAccountUpdate::class)->inserts($data);

	}

}
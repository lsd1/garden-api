<?php namespace App\Jobs;

use Exception, DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

use App\Repositories\Config\ConfigRepository as Config;

use App\Repositories\User\UserTreeRepository as UserTree;
use App\Repositories\User\UserTreeLogRepository as UserTreeLog;
use App\Repositories\User\UserTreeFruitRepository as UserTreeFruit;

class FruitMature extends Job
{
	public $tries = 3;

	private $id;

	public function __construct($id) {
		$this->id = $id;
	}


	public function handle() {
		
		$treeFruit = app(UserTreeFruit::class)->getOneById($this->id);
		if ($treeFruit->isMature == 0)
		{
			$key = env('CACHE_PREFIX', 'gd_') . 'steal_' . $treeFruit->userId;
			$vue = $treeFruit->userId . '_' . $treeFruit->userId . '_' . mt_rand(11111, 99999);
			
			// 强制锁
			$expiresAt = new \DateTime();
			Cache::put($key, $vue, $expiresAt->modify('+3 seconds'));

			$matureFruit = $treeFruit->matureFruit;
			$dryFruit = $wormyFruit = 0;

			$tree = app(UserTree::class)->getOneByUserId($treeFruit->userId);

			if (strcmp($tree->dryTime, '0000-00-00 00:00:00') != 0)
			{
				$dryFruitCDChance = app(Config::class)->getContentByKey('DryFruitCDChance', 0.2);
				$dryFruit = intval($matureFruit * $dryFruitCDChance);
				$matureFruit = $matureFruit - $dryFruit;
			}
			
			if (strcmp($tree->wormyTime, '0000-00-00 00:00:00') != 0)
			{
				$wormyFruitCDChance = app(Config::class)->getContentByKey('WormyFruitCDChance', 0.3);
				$wormyFruit = intval($matureFruit * $wormyFruitCDChance);
				$matureFruit = $matureFruit - $wormyFruit;
			}
			
			DB::beginTransaction();
			try {
				app(UserTree::class)->fruitMature($treeFruit->userId, $matureFruit);

				app(UserTreeLog::class)->create([
					'userId' => $treeFruit->userId, 'matureFruit' => $matureFruit, 'dryFruit' => $dryFruit, 
					'wormyFruit' => $wormyFruit, 'oldTreeFruit' => $tree->matureFruit, 
					'newTreeFruit' => ($tree->matureFruit + $matureFruit), 'datetime' => date('Y-m-d H:i:s')
				]);

				$treeFruit->isMature = 1;
				$treeFruit->save();

				DB::commit();
			} catch (Exception $e) {
				DB::rollback();
			} finally {
				Cache::forget($key);
			}
		}

	}
}

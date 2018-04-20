<?php namespace App\Listeners\User;

use Exception, DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

use App\Repositories\Config\ConfigRepository as Config;
use App\Repositories\Config\PackageRepository as Package;
use App\Repositories\Config\ActivateRepository as Activate;
use App\Repositories\Config\PackageToolRepository as PackageTool;

use App\Repositories\User\UserLogRepository as UserLog;
use App\Repositories\User\UserTreeRepository as UserTree;
use App\Repositories\User\UserToolLogRepository as UserToolLog;
use App\Repositories\User\UserPackageRepository as UserPackage;
use App\Repositories\User\UserProfileRepository as UserProfile;
use App\Repositories\User\UserToolCountRepository as UserToolCount;
use App\Repositories\User\UserTreeFruitRepository as UserTreeFruit;

class UserToolListener
{
	
	private $request;

    /**
     * 创建监听器
     *
     * 构造函数
     */
    public function __construct(Request $request) {

        $this->request = $request;

    }

	public function onFert($event) {

		$userId = $this->request->input('userId', 0);
		$toolId = $this->request->input('toolId', 0);
		$useNum = $this->request->input('useNum', 1);

		$fert2Fruit = app(Config::class)->getContentByKey('Fert2Fruit', 20);
		$fertGrowTime = app(Config::class)->getContentByKey('FertGrowTime', 86400);

		$matureTime = date('Y-m-d H:i:s', strtotime('+' . ($fertGrowTime * $useNum) . ' seconds'));
		$matureFruit = $fert2Fruit * $useNum;

		$toolCount = app(UserToolCount::class)->getOneByUserIdToolId($userId, $toolId);

		DB::beginTransaction();
		try {
			app(UserTreeFruit::class)->create([
				'userId' => $userId, 'fertTime' => date('Y-m-d H:i:s'),
				'matureTime' => $matureTime, 'matureFruit' => $matureFruit
			]);

			app(UserToolCount::class)->decrementTool($userId, $toolId, $useNum);
			app(UserToolLog::class)->create([
				'userId' => $userId, 'toolId' => $toolId, 'changeType' => 0, 'changeNum' => $useNum, 
				'oldNum' => $toolCount->num, 'newNum' => ($toolCount->num - $useNum),
				'content' => 'tool.tool_fertilizer_used',  'datetime' => date('Y-m-d H:i:s')
			]);

			app(UserLog::class)->create([
				'userId' => $userId, 'curType' => 'fert', 'joinUserId' => $userId, 'content' => 'tool.tool_fertilizer_used', 
				'datetime' => date('Y-m-d H:i:s')
			]);

			DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            throw new Exception('使用失败！');
        }
	}

	public function onWorm($event) {

		$userId = $this->request->input('userId', 0);
		$toolId = $this->request->input('toolId', 0);
		$useNum = $this->request->input('useNum', 1);
		
		// 检测是否需要除虫
		$tree = app(UserTree::class)->getOneByUserId($userId);
		if (strcmp($tree->wormyTime, '0000-00-00 00:00:00') == 0)
		{
			throw new Exception('果树很健康！');
		}

		$toolCount = app(UserToolCount::class)->getOneByUserIdToolId($userId, $toolId);

		DB::beginTransaction();
		try {
			app(UserTree::class)->updateByUserId(['wormyTime' => '0000-00-00 00:00:00'], $userId);

			app(UserToolCount::class)->decrementTool($userId, $toolId, $useNum);
			app(UserToolLog::class)->create([
				'userId' => $userId, 'toolId' => $toolId, 'changeType' => 0, 'changeNum' => $useNum, 
				'oldNum' => $toolCount->num, 'newNum' => ($toolCount->num - $useNum),
				'content' => 'tool.tool_repellent_used',  'datetime' => date('Y-m-d H:i:s')
			]);

			app(UserLog::class)->create([
				'userId' => $userId, 'curType' => 'repellent', 'joinUserId' => $userId, 'content' => 'tool.tool_repellent_used', 
				'datetime' => date('Y-m-d H:i:s')
			]);

			DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            throw new Exception('使用失败！');
        }
	}

	public function onRipening($event) {
		
		$userId = $this->request->input('userId', 0);
		$toolId = $this->request->input('toolId', 0);
		$useNum = $this->request->input('useNum', 1);

		$ripeningRate = app(Config::class)->getContentByKey('RipeningRate', 0.5);

		$beMature = app(UserTreeFruit::class)->getBeMatureByUserId($userId);
		if (count($beMature) <= 0)
		{
			throw new Exception('你还没施肥！');
		}
		
		$rate = 1;
		for($i = 0; $i < $useNum; $i ++)
		{
			$rate = (1 - $ripeningRate) * $rate;
		}
		
		$tree = app(UserTree::class)->getOneByUserId($userId);
		$toolCount = app(UserToolCount::class)->getOneByUserIdToolId($userId, $toolId);

		DB::beginTransaction();
		try {
			$nmatureTime = new \DateTime($tree->matureTime);

			foreach ($beMature as $row) 
			{
				$dteStart = new \DateTime($row->matureTime);
				$dteEnd   = new \DateTime(date('Y-m-d H:i:s'));
				if ($dteStart->getTimestamp() > $dteEnd->getTimestamp())
				{
					$dteDiff  = $dteStart->diff($dteEnd);
					$s = intval(($dteDiff->d * 86400 + $dteDiff->h * 3600 + $dteDiff->i * 60 + $dteDiff->s) * $rate);
					$matureTime = $dteEnd->modify("+{$s} seconds");

					$row->matureTime = $matureTime->format('Y-m-d H:i:s');
					$row->save();

					if ($nmatureTime->getTimestamp() > $matureTime->getTimestamp())
					{
						$nmatureTime = $matureTime;
					}
				}
			}
			
			app(UserToolCount::class)->decrementTool($userId, $toolId, $useNum);
			app(UserToolLog::class)->create([
				'userId' => $userId, 'toolId' => $toolId, 'changeType' => 0, 'changeNum' => $useNum, 
				'oldNum' => $toolCount->num, 'newNum' => ($toolCount->num - $useNum),
				'content' => 'tool.tool_ripener_used',  'datetime' => date('Y-m-d H:i:s')
			]);

			app(UserLog::class)->create([
				'userId' => $userId, 'curType' => 'ripener', 'joinUserId' => $userId, 'content' => 'tool.tool_ripener_used', 
				'datetime' => date('Y-m-d H:i:s')
			]);

			$matureTime = $nmatureTime->format('Y-m-d H:i:s');
			if (strcmp($tree->matureTime, $matureTime) != 0)
			{
				$tree->matureTime = $matureTime;
				$tree->save();
			}

			DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            throw new Exception('使用失败！');
        }
	}

	public function onAntiTheft($event) {

		$userId = $this->request->input('userId', 0);
		$toolId = $this->request->input('toolId', 0);
		$useNum = $this->request->input('useNum', 1);

		$antiTheftTime = app(Config::class)->getContentByKey('AntiTheftTime', 259200);

		$tree = app(UserTree::class)->getOneByUserId($userId);
		$datetime = $tree->antiTheftTime >= date('Y-m-d H:i:s') ? $tree->antiTheftTime : date('Y-m-d H:i:s');
		$datetime = new \DateTime($datetime);
		$datetime = $datetime->modify('+' . ($antiTheftTime * $useNum) . ' seconds')->format('Y-m-d H:i:s');

		$toolCount = app(UserToolCount::class)->getOneByUserIdToolId($userId, $toolId);

		DB::beginTransaction();
		try {
			app(UserTree::class)->updateByUserId(['antiTheftTime' => $datetime], $userId);

			app(UserToolCount::class)->decrementTool($userId, $toolId, $useNum);
			app(UserToolLog::class)->create([
				'userId' => $userId, 'toolId' => $toolId, 'changeType' => 0, 'changeNum' => $useNum, 
				'oldNum' => $toolCount->num, 'newNum' => ($toolCount->num - $useNum),
				'content' => 'tool.tool_anti_theft_used',  'datetime' => date('Y-m-d H:i:s')
			]);

			app(UserLog::class)->create([
				'userId' => $userId, 'curType' => 'anti-theft', 'joinUserId' => $userId, 'content' => 'tool.tool_anti_theft_used', 
				'datetime' => date('Y-m-d H:i:s')
			]);

			DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            throw new Exception('使用失败！');
        }
		
	}

	public function onDrug($event) {
		
		$userId = $this->request->input('userId', 0);
		$toolId = $this->request->input('toolId', 0);
		$useNum = $this->request->input('useNum', 1);
		
		// 检测是否需要除虫
		$tree = app(UserTree::class)->getOneByUserId($userId);
		if (strcmp($tree->wormyTime, '0000-00-00 00:00:00') == 0)
		{
			throw new Exception('果树很健康！');
		}

		$toolCount = app(UserToolCount::class)->getOneByUserIdToolId($userId, $toolId);

		DB::beginTransaction();
		try {
			app(UserTree::class)->updateByUserId(['wormyTime' => '0000-00-00 00:00:00'], $userId);

			app(UserToolCount::class)->decrementTool($userId, $toolId, $useNum);
			app(UserToolLog::class)->create([
				'userId' => $userId, 'toolId' => $toolId, 'changeType' => 0, 'changeNum' => $useNum, 
				'oldNum' => $toolCount->num, 'newNum' => ($toolCount->num - $useNum),
				'content' => 'tool.tool_repellent_used',  'datetime' => date('Y-m-d H:i:s')
			]);

			app(UserLog::class)->create([
				'userId' => $userId, 'curType' => 'drug', 'joinUserId' => $userId, 'content' => 'tool.tool_repellent_used', 
				'datetime' => date('Y-m-d H:i:s')
			]);

			DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            throw new Exception('使用失败！');
        }

	}

	public function onBug($event) {
		
		$userId = $this->request->input('userId', 0);

		// 检测是否需要生虫
		$tree = app(UserTree::class)->getOneByUserId($userId);
		if (strcmp($tree->wormyTime, '0000-00-00 00:00:00') != 0)
		{
			return;
		}
		
		// 计算概率
		$fertGrowWormyChance = app(Config::class)->getContentByKey('FertGrowWormyChance', 0.07);
		$max = 1000;
		$s = $fertGrowWormyChance * $max;
		$e = $s + $s;
		$max = $max < $e ? $e : $max;
	
		$m = mt_rand(1, $max);
		if ($m < $s || $m >= $e)
		{
			return;
		}
	
		DB::beginTransaction();
		try {
			app(UserTree::class)->updateByUserId(['wormyTime' => date('Y-m-d H:i:s')], $userId);

			app(UserLog::class)->create([
				'userId' => $userId, 'curType' => 'bug', 'joinUserId' => $userId, 'content' => 'tool.tree_on_bug', 
				'datetime' => date('Y-m-d H:i:s')
			]);

			DB::commit();
        } catch (Exception $e) {
            DB::rollback();
        }
	}

	public function onActivate($event) {
	
		$userId = $this->request->input('userId', 0);
		$activateNo = $this->request->input('activateNo', '');
		
		// 检测激活码
		$activate = app(Activate::class)->getOneByActivateNo($activateNo);
		if (! $activate)
		{
			throw new Exception('激活码不存在！');
		}
		if ($activate->status)
		{
			throw new Exception('激活码已使用！');
		}

		DB::beginTransaction();
		try {
			app(UserProfile::class)->updateById(['isActivate' => 1, 'activateTime' => date('Y-m-d H:i:s')], $userId);

			app(Activate::class)->updateById(['status' => 1], $activate->id);

			DB::commit();
        } catch (Exception $e) {
            DB::rollback();
			throw new Exception('使用失败！');
        }

	}

	public function onPackage($event) {
		
		$userId = $this->request->input('userId', 0);
		$packageNo = $this->request->input('packageNo', '');
		
		// 检测礼包
		$package = app(Package::class)->getOneByPackageNo($packageNo);
		if (! $package)
		{
			throw new Exception('礼包不存在！');
		}
		if ($package->status)
		{
			throw new Exception('礼包已使用！');
		}

		DB::beginTransaction();
		try {
			app(UserProfile::class)->updateById(['isPackage' => 1, 'packageTime' => date('Y-m-d H:i:s')], $userId);

			app(Package::class)->updateById(['status' => 1], $package->id);

			app(UserPackage::class)->create([
				'userId' => $userId, 'packageNo' => $package->packageNo, 'level' => $package->level, 
				'datetime' => date('Y-m-d H:i:s')
			]);

			DB::commit();
        } catch (Exception $e) {
            DB::rollback();
			throw new Exception('使用失败！');
        }

	}

	public function onPackage2Tool($event) {
		
		$packageNo = $event->getPackageNo();
		$sendDateTime = $event->getSendDateTime();
		
		$userPackage = app(UserPackage::class)->getOneByPackageNo($packageNo);
		
		if (! $userPackage)
		{
			return;
		}

		if ($userPackage->sendEnd)
		{
			return;
		}

		$datetime = new \DateTime(substr($userPackage->datetime, 0, 10));
		if (empty($sendDateTime))
		{
			$sendDateTime = $datetime;
		} else {
			$sendDateTime = new \DateTime($sendDateTime);
		}
	
		$sendDay = $sendDateTime->format('Ymd');

		if ($userPackage->sendDay >= $sendDay)
		{
			return;
		}

		$tools = app(PackageTool::class)->getListByLevel($userPackage->level);
		$diff = $sendDateTime->diff($datetime);
		$sendEnd = 1;

		DB::beginTransaction();
		try {

			foreach ($tools as $tool)
			{
				$sendTimes = intval($diff->days / $tool->everyDays) + 1;

				if ($tool->sendTimes < $sendTimes)
				{
					continue;
				} else if ($tool->sendTimes > $sendTimes) {
					$sendEnd = 0;
				}
				
				$ndiff = $sendDateTime->diff(new \DateTime());
				if (0 != $diff->days % $tool->everyDays)
				{
					continue;
				}

				$toolCount = app(UserToolCount::class)->getOneByUserIdToolId($userPackage->userId, $tool->toolId);
				
				app(UserToolCount::class)->incrementTool($userPackage->userId, $tool->toolId, $tool->sendNum);

				app(UserToolLog::class)->create([
					'userId' => $userPackage->userId, 'toolId' => $tool->toolId, 'changeType' => 1, 'changeNum' => $tool->sendNum, 
					'oldNum' => $toolCount->num, 'newNum' => ($toolCount->num + $tool->sendNum), 'content' => 'tool.package_2_tool',
					'datetime' => date('Y-m-d H:i:s')
				]);
			}

			$userPackage->sendEnd = $sendEnd;
			$userPackage->sendDay = $sendDay;
			$userPackage->save();

			DB::commit();
        } catch (Exception $e) {
            DB::rollback();
        }
		
	}
	
    /**
     * @param $events
     *
     * 为订阅者注册监听器
     */
    public function subscribe($events) {

        $events->listen(
            'App\Events\User\FertEvent',
            'App\Listeners\user\UserToolListener@onFert'
        );

		$events->listen(
            'App\Events\User\WormEvent',
            'App\Listeners\user\UserToolListener@onWorm'
        );

		$events->listen(
            'App\Events\User\RipeningEvent',
            'App\Listeners\user\UserToolListener@onRipening'
        );

		$events->listen(
            'App\Events\User\AntiTheftEvent',
            'App\Listeners\user\UserToolListener@onAntiTheft'
        );
		
		$events->listen(
            'App\Events\User\DrugEvent',
            'App\Listeners\user\UserToolListener@onDrug'
        );

		$events->listen(
            'App\Events\User\BugEvent',
            'App\Listeners\user\UserToolListener@onBug'
        );

		$events->listen(
            'App\Events\User\ActivateEvent',
            'App\Listeners\user\UserToolListener@onActivate'
        );

		$events->listen(
            'App\Events\User\PackageEvent',
            'App\Listeners\user\UserToolListener@onPackage'
        );

		$events->listen(
            'App\Events\User\Package2ToolEvent',
            'App\Listeners\user\UserToolListener@onPackage2Tool'
        );

    }

}

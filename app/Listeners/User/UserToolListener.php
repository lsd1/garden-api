<?php namespace App\Listeners\user;

use Exception, DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

use App\Repositories\Config\ConfigRepository as Config;

use App\Repositories\User\UserLogRepository as UserLog;
use App\Repositories\User\UserTreeRepository as UserTree;
use App\Repositories\User\UserToolLogRepository as UserToolLog;
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
				'userId' => $userId, 'joinUserId' => $userId, 'content' => 'tool.tool_repellent_used', 
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

		$beMature = app(UserTreeFruit::class)->getBeMatureByUserId($toolId);


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
				'userId' => $userId, 'joinUserId' => $userId, 'content' => 'tool.tool_anti_theft_used', 
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
				'userId' => $userId, 'joinUserId' => $userId, 'content' => 'tool.tool_repellent_used', 
				'datetime' => date('Y-m-d H:i:s')
			]);

			DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            throw new Exception('使用失败！');
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

    }

}

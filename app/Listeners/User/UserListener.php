<?php namespace App\Listeners\User;

use Exception, DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

use App\Repositories\Config\ConfigRepository as Config;

use App\Repositories\User\UserRepository as User;
use App\Repositories\User\UserLogRepository as UserLog;
use App\Repositories\User\UserTreeRepository as UserTree;
use App\Repositories\User\UserTokenRepository as UserToken;
use App\Repositories\User\UserCountRepository as UserCount;
use App\Repositories\User\UserAttachRepository as UserAttach;
use App\Repositories\User\UserProfileRepository as UserProfile;
use App\Repositories\User\UserPackageRepository as UserPackage;
use App\Repositories\User\UserDayCountRepository as UserDayCount;
use App\Repositories\User\UserScoreLogRepository as UserScoreLog;
use App\Repositories\User\UserScoreTakeRepository as UserScoreTake;
use App\Repositories\User\UserTreeFruitRepository as UserTreeFruit;

use App\Repositories\Config\PackageRepository as Package;

class UserListener
{
	
	private $request;

	private $user;
	private $userToken;

    /**
     * 创建监听器
     *
     * 构造函数
     */
    public function __construct(Request $request, User $user, UserToken $userToken) {

        $this->request = $request;

		$this->user = $user;
		$this->userToken = $userToken;

    }

	public function onRegister($event) {
		
		$username = $this->request->input('username', '');
		$nickname = $this->request->input('nickname', $username);
		$password = $this->request->input('password', '');
		$inviter = $this->request->input('inviter', 0);
		$salt = str_random(6);
		$datetime = date('Y-m-d H:i:s');
		
		$r = str_random(10);
		$packageNo1 = 'R1' . $r;
		$packageNo2 = 'R2' . $r;

		DB::beginTransaction();
		try {
			$user = $this->user->create([
				'username' => $username, 'nickname' => $nickname, 'password' => md5($password . $salt), 
				'salt' => $salt, 'datetime' => $datetime
			]);

			app(UserProfile::class)->create([
				'userId' => $user->id	
			]);

			app(UserTree::class)->create([
				'userId' => $user->id	
			]);

			// 激活用户
			app(UserProfile::class)->updateById(['isActivate' => 1, 'activateTime' => date('Y-m-d H:i:s')], $user->id);

			// 激活码
			app(Package::class)->create([
				'packageNo' => $packageNo1, 'level' => 98, 'batch' => '注册', 'status' => 1, 'datetime' => date('Y-m-d H:i:s')
			]);
			app(UserPackage::class)->create([
				'userId' => $user->id, 'packageNo' => $packageNo1, 'level' => 98, 'datetime' => date('Y-m-d H:i:s')
			]);

			if ($inviter > 0)
			{
				app(Package::class)->create([
					'packageNo' => $packageNo2, 'level' => 99, 'batch' => '分享', 'status' => 1, 'datetime' => date('Y-m-d H:i:s')
				]);
				app(UserPackage::class)->create([
					'userId' => $inviter, 'packageNo' => $packageNo2, 'level' => 99, 'datetime' => date('Y-m-d H:i:s')
				]);
			}

			DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            throw new Exception('创建失败！');
        }

		try {
			event(new \App\Events\User\Package2ToolEvent($packageNo1));
			event(new \App\Events\User\Package2ToolEvent($packageNo2));
		} catch (\Exception $e) {

		}

	}

	public function onLogin($event) {
		
		$userId = $this->request->input('userId', 0);
		$username = $this->request->input('username', '');
		$version = $this->request->input('version', '1.0.0');
		$clientType = $this->request->input('clientType', 0);
		$network = $this->request->input('network', 0);
		$lang = $this->request->input('lang', 0);
		$token = $this->request->input('token', '');
		$datetime = date('Y-m-d H:i:s');

		$login = $this->userToken->getOneByUserId($userId);
		
		if ($login)
		{
			$login->version = $version;
			$login->clientType = $clientType;
			$login->network = $network;
			$login->lang = $lang;
			$login->token = $token;
			$login->datetime = $datetime;

			$login->save();
		} else {
			$login = $this->userToken->create([	
				'userId' => $userId, 'version' => $version,
				'clientType' => $clientType, 'network' => $network,
				'lang' => $lang, 'token' => $token,
				'datetime' => $datetime	
			]);
		}
		
		if (! $login)
		{
			throw new Exception('登录失败！');
		}

	}

	public function onLogout($event) {
		
		$userId = $this->request->input('userId', 0);
		$username = $this->request->input('username', '');
		$token = md5(str_random(30) . $username);
		
		$login = $this->userToken->getOneByUserId($userId);
		
		if ($login)
		{
			$login->token = $token;

			$login->save();
		}

	}

	public function onUploadAvatar($event) {
		
		$userId = $this->request->input('userId', 0);
		$username = $this->request->input('username', '');
		$avatarPath = $this->request->input('avatarPath', '');
		$avatar = $this->request->input('avatar', '');

		$attach = app(UserAttach::class)->getOne($userId, 1, 1);

		$l = strripos($avatarPath, '/');
		$path = substr($avatarPath, 0, $l);
		$file = $path . '/' . substr($avatarPath, $l + 1);

		DB::beginTransaction();
		try {
			Storage::put($file, $avatar);

			if ($attach)
			{
				$attach->url = $file;
				$attach->datetime = date('Y-m-d H:i:s');
				$attach->save();
			} else {
				app(UserAttach::class)->create([
					'userId' => $userId, 'curType' => 1, 'useType' => 1, 'url' => $file, 'datetime' => date('Y-m-d H:i:s')
				]);
			}

			DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            throw new Exception('上传失败！');
        }

	}

	public function onTakeScore($event) {

		$username = $this->request->input('username', '');
		$userId = $this->request->input('userId', 0);
		$score = $this->request->input('score', 0);
		$address = $this->request->input('address', '');

		$count = app(UserCount::class)->getOneByUserId($userId);

		DB::beginTransaction();
		try {
			
			app(UserCount::class)->decrementScore($userId, $score);

			app(UserScoreLog::class)->create([
				'userId' => $userId, 'changeType' => 0, 'changeScore' => $score,
				'oldScore' => $count->score, 'newScore' => $count->score - $score,
				'content' => 'user.user_score_take', 'datetime' => date('Y-m-d H:i:s')
			]);

			app(UserScoreTake::class)->create([
				'userId' => $userId, 'address' => $address, 'score' => $score,
				'datetime' => date('Y-m-d H:i:s')
			]);

			DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            throw new Exception('提取失败！');
        }
	}

	public function onWater($event) {
		
		$userId = $this->request->input('userId', 0);
		$toUserId = $this->request->input('toUserId', 0);
		$toUserId = $toUserId > 0 ? $toUserId : $userId;
	
		DB::beginTransaction();
		try {

			app(UserTree::class)->updateByUserId(['waterTime' => date('Y-m-d H:i:s')], $toUserId);

			app(UserLog::class)->create([
				'userId' => $toUserId, 'curType' => 'water', 'joinUserId' => $userId, 'content' => 'user.user_water_tree', 
				'datetime' => date('Y-m-d H:i:s')
			]);

			DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            throw new Exception('浇水失败！');
        }
		
	}

	public function onStealFruit($event) {
		
		$userId = $this->request->input('userId', 0);
		$toUserId = $this->request->input('toUserId', 0);
		$antiTheft = $this->request->input('antiTheft', 0);

		if ($antiTheft == 1)
		{
			// 检查防护盾
			$tree = app(UserTree::class)->getOneByUserId($toUserId);
			if (strcmp($tree->antiTheftTime, '0000-00-00 00:00:00') != 0)
			{
				$now = new \DateTime();
				$antiTheftTime = new \DateTime($tree->antiTheftTime);
				if ($antiTheftTime->getTimestamp() > $now->getTimestamp())
				{
					DB::beginTransaction();
					try {

						app(UserLog::class)->create([
							'userId' => $toUserId, 'curType' => 'steal', 'joinUserId' => $userId, 'joinFruit' => 0, 'content' => 'user.user_steal_fruit', 
							'datetime' => date('Y-m-d H:i:s')
						]);

						app(UserDayCount::class)->fruitSteal($userId, 0);
				
						DB::commit();
					} catch (Exception $e) {
						DB::rollback();
						throw new Exception('偷取失败！');
					}		

					return;
				}
			}
		}

		$key = env('CACHE_PREFIX', 'gd_') . 'steal_' . $toUserId;
		$vue = $toUserId . '_' . $userId . '_' . mt_rand(11111, 99999);
		$usleep = 100000;

		do {
			$do = Cache::get($key);

			if (! $do)
			{
				$expiresAt = new \DateTime();
				Cache::put($key, $vue, $expiresAt->modify('+3 seconds'));
			} else {
				usleep($usleep);
			}
		} while (! $do);
		
		if ($do == $vue)
		{
			$fruitMaxStealRate = app(Config::class)->getContentByKey('FruitMaxStealRate', 0.1);
			$activateStealMinNum = app(Config::class)->getContentByKey('ActivateStealMinNum', 1);
			$activateStealMaxNum = app(Config::class)->getContentByKey('ActivateStealMaxNum', 3);
			$packageStealMinNum = app(Config::class)->getContentByKey('PackageStealMinNum', 2);
			$packageStealMaxNum = app(Config::class)->getContentByKey('PackageStealMaxNum', 6);
			
			// 可偷取量检查
			$tree = app(UserTree::class)->getOneByUserId($toUserId);
			$fruit = intval($tree->matureFruit * $fruitMaxStealRate);
			$hasStealFruit = $fruit - $tree->stealFruit;
			if ($hasStealFruit <= 0)
			{
				throw new Exception('已偷取太多！');
			}
			
			// 计算可偷取量
			$stealFruit = 0;
			$profile =  app(UserProfile::class)->getOneByUserId($userId);
			if ($profile)
			{
				if ($profile->isPackage)
				{
					if ($hasStealFruit <= $packageStealMinNum)
					{
						$stealFruit = intval(mt_rand(1, $hasStealFruit));
					} else if ($hasStealFruit <= $packageStealMaxNum) {
						$stealFruit = intval(mt_rand($packageStealMinNum, $hasStealFruit));
					} else {
						$stealFruit = intval(mt_rand($packageStealMinNum, $packageStealMaxNum));
					}
				} else if ($profile->isActivate) {
					if ($hasStealFruit <= $activateStealMinNum)
					{
						$stealFruit = intval(mt_rand(1, $hasStealFruit));
					} else if ($hasStealFruit <= $activateStealMaxNum) {
						$stealFruit = intval(mt_rand($activateStealMinNum, $hasStealFruit));
					} else {
						$stealFruit = intval(mt_rand($activateStealMinNum, $activateStealMaxNum));
					}
				}
			}
			if ($stealFruit <= 0)
			{
				throw new Exception('你偷啥子哟！');
			}

			$this->request->merge(['fruit' => $stealFruit]);
			
			// 用户统计数据
			$count = app(UserCount::class)->getOneByUserId($userId);

			// 用户日统计数据
			$dayCount = app(UserDayCount::class)->getOneByUserId($userId);
			
			DB::beginTransaction();
			try {
				app(UserTree::class)->fruitSteal($toUserId, $stealFruit);

				app(UserLog::class)->create([
					'userId' => $toUserId, 'curType' => 'steal', 'joinUserId' => $userId, 'joinFruit' => $stealFruit, 'content' => 'user.user_steal_fruit', 
					'datetime' => date('Y-m-d H:i:s')
				]);

				app(UserCount::class)->incrementScore($userId, $stealFruit);

				app(UserScoreLog::class)->create([
					'userId' => $userId, 'changeType' => 1, 'changeScore' => $stealFruit,
					'oldScore' => $count->score, 'newScore' => $count->score + $stealFruit,
					'content' => 'user.user_steal_fruit', 'datetime' => date('Y-m-d H:i:s')
				]);

				app(UserDayCount::class)->fruitSteal($userId, $stealFruit);
				
				DB::commit();
			} catch (Exception $e) {
				DB::rollback();
				throw new Exception('偷取失败！');
			} finally {
				Cache::forget($key);
			}
		}
			
	}

	public function onPickFruit($event) {
	
		$userId = $this->request->input('userId', 0);

		$key = env('CACHE_PREFIX', 'gd_') . 'steal_' . $userId;
		$vue = $userId . '_' . $userId . '_' . mt_rand(11111, 99999);
		$usleep = 100000;

		do {
			$do = Cache::get($key);

			if (! $do)
			{
				$expiresAt = new \DateTime();
				Cache::put($key, $vue, $expiresAt->modify('+3 seconds'));
			} else {
				usleep($usleep);
			}
		} while (! $do);
		
		if ($do == $vue)
		{
			// 可摘取量检查
			$tree = app(UserTree::class)->getOneByUserId($userId);
			$pickFruit = $tree->matureFruit - $tree->stealFruit;
			if ($pickFruit <= 0)
			{
				throw new Exception('树都摘光了！');
			}
			
			$this->request->merge(['fruit' => $pickFruit]);
			
			// 用户统计数据
			$count = app(UserCount::class)->getOneByUserId($userId);

			// 用户日统计数据
			$dayCount = app(UserDayCount::class)->getOneByUserId($userId);
			$treeFruit = app(UserTreeFruit::class)->getOneBeMatureByUserId($userId);
			
			DB::beginTransaction();
			try {
				if ($treeFruit)
				{
					app(UserTree::class)->fruitPick($userId, $tree->matureFruit, $treeFruit->matureTime);
				} else {
					app(UserTree::class)->fruitPick($userId, $tree->matureFruit);
				}

				app(UserLog::class)->create([
					'userId' => $userId, 'curType' => 'pick', 'joinUserId' => $userId, 'joinFruit' => $pickFruit, 'content' => 'user.user_pick_fruit', 
					'datetime' => date('Y-m-d H:i:s')
				]);

				app(UserCount::class)->incrementScore($userId, $pickFruit);

				app(UserScoreLog::class)->create([
					'userId' => $userId, 'changeType' => 1, 'changeScore' => $pickFruit,
					'oldScore' => $count->score, 'newScore' => $count->score + $pickFruit,
					'content' => 'user.user_pick_fruit', 'datetime' => date('Y-m-d H:i:s')
				]);

				app(UserDayCount::class)->fruitPick($userId, $pickFruit);
				
				DB::commit();
			} catch (Exception $e) {
				DB::rollback();
				throw new Exception('摘取失败！');
			} finally {
				Cache::forget($key);
			}
		}

	}

    /**
     * @param $events
     *
     * 为订阅者注册监听器
     */
    public function subscribe($events) {

        $events->listen(
            'App\Events\User\RegisterEvent',
            'App\Listeners\user\UserListener@onRegister'
        );

		$events->listen(
            'App\Events\User\LoginEvent',
            'App\Listeners\user\UserListener@onLogin'
        );

		$events->listen(
            'App\Events\User\LogoutEvent',
            'App\Listeners\user\UserListener@onLogout'
        );

		$events->listen(
            'App\Events\User\UploadAvatarEvent',
            'App\Listeners\user\UserListener@onUploadAvatar'
        );
		
		$events->listen(
            'App\Events\User\TakeScoreEvent',
            'App\Listeners\user\UserListener@onTakeScore'
        );

		$events->listen(
            'App\Events\User\WaterEvent',
            'App\Listeners\user\UserListener@onWater'
        );

		$events->listen(
            'App\Events\User\StealFruitEvent',
            'App\Listeners\user\UserListener@onStealFruit'
        );

		$events->listen(
            'App\Events\User\PickFruitEvent',
            'App\Listeners\user\UserListener@onPickFruit'
        );

    }

}

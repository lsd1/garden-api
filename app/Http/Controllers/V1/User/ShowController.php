<?php namespace App\Http\Controllers\V1\User;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

use App\Repositories\User\UserRepository as User;
use App\Repositories\User\UserLogRepository as UserLog;
use App\Repositories\User\UserTreeRepository as UserTree;
use App\Repositories\User\UserCountRepository as UserCount;
use App\Repositories\User\UserAttachRepository as UserAttach;
use App\Repositories\User\UserProfileRepository as UserProfile;
use App\Repositories\User\UserScoreLogRepository as UserScoreLog;

use App\Repositories\Config\ConfigRepository as Config;

class ShowController extends Controller
{
	
	private $request;

	private $user;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request, User $user) {

		$this->request = $request;

		$this->user = $user;
        
    }

	public function showGarden(UserTree $userTree, Config $config, UserAttach $userAttach) {
		
		$token = $this->request->input('token', '');
		$lang = $this->request->input('lang', 0);
		$userId = $this->request->input('userId', 0);
		$toUsername = $this->request->input('toUsername', '');
		$toUserId = 0;
		$now = time();
		
		if (! empty($toUsername))
		{
			$user = $this->user->getOneByUsername($toUsername);
			$toUserId = $user ? $user->id : 0;
		}
		
		$tree = $userTree->getOneByUserId($toUserId > 0 ? $toUserId : $userId);

		$data = [];

		$data['avatar'] = $userAttach->getAvatarByUserId($toUserId > 0 ? $toUserId : $userId);

		$data['isMature'] = 0;
		if ($tree->matureTime != '0000-00-00 00:00:00')
		{
			$mature = strtotime($tree->matureTime);
			$data['isMature'] = $mature <= $now ? 1 : 0;
		}

		$activeWaterMinTime =  $config->getContentByKey('ActiveWaterMinTime', 14400);
		$data['isWater'] = 0;
		$water = strtotime($tree->waterTime);
		if ($now > $water + $activeWaterMinTime)
		{
			$data['isWater'] = 1;
		}
		
		$data['isDry'] = 0;
		if ($tree->dryTime != '0000-00-00 00:00:00')
		{
			$data['isDry'] = 1;
		}

		$data['isWormy'] = 0;
		if ($tree->wormyTime != '0000-00-00 00:00:00')
		{
			$data['isWormy'] = 1;
		}
		
		$data['isNight'] = 0;
		$nightStart =  $config->getContentByKey('NightStart', 18);
		$nightEnd =  $config->getContentByKey('NightEnd', 6);
		$h = date('H', $now);
		if ($h > $nightStart || $h < $nightEnd)
		{
			$data['isNight'] = 1;
		}

		return ['code' => 0, 'msg' => trans('user.request_success'), 'data' => $data, 'lang' => $lang, 'token' => $token, 'datetime' => date('Y-m-d H:i:s')];

	}

	public function socreLogs(UserCount $userCount, UserProfile $userProfile, UserScoreLog $userScoreLog) {

		$token = $this->request->input('token', '');
		$lang = $this->request->input('lang', 0);
		$userId = $this->request->input('userId', 0);
		
		$list = [];
		$userScoreLog->pushCriteria(new \App\Repositories\Criteria\User\ScoreLogListCriteria($this->request));
		foreach ($userScoreLog->all() as $row)
		{
			$list[] = [
				'id' => $row->id,
				'changeScore' => ($row->changeType ? '+' : '-') . $row->changeScore,
				'content' => trans($row->content),
				'datetime' => $row->datetime
			];
		}

		$count = $userCount->getOneByUserId($userId);
		$profile = $userProfile->getOneByUserId($userId);

		return ['code' => 0, 'msg' => trans('user.request_success'), 'data' => ['score' => $count->score, 'activateTime' => $profile->activateTime, 'scoreLogList' => $list], 'lang' => $lang, 'token' => $token, 'datetime' => date('Y-m-d H:i:s')];

	}

	public function userLogs(UserLog $userLog, UserAttach $userAttach) {
		
		$token = $this->request->input('token', '');
		$lang = $this->request->input('lang', 0);
		
		$list = [];
		$userLog->pushCriteria(new \App\Repositories\Criteria\User\UserLogListCriteria($this->request));
		foreach ($userLog->all() as $row)
		{
			$user = $this->user->getOneById($row->joinUserId);
			$avatar = $userAttach->getAvatarByUserId($user->id);

			$list[] = [
				'id' => $row->id,
				'username' => $user->username,
				'avatar' => $avatar,
				'curType' => $row->curType,
				'fruit' => $row->joinFruit,
				'content' => trans($row->content),
				'datetime' => $row->datetime
			];
		}
		
		return ['code' => 0, 'msg' => trans('user.request_success'), 'data' => ['userLogList' => $list], 'lang' => $lang, 'token' => $token, 'datetime' => date('Y-m-d H:i:s')];

	}

	public function pickList(UserTree $userTree, UserAttach $userAttach) {
		
		$token = $this->request->input('token', '');
		$lang = $this->request->input('lang', 0);
		$now = time();

		$list = [];
		$userTree->pushCriteria(new \App\Repositories\Criteria\User\PickListCriteria($this->request));
		foreach ($userTree->all() as $row)
		{
			$user = $this->user->getOneById($row->userId);
			$avatar = $userAttach->getAvatarByUserId($user->id);
			
			$mature = strtotime($row->matureTime);
			$isMature = $mature <= $now ? 1 : 0;
			$countdown = $isMature ? 0 : ($mature - $now);
			$isWater = 1;

			$list[] = [
				'id' => $row->id,
				'username' => $user->username,
				'avatar' => $avatar,
				'isMature' => $isMature,
				'countdown' => $countdown,
				'isWater' => $isWater,
				'datetime' => $row->matureTime
			];
		}

		return ['code' => 0, 'msg' => trans('user.request_success'), 'data' => ['pickList' => $list], 'lang' => $lang, 'token' => $token, 'datetime' => date('Y-m-d H:i:s')];

	}

}

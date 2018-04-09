<?php namespace App\Http\Controllers\V1\User;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

use App\Repositories\User\UserRepository as User;
use App\Repositories\User\UserTreeRepository as UserTree;
use App\Repositories\User\UserCountRepository as UserCount;
use App\Repositories\User\UserToolCountRepository as UserToolCount;

class ActionController extends Controller
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
    
	public function uploadAvatar() {
		
		$token = $this->request->input('token', '');
		$lang = $this->request->input('lang', 0);
		$username = $this->request->input('username', '');
		$userId = $this->request->input('userId', 0);
		$avatar = $this->request->file('avatar');
	
		$validator = Validator::make(['avatar' => $avatar], ['avatar' => 'required|image']);
		if ($validator->fails()) 
		{
			return ['code' => 111, 'msg' => trans('user.user_avatar_must_picture'), 'lang' => $lang, 'token' => '', 'datetime' => date('Y-m-d H:i:s')];
		}

		try {
			$avatarPath = 'user/avatars/' . $userId . '.' . $avatar->getClientOriginalExtension();
			$this->request->merge(['avatarPath' => $avatarPath]);
			
			event(new \App\Events\User\UploadAvatarEvent());
		} catch (\Exception $e) {
			return ['code' => 111, 'msg' => trans('user.user_avatar_upload_faild'), 'lang' => $lang, 'token' => '', 'datetime' => date('Y-m-d H:i:s')];
		}

		return ['code' => 0, 'msg' => trans('user.user_avatar_upload_success'), 'data' => ['avatar' => get_file_address($avatarPath)], 'lang' => $lang, 'token' => $token, 'datetime' => date('Y-m-d H:i:s')];

	}

	public function drawScore(UserCount $userCount) {
		
		$token = $this->request->input('token', '');
		$lang = $this->request->input('lang', 0);
		$username = $this->request->input('username', '');
		$userId = $this->request->input('userId', 0);
		$score = $this->request->input('score', 0);
	
		$count = $userCount->getOneByUserId($userId);

		if ($count->score < $score)
		{
			return ['code' => 111, 'msg' => trans('user.user_score_not_enough'), 'lang' => $lang, 'token' => '', 'datetime' => date('Y-m-d H:i:s')];
		}

		try {
			event(new \App\Events\User\TakeScoreEvent());
		} catch (\Exception $e) {
			return ['code' => 111, 'msg' => trans('user.user_score_take_faild'), 'lang' => $lang, 'token' => '', 'datetime' => date('Y-m-d H:i:s')];
		}

		return ['code' => 0, 'msg' => trans('user.user_score_take_success'), 'data' => ['score' => intval($count->score - $score)], 'lang' => $lang, 'token' => $token, 'datetime' => date('Y-m-d H:i:s')];

	}

	public function putWater(UserTree $userTree) {
		
		$token = $this->request->input('token', '');
		$lang = $this->request->input('lang', 0);
		$userId = $this->request->input('userId', 0);
		$toUsername = $this->request->input('toUsername', '');

		if (! empty($toUsername))
		{
			$user = $this->user->getOneByUsername($toUsername);
			$this->request->merge(['toUserId' => ($user ? $user->id : 0)]);
		}

		try {
			event(new \App\Events\User\WaterEvent());
		} catch (\Exception $e) {
			
		}

		return ['code' => 0, 'msg' => trans('user.request_success'), 'lang' => $lang, 'token' => $token, 'datetime' => date('Y-m-d H:i:s')];

	}

	public function putSteal(UserTree $userTree) {
		
		$token = $this->request->input('token', '');
		$lang = $this->request->input('lang', 0);
		$userId = $this->request->input('userId', 0);
		$toUsername = $this->request->input('toUsername', '');

		if (! empty($toUsername))
		{
			$user = $this->user->getOneByUsername($toUsername);
			$toUserId = $user ? $user->id : 0;
			$this->request->merge(['toUserId' => $toUserId]);
		} else {
			return ['code' => 111, 'msg' => trans('user.user_steal_fruit_faild'), 'lang' => $lang, 'token' => '', 'datetime' => date('Y-m-d H:i:s')];
		}

		if ($userId == $toUserId || $toUserId == 0)
			return ['code' => 111, 'msg' => trans('user.user_steal_fruit_faild'), 'lang' => $lang, 'token' => '', 'datetime' => date('Y-m-d H:i:s')];

		try {
			event(new \App\Events\User\StealFruitEvent());
		} catch (\Exception $e) {
			return ['code' => 111, 'msg' => trans('user.user_steal_fruit_faild'), 'lang' => $lang, 'token' => '', 'datetime' => date('Y-m-d H:i:s')];
		}

		$fruit = $this->request->input('fruit', 0);
		return ['code' => 0, 'msg' => trans('user.user_steal_fruit_success', ['fruit' => $fruit]), 'lang' => $lang, 'token' => $token, 'datetime' => date('Y-m-d H:i:s')];

	}

	public function putPick(UserTree $userTree) {
		
		$token = $this->request->input('token', '');
		$lang = $this->request->input('lang', 0);
		$userId = $this->request->input('userId', 0);

		try {
			event(new \App\Events\User\PickFruitEvent());
		} catch (\Exception $e) {
			return ['code' => 111, 'msg' => trans('user.user_pick_fruit_faild'), 'lang' => $lang, 'token' => '', 'datetime' => date('Y-m-d H:i:s')];
		}

		$fruit = $this->request->input('fruit', 0);
		return ['code' => 0, 'msg' => trans('user.user_pick_fruit_success', ['fruit' => $fruit]), 'lang' => $lang, 'token' => $token, 'datetime' => date('Y-m-d H:i:s')];

	}

	public function useTool(UserToolCount $userToolCount) {
		
		$token = $this->request->input('token', '');
		$lang = $this->request->input('lang', 0);
		$userId = $this->request->input('userId', 0);
		$toolId = $this->request->input('toolId', 0);
		$useNum = $this->request->input('useNum', 1);

		// ����������
		$toolCount = $userToolCount->getOneByUserIdToolId($userId, $toolId);
		if (! $toolCount || $toolCount->num < $useNum)
		{
			return ['code' => 111, 'msg' => trans('tool.tool_num_not_enough'), 'lang' => $lang, 'token' => '', 'datetime' => date('Y-m-d H:i:s')];
		}

		
		switch($toolId)
		{
			case 1:
				event(new \App\Events\User\FertEvent());
				break;
			case 2:
				try {
					event(new \App\Events\User\WormEvent());
				} catch (\Exception $e) {
					return ['code' => 111, 'msg' => trans('tool.not_has_worm'), 'lang' => $lang, 'token' => '', 'datetime' => date('Y-m-d H:i:s')];
				}
				break;
			case 3:
				try {
					event(new \App\Events\User\RipeningEvent());
				} catch (\Exception $e) {
					return ['code' => 111, 'msg' => trans('tool.not_has_ripener'), 'lang' => $lang, 'token' => '', 'datetime' => date('Y-m-d H:i:s')];
				}
				break;
			case 4:
				try {
					event(new \App\Events\User\AntiTheftEvent());
				} catch (\Exception $e) {
					return ['code' => 111, 'msg' => trans('tool.tool_num_not_enough'), 'lang' => $lang, 'token' => '', 'datetime' => date('Y-m-d H:i:s')];
				}
				break;
			case 5:
				try {
					event(new \App\Events\User\DrugEvent());
				} catch (\Exception $e) {
					return ['code' => 111, 'msg' => trans('tool.not_has_drug'), 'lang' => $lang, 'token' => '', 'datetime' => date('Y-m-d H:i:s')];
				}
				break;	
			default:
		}
		

		return ['code' => 0, 'msg' => trans('user.request_success'), 'lang' => $lang, 'token' => $token, 'datetime' => date('Y-m-d H:i:s')];

	}

}
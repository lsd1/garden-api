<?php namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Repositories\User\UserRepository as User;
use App\Repositories\User\UserAttachRepository as UserAttach;
use App\Repositories\User\UserProfileRepository as UserProfile;

class LoginController extends Controller
{
	
	private $request;

	private $user;
	private $userAttach;
	private $userProfile;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request, User $user, UserAttach $userAttach, UserProfile $userProfile) {

		$this->request = $request;

		$this->user = $user;
		$this->userAttach = $userAttach;
		$this->userProfile = $userProfile;
        
    }
	
	public function login() {
		
		$lang = $this->request->input('lang', 0);
		$username = $this->request->input('username', '');
		$password = $this->request->input('password', '');
		$token = md5(str_random(30) . $username);

		$user = $this->user->getOneByUsername($username);

		if (! $user)
		{
			return ['code' => 111, 'msg' => trans('user.username_password_error'), 'lang' => $lang, 'token' => '', 'datetime' => date('Y-m-d H:i:s')];
		}

		if (strcmp($user->password, md5($password . $user->salt)) != 0)
		{
			return ['code' => 111, 'msg' => trans('user.username_password_error'), 'lang' => $lang, 'token' => '', 'datetime' => date('Y-m-d H:i:s')];
		}

		$this->request->merge(['userId' => $user->id, 'token' => $token]);
		
		try {
			event(new \App\Events\User\LoginEvent());
		} catch (\Exception $e) {
			return ['code' => 111, 'msg' => trans('user.login_faild'), 'lang' => $lang, 'token' => '', 'datetime' => date('Y-m-d H:i:s')];
		}

		$avatar = $this->userAttach->getAvatarByUserId($user->id);
		$profile =  $this->userProfile->getOneByUserId($user->id);

		$userInfo = [
			'id' => $user->id, 'username' => $username, 'avatar' => $avatar, 'isActivate' => $profile->isActivate, 'isPackage' => $profile->isPackage
		];
		
		return ['code' => 0, 'msg' => trans('user.login_success'), 'data' => ['userInfo' => $userInfo], 'lang' => $lang, 'token' => $token, 'datetime' => date('Y-m-d H:i:s')];

	}

}

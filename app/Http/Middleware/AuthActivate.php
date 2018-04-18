<?php namespace App\Http\Middleware;

use Closure;

use App\Repositories\User\UserProfileRepository as UserProfile;

class AuthActivate
{
	
	private $UserProfile;

	public function __construct(UserProfile $userProfile) {

		$this->userProfile = $userProfile;

	}

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
		
		$token = $request->input('token', '');
		$lang = $request->input('lang', 0);
		$username = $request->input('username', '');
		$userId = $request->input('userId', 0);
		
		$profile = $this->userProfile->getOneByUserId($userId);
		if (! $profile || $profile->isActivate != 1)
		{
			return ['code' => 112, 'msg' => trans('user.user_activate_must'), 'lang' => $lang, 'token' => '', 'datetime' => date('Y-m-d H:i:s')];
		}

		return $next($request);

    }
}

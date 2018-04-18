<?php namespace App\Http\Middleware;

use Closure;

use App\Repositories\Config\ConfigRepository as Config;

use App\Repositories\User\UserProfileRepository as UserProfile;
use App\Repositories\User\UserDayCountRepository as UserDayCount;

class AuthDayCount
{
	
	private $config;
	private $userProfile;
	private $userDayCount;

	public function __construct(Config $config, UserProfile $userProfile, UserDayCount $userDayCount) {
		
		$this->config = $config;
		$this->userProfile = $userProfile;
		$this->userDayCount = $userDayCount;

	}

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
		
		if (strpos($request->path(), '/user/put_steal') >= 0)
		{
			return $this->_checkSteal($request, $next);
		} else {
			return $next($request);
		}

    }

	private function _checkSteal($request, Closure $next) {
		
		$token = $request->input('token', '');
		$lang = $request->input('lang', 0);
		$username = $request->input('username', '');
		$userId = $request->input('userId', 0);

		$profile = $this->userProfile->getOneByUserId($userId);
		$dayCount = $this->userDayCount->getOneByUserId($userId);

		if ($profile->isPackage)
		{
			$num = $this->config->getContentByKey('PackageDayStealNum', 10);
		} else {
			$num = $this->config->getContentByKey('ActivateDayStealNum', 2);
		}

		if ($dayCount->stealNum >= $num)
		{
			return ['code' => 111, 'msg' => trans('user.user_day_steal_times', ['times' => $num]), 'lang' => $lang, 'token' => '', 'datetime' => date('Y-m-d H:i:s')];
		} else {
			return $next($request);
		}

	}
}

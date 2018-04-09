<?php namespace App\Http\Middleware;

use Closure;

use App\Repositories\User\UserTokenRepository as UserToken;

class AuthSign
{
	
	private $userToken;

	public function __construct(UserToken $userToken) {

		$this->userToken = $userToken;

	}

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
		
		$clientType = $request->input('clientType', 1);
		$lang = $request->input('lang', 0);
		$network = $request->input('network', 1);
		$timestamp = $request->input('timestamp', time());
		$username = $request->input('username', '');
		$version = $request->input('version', '1.0.0');
		$userId = $request->input('userId', 0);
		$uuid = $request->input('uuid', uniqid());
		$action = $request->path();
		$token = '';

		if (strlen($username) < 8 || strlen($username) > 30)
		{
			return ['code' => 111, 'msg' => trans('user.username_format_error'), 'lang' => $lang, 'token' => $token, 'datetime' => date('Y-m-d H:i:s')];
		}

		if ($userId > 0)
		{
			$login = $this->userToken->getOneByUserId($userId);
			if (! $login)
			{
				return ['code' => 110, 'msg' => trans('user.login_error'), 'lang' => $lang, 'token' => $token, 'datetime' => date('Y-m-d H:i:s')];
			}

			$token = $login->token;
		}

		$param = "clientType={$clientType}&lang={$lang}&network={$network}&timestamp={$timestamp}&username={$username}&version={$version}";
		$sign = md5("{$param}token={$token}uuid={$uuid}action={$action}");

		if (strcmp($request->input('sign', ''), $sign) == 0)
		{
			$request->merge(['token' => $token]);
			return $next($request);
		} else {
			if ($userId > 0)
			{
				return ['code' => 110, 'msg' => trans('user.login_error'), 'lang' => $lang, 'token' => '', 'datetime' => date('Y-m-d H:i:s')];
			} else {
				return ['code' => 201, 'msg' => trans('user.request_error'), 'lang' => $lang, 'token' => '', 'datetime' => date('Y-m-d H:i:s')];
			}
		}

    }
}

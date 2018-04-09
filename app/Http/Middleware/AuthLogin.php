<?php namespace App\Http\Middleware;

use Closure;

class AuthLogin
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
		
		$lang = $request->input('lang', 0);
		$userId = $request->input('userId', 0);

		if ($userId <= 0)
		{
			return ['code' => 110, 'msg' => trans('user.login_error'), 'lang' => $lang, 'token' => '', 'datetime' => date('Y-m-d H:i:s')];
		}
		
        return $next($request);

    }

}

<?php namespace App\Http\Middleware;

use Closure;

class AuthVersion
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {

		$version = $request->input('version', '1.0.0');
		$lang = $request->input('lang', 0);

		return $next($request);

    }

}

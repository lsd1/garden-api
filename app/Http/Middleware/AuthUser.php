<?php namespace App\Http\Middleware;

use Closure;

use App\Repositories\User\UserRepository as User;

class AuthUser
{
	
	private $user;

	public function __construct(User $user) {

		$this->user = $user;

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
		$userId = 0;

		if (! empty($username))
		{
			$user = $this->user->getOneByUsername($username);
			if ($user)
			{
				$userId = $user->id;
			}
		}

		$request->merge(['userId' => $userId]);
		
        return $next($request);

    }
}

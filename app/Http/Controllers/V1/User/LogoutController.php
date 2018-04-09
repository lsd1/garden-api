<?php namespace App\Http\Controllers\V1\User;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Repositories\User\UserRepository as User;

class LogoutController extends Controller
{
	
	private $request;

	private $user;
	private $userAccount;
	private $userCount;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request, User $user) {

		$this->request = $request;

		$this->user = $user;
        
    }
    
	public function logout() {
		
		$lang = $this->request->input('lang', 0);
		$username = $this->request->input('username', '');

		$user = $this->user->getOneByUsername($username);

		if ($user)
		{
			$this->request->merge(['userId' => $user->id]);

			event(new \App\Events\User\LogoutEvent());
		}

		return ['code' => 0, 'msg' => trans('user.logout_success'), 'lang' => $lang, 'token' => '', 'datetime' => date('Y-m-d H:i:s')];
		
	}

}

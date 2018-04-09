<?php namespace App\Http\Controllers\V1\User;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Repositories\User\UserRepository as User;

class UserPwdController extends Controller
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
    
	public function edit() {
		
		$token = $this->request->input('token', '');
		$lang = $this->request->input('lang', 0);
		$username = $this->request->input('username', '');
		$oldpassword = $this->request->input('oldpassword', '');
		$newpassword = $this->request->input('newpassword', '');

		$user = $this->user->getOneByUsername($username);

		if (strcmp($user->password, md5($oldpassword . $user->salt)) != 0)
		{
			return ['code' => 111, 'msg' => trans('user.old_password_error'), 'lang' => $lang, 'token' => '', 'datetime' => date('Y-m-d H:i:s')];
		}

		$user->password = md5($newpassword . $user->salt);
		$user->save();

		return ['code' => 0, 'msg' => trans('user.edit_password_success'), 'lang' => $lang, 'token' => $token, 'datetime' => date('Y-m-d H:i:s')];
		
	}

}

<?php namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Repositories\User\UserRepository as User;

class RegisterController extends Controller
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
	
	public function index() {
		
		$lang = $this->request->input('lang', 0);
		$username = $this->request->input('username', '');

		$account = $this->user->getOneByUsername($username);

		if ($account)
		{
			return ['code' => 111, 'msg' => trans('user.username_exist'), 'lang' => $lang, 'token' => '', 'datetime' => date('Y-m-d H:i:s')];
		}
		
		try {
			event(new \App\Events\User\RegisterEvent());
		} catch (\Exception $e) {
			return ['code' => 111, 'msg' => trans('user.register_faild'), 'lang' => $lang, 'token' => '', 'datetime' => date('Y-m-d H:i:s')];
		}
		
		return ['code' => 0, 'msg' => trans('user.register_success'), 'lang' => $lang, 'token' => '', 'datetime' => date('Y-m-d H:i:s')];
	}
    
	public function checkUsername() {
		
		$lang = $this->request->input('lang', 0);
		$username = $this->request->input('username', '');
		
		$account = $this->user->getOneByUsername($username);
		
		if ($account)
		{
			return ['code' => 111, 'msg' => trans('user.username_exist'), 'lang' => $lang, 'token' => '', 'datetime' => date('Y-m-d H:i:s')];
		} else {
			return ['code' => 0, 'msg' => trans('user.username_success'), 'lang' => $lang, 'token' => '', 'datetime' => date('Y-m-d H:i:s')];
		}

	}

}

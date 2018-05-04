<?php namespace App\Http\Controllers\V1\Share;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

class ShowController extends Controller
{
	
	private $request;

	private $user;
	private $userToolCount;

	private $tool;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request) {

		$this->request = $request;
        
    }

	public function index() {
		
		$lang = $this->request->input('lang', 0);
		$token = $this->request->input('token', '');
		$username = $this->request->input('username', '');
		$userId = $this->request->input('userId', 0);

		return ['code' => 0, 'msg' => trans('user.request_success'), 'data' => ['shareUrl' => env('WEB_ADDRESS', 'http://www.ebcgame.com') . "?action=login&id={$userId}"], 'lang' => $lang, 'token' => $token, 'datetime' => date('Y-m-d H:i:s')];

	}

}
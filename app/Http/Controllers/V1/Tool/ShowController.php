<?php namespace App\Http\Controllers\V1\Tool;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Repositories\User\UserRepository as User;
use App\Repositories\User\UserToolCountRepository as UserToolCount;

use App\Repositories\Tool\ToolRepository as Tool;

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
    public function __construct(Request $request, User $user, UserToolCount $userToolCount, Tool $tool) {

		$this->request = $request;

		$this->user = $user;
		$this->userToolCount = $userToolCount;

		$this->tool = $tool;
        
    }

	public function index() {
		
		$lang = $this->request->input('lang', 0);
		$token = $this->request->input('token', '');
		$username = $this->request->input('username', '');
		$userId = $this->request->input('userId', 0);

		$tools = $this->tool->getSellTool();

		$toolCount = $this->userToolCount->getToolCountByUserId($userId);

		$toolList = [];

		foreach ($tools as $row)
		{
			$toolList[] = [
				'toolId' => $row->id,
				'toolname' => $row->toolname,
				'banner' => get_file_address($row->banner),
				'count' => isset($toolCount[$row->id]) ? $toolCount[$row->id] : 0
			];
		}

		return ['code' => 0, 'msg' => trans('user.request_success'), 'data' => ['toolList' => $toolList], 'lang' => $lang, 'token' => $token, 'datetime' => date('Y-m-d H:i:s')];

	}

	public function tips() {
		
		$lang = $this->request->input('lang', 0);
		$token = $this->request->input('token', '');
		$username = $this->request->input('username', '');
		$userId = $this->request->input('userId', 0);
		$toolId = $this->request->input('toolId', 0);

		$tool = $this->tool->getOneById($toolId);

		if (! $tool || $tool->isSell != 1)
		{
			return ['code' => 111, 'msg' => trans('tool.tool_not_exist'), 'lang' => $lang, 'token' => '', 'datetime' => date('Y-m-d H:i:s')];
		}

		$toolInfo = [
			'toolId' => $tool->id,
			'toolname' => $tool->toolname,
			'tooltips' => $tool->describe,
		];
		
		return ['code' => 0, 'msg' => trans('user.request_success'), 'data' => ['toolInfo' => $toolInfo], 'lang' => $lang, 'token' => $token, 'datetime' => date('Y-m-d H:i:s')];

	}

}

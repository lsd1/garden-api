<?php namespace App\Repositories\Tool;

use Bosnadev\Repositories\Eloquent\Repository;

use App\Repositories\Tool\ToolCNRepository as ToolCN;
use App\Repositories\Tool\ToolENRepository as ToolEN;

class ToolRepository extends Repository 
{

    private static $locale = 'cn';

	/**
     * @return string
     *
     * 绑定模型
     */
    public function model() {
		if (self::$locale == 'en')
			return app(ToolEN::class)->model();
		else
			return app(ToolCN::class)->model();
    }

	public function setLocale($locale = 'cn') {
		if ($locale == 'en')
			self::$locale = 'en';
		else
			self::$locale = 'cn';
	}

	public function getOneById($id) {
		
		return $this->find($id);

	}

	public function getSellTool() {
		
		return $this->model
			        ->where('isSell', 1)
			        ->orderBy('id', 'asc')
			        ->get();

	}

}
<?php namespace App\Repositories\Tool;

use Bosnadev\Repositories\Eloquent\Repository;

class ToolENRepository extends Repository 
{

    /**
     * @return string
     *
     * 绑定模型
     */
    public function model() {

        return 'App\Models\Tool\ToolEN';

    }
	
	public function getOneById($id) {
		
		return $this->find($id);

	}

}
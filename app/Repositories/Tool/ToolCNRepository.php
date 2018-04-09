<?php namespace App\Repositories\Tool;

use Bosnadev\Repositories\Eloquent\Repository;

class ToolCNRepository extends Repository 
{

    /**
     * @return string
     *
     * 绑定模型
     */
    public function model() {

        return 'App\Models\Tool\ToolCN';

    }
	
	public function getOneById($id) {
		
		return $this->find($id);

	}

}
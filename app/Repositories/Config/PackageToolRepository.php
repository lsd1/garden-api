<?php namespace App\Repositories\Config;

use Bosnadev\Repositories\Eloquent\Repository;

class PackageToolRepository extends Repository 
{

    /**
     * @return string
     *
     * 绑定模型
     */
    public function model() {

        return 'App\Models\Config\PackageTool';

    }
	
	public function getListByLevel($level) {
		
		return $this->model
			        ->where('level', $level)
			        ->get();

	}

}
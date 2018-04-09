<?php namespace App\Repositories\Config;

use Bosnadev\Repositories\Eloquent\Repository;

class ActivateRepository extends Repository 
{

    /**
     * @return string
     *
     * 绑定模型
     */
    public function model() {

        return 'App\Models\Config\Activate';

    }
	
	public function getOneById($id) {
		
		return $this->find($id);

	}

	public function getOneByActivateNo($activateNo) {

		return $this->model
			        ->where('activateNo', $activateNo)
			        ->first();

	}

	public function updateById($data, $id) {
		
		return $this->update($data, $id);

	}

	public function updateByActivateNo($data, $activateNo) {
		
		return $this->update($data, $activateNo, 'activateNo');

	}

}
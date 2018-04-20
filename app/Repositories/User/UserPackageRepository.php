<?php namespace App\Repositories\User;

use Bosnadev\Repositories\Eloquent\Repository;

class UserPackageRepository extends Repository 
{

    /**
     * @return string
     *
     * 绑定模型
     */
    public function model() {

        return 'App\Models\User\UserPackage';

    }
	
	public function getOneById($id) {
		
		return $this->find($id);

	}

	public function getOneByPackageNo($packageNo) {

		return $this->model
			        ->where('packageNo', $packageNo)
			        ->first();

	}

	public function updateById($data, $id) {
		
		return $this->update($data, $id);

	}

	public function updateByPackageNo($data, $packageNo) {
		
		return $this->update($data, $packageNo, 'packageNo');

	}

	public function hasSend($limit = 100) {
		
		return $this->model
			        ->where('sendEnd', 0)
			        ->where('sendDay', '>', 0)
			        ->where('sendDay', '<', date('Ymd'))
			        ->take($limit)
			        ->get();

	}

}
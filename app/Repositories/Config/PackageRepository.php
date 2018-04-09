<?php namespace App\Repositories\Config;

use Bosnadev\Repositories\Eloquent\Repository;

class PackageRepository extends Repository 
{

    /**
     * @return string
     *
     * 绑定模型
     */
    public function model() {

        return 'App\Models\Config\Package';

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

}
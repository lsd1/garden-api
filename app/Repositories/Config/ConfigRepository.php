<?php namespace App\Repositories\Config;

use Bosnadev\Repositories\Eloquent\Repository;

class ConfigRepository extends Repository 
{

    /**
     * @return string
     *
     * 绑定模型
     */
    public function model() {

        return 'App\Models\Config\Config';

    }
	
	public function getOneById($id) {
		
		return $this->find($id);

	}

	public function getOneByKey($key) {
		
		return $this->model->where('key', $key)->first();

	}

	public function getContentByKey($key, $default = '') {
		
		$res = $this->getOneByKey($key);

		if ($res)
			return $res->content;
		else
			return $default;

	}

}
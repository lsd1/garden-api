<?php namespace App\Repositories\User;

use Bosnadev\Repositories\Eloquent\Repository;

class UserRepository extends Repository 
{

    /**
     * @return string
     *
     * 绑定模型
     */
    public function model() {

        return 'App\Models\User\User';

    }
	
	public function getOneById($id) {
		
		return $this->find($id);

	}

	public function getOneByUsername($username) {
		
		return $this->findBy('username', $username);

	}

	public function updateById($data, $id) {
		
		return $this->model
		            ->where('id', $id)
		            ->update($data);

	}

}
<?php namespace App\Repositories\User;

use Bosnadev\Repositories\Eloquent\Repository;

class UserProfileRepository extends Repository 
{

    /**
     * @return string
     *
     * 绑定模型
     */
    public function model() {

        return 'App\Models\User\UserProfile';

    }
	
	public function getOneById($id) {
		
		return $this->find($id);

	}

	public function getOneByUserId($userId) {

		return $this->model
			        ->where('userId', $userId)
			        ->first();

	}

	public function updateById($data, $id) {
		
		return $this->update($data, $id);

	}

}
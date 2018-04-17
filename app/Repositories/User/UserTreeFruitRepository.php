<?php namespace App\Repositories\User;

use Bosnadev\Repositories\Eloquent\Repository;

class UserTreeFruitRepository extends Repository 
{

    /**
     * @return string
     *
     * 绑定模型
     */
    public function model() {

        return 'App\Models\User\UserTreeFruit';

    }
	
	public function getOneById($id) {
		
		return $this->find($id);

	}

	public function getBeMatureByUserId($userId) {
		
		return $this->model
			        ->where('userId', $userId)
			        ->where('isMature', 0)
			        ->get();

	}
 
	public function getOneBeMatureByUserId($userId) {

		return $this->model
			        ->where('userId', $userId)
			        ->where('isMature', 0)
			        ->first();
		
	}

}
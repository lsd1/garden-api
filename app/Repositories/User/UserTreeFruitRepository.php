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

	public function getBeMatureBySeconds($seconds = 60) {
		
		return $this->model
			        ->where('isMature', 0)
			        ->where('matureTime', '<=', date('Y-m-d H:i:s', strtotime("+{$seconds} seconds")))
			        ->orderBy('matureTime', 'asc')
			        ->get();

	}

}
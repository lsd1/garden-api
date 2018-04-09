<?php namespace App\Repositories\User;

use Bosnadev\Repositories\Eloquent\Repository;

class UserCountRepository extends Repository 
{

    /**
     * @return string
     *
     * 绑定模型
     */
    public function model() {

        return 'App\Models\User\UserCount';

    }
	
	public function getOneById($id) {
		
		return $this->find($id);

	}

	public function getOneByUserId($userId) {

		$res = $this->findBy('userId', $userId);

		if (! $res)
		{
			$this->create(['userId' => $userId, 'score' => 0]);
			return $this->getOneByUserId($userId);
		}

		return $res;

	}

	public function incrementScore($userId, $score) {
		return $this->model->where('userId', $userId)->increment('score', $score);
	}
	
	public function decrementScore($userId, $score) {
		return $this->model->where('userId', $userId)->decrement('score', $score);
	}

}
<?php namespace App\Repositories\User;

use Bosnadev\Repositories\Eloquent\Repository;

class UserTokenRepository extends Repository 
{

    /**
     * @return string
     *
     * 绑定模型
     */
    public function model() {

        return 'App\Models\User\UserToken';

    }

	public function getOneById($id) {
		
		return $this->find($id);

	}

	public function getOneByUserId($userId) {
		
		return $this->findBy('userId', $userId);

	}

}
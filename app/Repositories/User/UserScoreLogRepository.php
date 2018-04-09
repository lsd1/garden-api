<?php namespace App\Repositories\User;

use Bosnadev\Repositories\Eloquent\Repository;

class UserScoreLogRepository extends Repository 
{

    /**
     * @return string
     *
     * 绑定模型
     */
    public function model() {

        return 'App\Models\User\UserScoreLog';

    }
	
	public function getOneById($id) {
		
		return $this->find($id);

	}

}
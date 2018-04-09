<?php namespace App\Repositories\User;

use Bosnadev\Repositories\Eloquent\Repository;

class UserScoreTakeRepository extends Repository 
{

    /**
     * @return string
     *
     * 绑定模型
     */
    public function model() {

        return 'App\Models\User\UserScoreTake';

    }
	
	public function getOneById($id) {
		
		return $this->find($id);

	}

}
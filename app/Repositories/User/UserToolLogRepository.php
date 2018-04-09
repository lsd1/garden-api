<?php namespace App\Repositories\User;

use Bosnadev\Repositories\Eloquent\Repository;

class UserToolLogRepository extends Repository 
{

    /**
     * @return string
     *
     * 绑定模型
     */
    public function model() {

        return 'App\Models\User\UserToolLog';

    }
	
	

}
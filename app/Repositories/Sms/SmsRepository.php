<?php namespace App\Repositories\Sms;

use Bosnadev\Repositories\Eloquent\Repository;

class SmsRepository extends Repository 
{

    /**
     * @return string
     *
     * 绑定模型
     */
    public function model() {

        return 'App\Models\Sms\Sms';

    }
	
	public function getOneById($id) {
		
		return $this->find($id);

	}

	public function getOneByMobile($mobile) {
		
		return $this->model
			        ->where('mobile', $mobile)
			        ->first();

	}

}
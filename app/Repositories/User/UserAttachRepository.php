<?php namespace App\Repositories\User;

use Bosnadev\Repositories\Eloquent\Repository;

class UserAttachRepository extends Repository 
{

    /**
     * @return string
     *
     * 绑定模型
     */
    public function model() {

        return 'App\Models\User\UserAttach';

    }
	
	public function getOneById($id) {
		
		return $this->find($id);

	}

	public function getOne($userId, $curType, $useType) {
		
		return $this->model
		           ->where('userId', $userId)
		           ->where('curType', 1)
		           ->where('useType', 1)
		           ->first();

	}

	public function getAvatarByUserId($userId) {
		
		$data = $this->model
		             ->where('userId', $userId)
		             ->where('curType', 1)
		             ->where('useType', 1)
		             ->first();

		return get_file_address($data ? $data->url : 'user/avatar/default.jpg');
			         

	}

}
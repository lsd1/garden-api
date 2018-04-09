<?php namespace App\Repositories\User;

use Bosnadev\Repositories\Eloquent\Repository;

class UserTreeRepository extends Repository 
{

    /**
     * @return string
     *
     * 绑定模型
     */
    public function model() {

        return 'App\Models\User\UserTree';

    }
	
	public function getOneById($id) {
		
		return $this->find($id);

	}

	public function getOneByUserId($userId) {
		
		return $this->model->where('userId', $userId)->first();

	}

	public function updateByUserId($data, $userId) {
		
		return $this->update($data, $userId, 'userId');

	}

	public function fruitMature($userId, $fruit) {
		
		return $this->model->where('userId', $userId)->increment('matureFruit', $fruit);

	}
	
	public function fruitSteal($userId, $fruit) {

		return $this->model->where('userId', $userId)->increment('stealFruit', $fruit);
		
	}
	
	public function fruitPick($userId, $fruit) {
		
		return $this->model->where('userId', $userId)->decrement('matureFruit', $fruit, ['matureTime' => '0000-00-00 00:00:00', 'stealFruit' => 0]);

	}

}
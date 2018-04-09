<?php namespace App\Repositories\User;

use Bosnadev\Repositories\Eloquent\Repository;

class UserDayCountRepository extends Repository 
{

    /**
     * @return string
     *
     * 绑定模型
     */
    public function model() {

        return 'App\Models\User\UserDayCount';

    }
	
	public function getOneById($id) {
		
		return $this->find($id);

	}

	public function getOneByUserId($userId) {
		
		$dayDate = date('Ymd');
		$res = $this->getOneByUserIdDatDate($userId, $dayDate);

		if (! $res)
		{
			$this->create(['userId' => $userId, 'dayDate' => $dayDate]);
			return $this->getOneByUserId($userId);
		}

		return $res;

	}

	public function getOneByUserIdDatDate($userId, $dayDate) {
		
		return $this->model
			        ->where('userId', $userId)
			        ->where('dayDate', $dayDate)
			        ->first();

	}

	public function fruitSteal($userId, $fruit) {
		
		$dayDate = date('Ymd');
		return $this->model
			        ->where('userId', $userId)
			        ->where('dayDate', $dayDate)
					->update([
						'stealNum' => \DB::raw('stealNum+1'),
						'stealFruit' => \DB::raw("stealFruit+{$fruit}")
		]);
		
	}

	public function fruitPick($userId, $fruit) {
		
		$dayDate = date('Ymd');
		return $this->model
			        ->where('userId', $userId)
			        ->where('dayDate', $dayDate)
					->update([
						'pickNum' => \DB::raw('pickNum+1'),
						'pickFruit' => \DB::raw("pickFruit+{$fruit}")
		]);
		
	}

}
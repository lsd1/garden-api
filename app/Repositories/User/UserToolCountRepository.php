<?php namespace App\Repositories\User;

use Bosnadev\Repositories\Eloquent\Repository;

class UserToolCountRepository extends Repository 
{

    /**
     * @return string
     *
     * 绑定模型
     */
    public function model() {

        return 'App\Models\User\UserToolCount';

    }
	
	public function getListByUserId($userId) {

		return $this->model
			        ->where('userId', $userId)
			        ->orderBy('toolId', 'asc')
					->get();
		
	}

	public function getToolCountByUserId($userId) {
	
		$data = [];

		$res = $this->getListByUserId($userId);
		foreach ($res as $row)
		{
			$data[$row->toolId] = $row->num;
		}

		return $data;

	}

	public function getOneByUserIdToolId($userId, $toolId) {
		
		$res = $this->model
			        ->where('userId', $userId)
			        ->where('toolId', $toolId)
					->first();

		if (! $res)
		{
			$this->create(['userId' => $userId, 'toolId' => $toolId, 'num' => 0]);

			return $this->getOneByUserIdToolId($userId, $toolId);
		}
		
		return $res;

	}

	public function decrementTool($userId, $toolId, $num) {
		
		return $this->model
			        ->where('userId', $userId)
			        ->where('toolId', $toolId)
					->decrement('num', $num);

	}
	
	public function incrementTool($userId, $toolId, $num) {
		
		return $this->model
			        ->where('userId', $userId)
			        ->where('toolId', $toolId)
					->increment('num', $num);

	}

}
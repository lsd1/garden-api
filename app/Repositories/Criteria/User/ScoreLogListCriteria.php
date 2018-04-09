<?php namespace App\Repositories\Criteria\User;

use Illuminate\Http\Request;
use Bosnadev\Repositories\Criteria\Criteria;
use Bosnadev\Repositories\Contracts\RepositoryInterface as Repository;

class ScoreLogListCriteria extends Criteria {

	private $request;

	public function __construct(Request $request) {
		$this->request = $request;
	}

	public function apply($model, Repository $repository) {

		$userId = $this->request->input('userId', 0);
		$lastId = $this->request->input('lastId', 0);
		$limit = $this->request->input('limit', 20);

		if ($lastId > 0)
			$model = $model->where('id', '<', $lastId);

		return $model->where('userId', $userId)->orderBy('id', 'desc')->take($limit);

	}
}
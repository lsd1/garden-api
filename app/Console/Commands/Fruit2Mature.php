<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

use App\Repositories\User\UserTreeFruitRepository as UserTreeFruit;

class Fruit2Mature extends Command
{

	protected $signature = 'garden:fruit2Mature';

	protected $description = 'garden fruit be mature';

	public function handle() {
		
		$sleep = 1;

		$list = app(UserTreeFruit::class)->getBeMatureBySeconds();

		foreach ($list as $row) 
		{
			$now = new \DateTime();
			$matureTime = new \DateTime($row->matureTime);

			$sleep = $matureTime->getTimestamp() - $matureTime->getTimestamp() - 10;
			
			if ($sleep <= 0)
			{
				dispatch((new \App\Jobs\FruitMature($row->id))->onQueue('fruitMature'));
			} else {
				sleep($sleep);
			}	
		}

		$this->comment(PHP_EOL . "garden fruit mature success " . date('Y-m-d H:i:s') . PHP_EOL);

	}

}
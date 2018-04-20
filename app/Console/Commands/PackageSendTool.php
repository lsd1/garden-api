<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

use App\Repositories\User\UserPackageRepository as UserPackage;

class PackageSendTool extends Command
{

	protected $signature = 'garden:packageSendTool';

	protected $description = 'garden package send tool';

	public function handle() {
		
		$do = true;
		$sleep = 1;
		$datetime = date('Y-m-d H:i:s');
		
		do {

			$res = app(UserPackage::class)->hasSend();

			if (count($res) > 0)
			{
				
				foreach ($res as $row)
				{
					
					event(new \App\Events\User\Package2ToolEvent($row->packageNo, $datetime));

				}

				sleep($sleep);

			} else {
			
				$do = false;

			}

		} while ($do);

		$this->comment(PHP_EOL . "garden package send tool success " . date('Y-m-d H:i:s') . PHP_EOL);

	}

}
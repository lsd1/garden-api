<?php namespace App\Events\User;

use App\Events\Event;

class Package2ToolEvent extends Event
{
	
	private $packageNo;
	private $sendDateTime;

    /**
     * Create a new event instance.
     *
     * @return void
     */
	public function __construct($packageNo, $sendDateTime = '') {

		$this->packageNo = $packageNo;
		$this->sendDateTime = $sendDateTime;

	}

	public function getPackageNo() {
		
		return $this->packageNo;

	}

	public function getSendDateTime() {
		
		return $this->sendDateTime;

	}

}

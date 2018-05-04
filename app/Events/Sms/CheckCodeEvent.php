<?php namespace App\Events\Sms;

use App\Events\Event;

class CheckCodeEvent extends Event
{
	public $mobile;
	
	public $code;

	public $type;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($mobile, $code, $type = 1) {

        $this->mobile = $mobile;
		$this->code = $code;
		$this->type = $type;

    }
}

<?php namespace App\Listeners\Sms;

use Exception, DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

use App\Repositories\Sms\SmsRepository as Sms;

class SmsListener
{
	const TARGET = 'http://106.ihuyi.cn/webservice/sms.php?method=Submit';

	const APIID = 'C27033744';

	const APIKEY = '617bdbbf0868b7a2973fcb4583ff5b2f';
	
	private $request;

	/**
	 * 创建监听器
	 *
	 * 构造函数
	 */
	public function __construct(Request $request) {

		$this->request = $request;

	}

	public function onSendCode($event) {
		$mobile = $event->mobile;
		$code = $event->code;
		$type = $event->type;
		$now = new \DateTime();
		
		$sms = app(Sms::class)->getOneByMobile($mobile);
		$send = false;

		if ($sms)
		{
			$sendTime = new \DateTime($sms->sendTime);
			if ($sms->type != $type || $sendTime->getTimestamp() < $now->getTimestamp() - 60)
			{
				$sms->code = $code;
				$sms->type = $type;
				$sms->sendTime = $now->format('Y-m-d H:i:s');
				
				$send = $sms->save();
			}
		} else {
			$send = app(Sms::class)->create([
				'mobile' => $mobile, 'code' => $code, 'type' => $type, 'sendTime' => $now->format('Y-m-d H:i:s')
			]);
		}

		if ($send)
		{
			try
			{
				$data = 'account=' . self::APIID . '&password=' . self::APIKEY . '&mobile=' . $mobile . '&content=' . rawurlencode('您的验证码是：' . $code . '。请不要把验证码泄露给其他人。');
				$response =  $this->_curl_post(self::TARGET, $data);
			
			} catch (Exception $e) {

			}
		}
	}

	public function onCheckCode($event) {
		
		$mobile = $event->mobile;
		$code = $event->code;
		$type = $event->type;
		$now = new \DateTime();

		$sms = app(Sms::class)->getOneByMobile($mobile);
		if (! $sms)
		{
			throw new Exception ('验证码错误！');
		}

		if ($sms->type != $type)
		{
			throw new Exception ('验证码错误！');
		}

		$sendTime = new \DateTime($sms->sendTime);

		if ($sendTime->getTimestamp() < $now->getTimestamp() - 300)
		{
			throw new Exception ('验证码超时！');
		}
		
		if ($sms->code != $code)
		{
			throw new Exception ('验证码错误！');
		}

	}

	private function _curl_post($url, $data) {
		
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_NOBODY, true);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

		$res = curl_exec($curl);

		curl_close($curl);

		return $res;

	}

	private function _xml_to_array($xml) {
		
		$reg = "/<(\w+)[^>]*>([\\x00-\\xFF]*)<\\/\\1>/";

		if (preg_match_all($reg, $xml, $matches)) 
		{
			$count = count($matches[0]);

			for ($i = 0; $i < $count; $i++)
			{
				$subxml= $matches[2][$i];
				$key = $matches[1][$i];

				if (preg_match( $reg, $subxml ))
				{
					$arr[$key] = $this->_xml_to_array($subxml);
				} else {
					$arr[$key] = $subxml;
				}
			}
		}
		
		return $arr;
		
	}

	/**
	 * @param $events
	 *
	 * 为订阅者注册监听器
	 */
	public function subscribe($events) {

		$events->listen(
			'App\Events\Sms\SendCodeEvent',
			'App\Listeners\Sms\SmsListener@onSendCode'
		);
		
		$events->listen(
			'App\Events\Sms\CheckCodeEvent',
			'App\Listeners\Sms\SmsListener@onCheckCode'
		);
		
	}

}

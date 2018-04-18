<?php namespace App\Http\Middleware;

use Closure;

use App\Repositories\Config\ConfigRepository as Config;

class AntiTheft
{
	
	private $config;
	private $userTree;

	public function __construct(Config $config) {

		$this->config = $config;

	}

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {

		// ¼ÆËã¸ÅÂÊ
		$preventStealChance = $this->config->getContentByKey('PreventStealChance', 0.8);
		$max = 1000;
		$p = $preventStealChance * $max;
		$m = mt_rand(1, $max);
		
		if ($m <= $p)
		{
			$request->merge(['antiTheft' => 1]);
		}
		
        return $next($request);

    }
}

<?php

if (! function_exists('config_path')) {
    /**
     * Get the configuration path.
     *
     * @param  string  $path
     * @return string
     */
    function config_path($path = '')
    {
        return app()->make('path.config').($path ? DIRECTORY_SEPARATOR.$path : $path);
    }
}

if (! function_exists('get_file_address')) {
	/**
     * Get the file address.
     *
     * @param  string  $path
     * @return string
     */
	function get_file_address($path = '') {
		return $path ? (env('APP_IMG_ADDRESS', '') . $path) : '';
	}
}

if (! function_exists('create_code')) {
	/**
     * Create random num.
     *
     * @param  int  $length
     * @return string
     */
	function create_code($length = 4) {
		$nums = '0123456789';
		$code = '';

		for ($i = 0; $i < $length; $i++) 
		{
			$code .= $nums[mt_rand(0, strlen($nums) - 1)];
		}

		return $code;
	}
}
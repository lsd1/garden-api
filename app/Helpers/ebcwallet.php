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
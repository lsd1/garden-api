<?php namespace App\Models\Config;

use Illuminate\Database\Eloquent\Model;

class Activate extends Model
{
	/**
	 * 与模型关联的数据表
	 *
	 * @var string
	 */
	protected $table = 'activate';

	/**
	 * 该模型的主键
	 *
	 * @var string
	 */
	protected $primaryKey = 'id';

	/**
	 * 该模型是否被自动维护时间戳
	 *
	 * @var bool
	 */
	public $timestamps = false;

}
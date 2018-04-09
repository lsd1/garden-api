<?php namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
	/**
	 * 与模型关联的数据表
	 *
	 * @var string
	 */
	protected $table = 'user';

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

	/**
     * 该模型的可分配属性
     *
     * @var array
     */
    protected $fillable = [
        'username', 'password', 'salt', 'datetime'
    ];

    /**
     * 该模型的需隐藏属性
     *
     * @var array
     */
    protected $hidden = [
        'password', 'salt'
    ];

}
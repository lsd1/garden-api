<?php namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
	/**
	 * 与模型关联的数据表
	 *
	 * @var string
	 */
	protected $table = 'user_profile';

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
        'userId', 'inviter', 'isActivate', 'activateTime', 'isPackage', 'packageTime'
    ];

}
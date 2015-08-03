<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Eloquent implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['name', 'email', 'password'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token'];

	public function device_status() {
		return $this->hasMany('App\DeviceStatus');
	}

	public function audits() {
		return $this->hasMany('App\Audit');
	}

	public static function getUserCount() {
		return count(User::where('type', 'user')->get());
	}

	public function activity()
	{
		return $this->hasMany('App\Activity');
	}

	public function recordActivity($name, $related)
	{
		return $related->recordActivity($name);
	}

    public function devices(){
        return $this->hasMany('App\Devices');
    }
	
}

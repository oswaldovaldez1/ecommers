<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

/**
 * @property integer $id
 * @property integer $id_rol
 * @property string $firstname
 * @property string $secondname
 * @property string $lastname
 * @property string $email
 * @property string $phone
 * @property string $password
 * @property integer $tipo
 * @property string $api_token
 * @property string $remember_token
 * @property string $created_at
 * @property string $updated_at
 * @property ConfRole $confRole
 */
//class UserModel extends Model
class UserModel extends Authenticatable
{
    use Notifiable, HasApiTokens;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user';

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['id_rol', 'firstname', 'secondname', 'lastname', 'email', 'phone', 'password', 'tipo', 'api_token', 'remember_token', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function confRole()
    {
        return $this->belongsTo('App\ConfRole', 'id_rol');
    }
}
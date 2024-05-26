<?php

namespace app\models\data;

use app\handlers\auth\jwt\contracts\JwtSubject;

use Illuminate\Database\Eloquent\{Model};

/**
 * @method static paginate(int $per_page)
 */
class User extends Model implements JwtSubject {

    /**
     * Making sure, that our model-class is
     * referring to the correct table !?
     */
    protected $table = 'users';

    /**
     * Specifying which columns, we want to write to...
     *
     * @var array
     */
    protected $fillable = [

        'email',
        'password',
        'img_cover',
        'img_avatar',
        'first_name',
        'last_name',
        'last_login'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

        //
    ];

    /**
     * @return int
     */
    public function getJwtIdentifier() : int {

        return $this->id;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sso() {

        return $this->hasMany(UserSocial::class);
    }
}

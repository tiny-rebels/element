<?php

namespace app\models\upload;

use Illuminate\Database\Eloquent\Model;

use Ramsey\Uuid\Uuid;

class Image extends Model {

    /**
     * Making sure, that our model-class is
     * referring to the correct table !?
     */
    protected $table = 'images';

    /**
     * Specifying which columns, we want to write to...
     */
    protected $fillable = [

        'uuid'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

        'id',
        'created_at',
        'updated_at'
    ];

    public static function boot() {

        parent::boot();

        static::creating(function ($image) {

            $image->uuid = Uuid::uuid4()->toString();
        });
    }
}

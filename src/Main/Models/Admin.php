<?php

namespace Main\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer                                  id
 * @property string                                   email
 * @property string                                   username
 * @property string                                   token
 * @property string                                   password
 * @property \Carbon\Carbon                           created_at
 * @property \Carbon\Carbon                           update_at
 * @property \Illuminate\Database\Eloquent\Collection followings  who are followed by this user
 */
class Admin extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email',];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'pswd',
    ];

    /**
     * Return Default Image Profile When User Does Not Have An Image
     *
     * @param $value
     *
     * @return string
     */
    public function getImageAttribute($value)
    {
        if (is_null($value)) {
            return 'https://aiivon.com/wp-content/uploads/2017/08/aiivon-web-logo.png';
        }

        return $value;
    }


    /********************
     *  Relationships
     ********************/



}





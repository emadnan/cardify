<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profile extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table="profiles";
    protected $primarykey="id";
    
    function educations(){
        return $this->hasMany('App\Models\Education','profile_id','id');
    }
    function experiences(){
        return $this->hasMany('App\Models\Experience','profile_id','id');
    }
    function social_links(){
        return $this->hasMany('App\Models\SocialLink','profile_id','id');
    }
    function country(){
        return $this->belongsTo('App\Models\Country','country_id','id');
    }
    function city(){
        return $this->belongsTo('App\Models\City','city_id','id');
    }
    function card(){
        return $this->hasOne('App\Models\Card','id','card_id');
    }
}

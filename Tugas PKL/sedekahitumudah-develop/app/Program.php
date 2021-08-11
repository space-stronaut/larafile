<?php

namespace App;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $fillable = ['users_id', 'category_id', 'title', 'brief_explanation', 'photo', 'donation_target', 'donation_collected', 'time_is_up', 'shelter_account_number', 'description', 'isPublished', 'isSelected', 'status', 'ops_percentage'];

    public function getFoto(){
        return asset('images/program-images/' . $this->photo);
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'users_id');
    }

    public function donatur()
    {
        return $this->hasMany('App\DonationConfirmation')->orderBy('id', 'desc');
    }

    public function reports()
    {
        return $this->hasMany('App\Report');
    }

    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    public function report()
    {
        return $this->hasMany('App\Report');
    }

    public function withdraw(){
        return $this->hasMany('App\Withdraw');
    }

    public function development(){
        return $this->hasMany('App\Development');
    }
}

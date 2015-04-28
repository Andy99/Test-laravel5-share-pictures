<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Picture extends Model {

	public $timestamps = true;
	
	protected $fillable = ['user_id', 'file_name', 'title'];

	public function users() {
        return $this->belongsTo('App\User');
	}

}


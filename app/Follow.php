<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Follow extends Model {

	public $timestamps = true;
	
	protected $fillable = ['user_id', 'follower_id'];

	// public function users() {
 //        return $this->belongsTo('App\User');
	// }

}
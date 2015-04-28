<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\User as User;

class UserTableSeeder extends Seeder {

	public function run()
	{
		DB::table('users')->delete();
		User::create([
			'first_name'	=> 'andy',
			'last_name'		=> 'cola',
			'email'		 	=> 'admin@test.com',
			'password' 		=> bcrypt("12345")
		]); 

	}

}

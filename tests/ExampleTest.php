<?php

class ExampleTest extends TestCase {

	/**
	 * A basic functional test example.
	 *
	 * @return void
	 */
	public function testBasicExample()
	{
		//$response = $this->action('GET', 'HomeController@showEditProfile');

		//$this->assertViewHas('user');
		//dd($response->getContent());
		$this->call('GET', 'edit-profile');
		$this->assertViewHas('user');
	}

}

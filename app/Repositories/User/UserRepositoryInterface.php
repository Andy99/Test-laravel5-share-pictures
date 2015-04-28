<?php namespace App\Repositories\User;
 
interface UserRepositoryInterface {

	public function findByUserNameOrCreate($userData);
	public function checkIfUserNeedsUpdating($userData, $user);
	public function getUsers($id);
	public function getUserProfile($id);
	public function getPictures($id);
	public function addNewFollower(array $data);
	public function removeFollower(array $data); 
	public function getFollowingPictures($id, $paginate, $shownData=null);
	public function getFollowing($id);
	public function isEmpty($arr);
	public function getPicturesByUsername($username);
	public function uploadCount($id);
	//public function ();
	//public function ();
	//public function ();
	//public function ();
	//public function ();
	

}
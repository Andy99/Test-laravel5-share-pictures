<?php namespace App\Repositories\User;

use Illuminate\Contracts\Filesystem\Filesystem as File;
use Intervention\Image\ImageServiceProvider as Image;
use App\User as User;
use App\Picture as Picture;
use App\Follow as Follow;
use Validator;

class EloquentUserRepository implements UserRepositoryInterface {

	public function findByUserNameOrCreate($userData)
	{
		//dd($userData);
		$user = User::where('provider_id', '=', $userData->id)->first();
		
		if(!$user)
		{
            $user = User::create([
	                'provider_id' =>$userData->id,
	                'first_name'  => $userData->user['first_name'],
	                'last_name'   => $userData->user['last_name'],
					'email'       => $userData->email,
                    'username'    => ($userData->nickname) ? $userData->nickname : $userData->user['first_name'].$userData->user['last_name'].rand() ,
	                'avatar'      => $userData->avatar,
	                'provider'	  => 'facebook',
	            ]);
        	
		}

		$this->checkIfUserNeedsUpdating($userData, $user);

        return $user;
	}

	public function checkIfUserNeedsUpdating($userData, $user) {

        $socialData = [
            'avatar' => $userData->avatar,
            'email' => $userData->email,
            'first_name' => $userData->user['first_name'],
            'last_name' => $userData->user['last_name'],
            'username'    => $userData->nickname,
        ];
        $dbData = [
            'avatar' => $user->avatar,
            'email' => $user->email,
            'username' => $user->username,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
        ];

        if (!empty(array_diff($socialData, $dbData))) {
            $user->avatar = $userData->avatar;
            $user->email = $userData->email;
            $user->first_name = $userData->user['first_name'];
            $user->last_name = $userData->user['last_name'];
            $user->save();
        }
    }

    public function getUsers($id)
    {
        //return User::with('pictures')->get();
        return User::where('id', '!=', $id)->get();
    }

    public function getPictures($id)
    {
        return User::where('id', $id)->with('pictures')->first();
    }

    public function getPicturesByUsername($username)
    {
        return User::where('username', $username)->with('pictures')->first();
    }

    public function addNewFollower(array $data)
    {
        $id = $data['user_id'];
        $follower_id = $data['follower_id'];

        $Follow = new Follow();

        $Follow->create([
                'user_id'         => $id,
                'follower_id'     => $follower_id
            ]);

        return true;
    }

    public function getUserProfile($id)
    {
        return User::findOrFail($id);
    }

    public function getFollowingPictures($id, $paginate, $shownData = null )
    {
        //Get User with foolowing
        $rows = User::where('id', $id)->with('following')->first();

        if($rows->following->isEmpty())
        {
            return false;
        }

        // Store the followings ids in array
        foreach ($rows->following as $row) {
            $followers_ids[] = $row->follower_id;
        }

        //Get pictures of all the following oreder by newest
        if(is_null($shownData)){
            $data['pictures'] = Picture::orderBy('created_at', 'DESC')->whereIn('user_id', $followers_ids )->take($paginate)->get();
        }
        else{
            $data['pictures'] = Picture::orderBy('created_at', 'DESC')->whereIn('user_id', $followers_ids )
                                ->skip($shownData)->take($paginate)->get();
        }
 
        // Check if the  the users have pictures else fail call
        if(!$this->isEmpty($data['pictures']))
        {
            return false;
        }

        foreach ($data as $key => $value) {
            foreach ($value as $key) {
                $array[] = $key;
            }
        }

        foreach ($array as $user) {
            // Get all info from the following user
            $user->user_id = User::where('id', $user->user_id)->first();
        }

        return $array;
    }

    public function getFollowing($id)
    {
        $users = $this->getUsers($id);
        foreach ($users as $user) {
            $user->following = Follow::where('follower_id', $user->id)->where('user_id', $id)->first();
        }
       return $users;
    }

    public function removeFollower(array $data)
    {
        $id = $data['user_id'];
        $follower_id = $data['follower_id'];

        // Get the row that need to be deleted
        $row = Follow::where('user_id', $id)->where('follower_id', $follower_id)->delete();
                
        return true;

    }

    public function isEmpty($array) {
        $count="";
        foreach($array as $key => $value)
        {
            foreach ($value as $r) {
                if ($r !== "");
                {
                    $count++;   
                }
            }
        }
        if($count == "")
            return false;
    
        return true;
    }

    public function uploadCount($id)
    {
        //Get User with foolowing
        $rows = User::where('id', $id)->with('following')->first();

        // Store the followings ids in array
        foreach ($rows->following as $row) {
            $followers_ids[] = $row->follower_id;
        }

        //Get pictures of all the following oreder by newest
        $data['pictures'] = Picture::whereIn('user_id', $followers_ids )->get();

        return $data['pictures']->count();
    }

}



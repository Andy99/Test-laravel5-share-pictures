<?php namespace App\Http\Controllers;

use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\Picture\PictureRepositoryInterface;
use App\Repositories\Registrar\RegistrarRepositoryInterface;

use Illuminate\Http\Request;

class HomeController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Home Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders your application's "dashboard" for users that
	| are authenticated. Of course, you are free to change or remove the
	| controller as you wish. It is just here to get your app started!
	|
	*/
	protected $registrar;
	protected $User;
	protected $Picture;
	protected $user_id;

	/**
	 * Items to show per page when returning Index
	 * @var integer
	 */
	protected $per_page = 4;

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(UserRepositoryInterface $User, 
								PictureRepositoryInterface $Picture,
								RegistrarRepositoryInterface $registrar)
	{
		//$this->middleware('auth');
		$this->User = $User;
		$this->Picture = $Picture;
		$this->registrar = $registrar;
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
		// Get all the following
		if(! $followings = $this->User->getFollowingPictures(\Auth::user()->id, $this->per_page))
		{
			$followings = "";
			$rows = "";
		}

		$rows = $this->per_page; // rows fetched from db

		return view('home')->with('followings', $followings)->with('rows', $rows);
	}

	public function showEditProfile()
	{
		$id = \Auth::user()->id;

		$user = $this->User->getUserProfile($id);

		return view('auth.edit-profile')->with('user', $user);

	}

	public function editProfile(Request $request, $id)
	{

		$avatar =  $request->file('avatar');

		$user = $this->registrar->createOrUpdate($request->all(), $id);


		if( $avatar )// If an image has been uploaded update it
			
			$this->registrar->createAvatar($user->id, $avatar);

			return redirect('home');

	}

	/**
	 * Show all the users.
	 *
	 * @return Response
	 */
	public function searchPeople()
	{
		//$users = $this->User->getUsers(\Auth::user()->id);
		//return $users;
		$users = $this->User->getFollowing(\Auth::user()->id);

		return view('app.search_people')->with('users', $users);
	}

	/**
	 * Show all the of the user.
	 *
	 * @return Response
	 */
	public function getPictures($username = null)
	{
		if(is_null($username)){
			$user = $this->User->getPictures(\Auth::user()->id);
		}
		else{
			$user = $this->User->getPicturesByUsername($username);
		}

		return view('app.pictures')->with('user', $user);
	}

	/**
	 * Add pictures.
	 *
	 * @return Response
	 */
	public function addPictures(Request $request)
	{
		$validator = $this->Picture->pictureValidator($request->file('pictures'));

		if ($validator->fails())
		{
			$this->throwValidationException(
				$request, $validator
			);
		}


		if( ! $this->Picture->createPictrues($request->file('pictures'), \Auth::user()->id) )
		{
			return false;
		}

		return redirect()->back();
	}

	public function editPictures($id = null)
	{ 
		if(is_null($id)){
			return redirect()->back();
		}

		//redirect back if the picture does not exist
		if(! $picture = $this->Picture->getPictureById($id)){
			return redirect()->back();
		}

		//Check if the pictures belongs to the usere logged in
		if(\Auth::user()->id != $picture->user_id){
			return redirect()->back();
		}

		return view('app.edit_picture')->with('picture', $picture);
	}

	public function addTitlePictures(Request $request, $id)
	{
		$validator = $this->Picture->createTitlePictureValidator($request->get('title'));

		if ($validator->fails())
		{
			$this->throwValidationException(
				$request, $validator
			);
		}

		if( ! $this->Picture->createTitlePicture($request->get('title'), $id) )
		{
			return false;
		}

		return redirect('pictures');

	}

	public function deletePictures($id)
	{
		if(! $this->Picture->deletePicture($id))
		{
			return false;
		}
		return redirect('pictures');
	}

	public function addFollower()
	{
		$data['user_id']		= \Auth::user()->id;
		$data['follower_id']	= \Input::get('follower_id');

		if(!$this->User->addNewFollower($data))
		{
			return false;
		}

		$response = array( 'success' => 1);
		return \Response::json($response);

	}

	public function removeFollower()
	{
		$data['user_id']		= \Auth::user()->id;
		$data['follower_id']	= \Input::get('follower_id');

		if(!$this->User->removeFollower($data))
		{
			return false;
		}

		$response = array( 'success' => 1);
		return \Response::json($response);
	}

	public function getScrollingData()
	{
		$id = \Auth::user()->id;
		$shownData = \Input::get('displayedData');

		$dbData = $this->User->uploadCount($id);

		//check to see if all the elements are already displayed
		if((int)$shownData === (int)sizeof($dbData)){

			//if this is true, return empty array
			return \Response::json(array());
		}

		$data = $this->User->getFollowingPictures($id, $this->per_page, $shownData);

		return \Response::json($data);

	}


}

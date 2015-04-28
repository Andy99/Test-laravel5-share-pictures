<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Filesystem\Filesystem as File;
use Intervention\Image\ImageServiceProvider as Image;

use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\Registrar\RegistrarRepositoryInterface;

use Illuminate\Http\Request;
use Socialize;

class AuthController extends Controller {

	private $user;
	private $auth;
	private $registrar;

	public function __construct(
									UserRepositoryInterface $user, 
									Guard $auth,
									RegistrarRepositoryInterface $registrar
								)
	{
    	$this->user = $user;
    	$this->auth = $auth;
    	$this->registrar = $registrar;
    	$this->middleware('guest');

	}// End constructor


	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return view('auth.login'); 
	}

	/**
	 * Log in with facebook.
	 *
	 * @return Response
	 */
	public function loginWithFacebook()
	{
    	return Socialize::with('facebook')->redirect();
	}

	public function handleProviderCallback()
	{
    	$userData = Socialize::with('facebook')->user();

    	$user = $this->user->findByUserNameOrCreate($userData);

    	$this->auth->login($user, true);

    	return $this->userHasLoggedIn($user);
    }

    public function postRegister(Request $request)
    {


    	$avatar =  $request->file('avatar');

    	$validator = $this->registrar->validator($request->all());

		if ($validator->fails())
		{
			$this->throwValidationException(
				$request, $validator
			);
		}


		$user = $this->registrar->createOrUpdate($request->all());

		if( $avatar )// If an image has been created do upload

			
			$this->registrar->createAvatar($user->id, $avatar);

			$this->auth->login($user, true);

		return redirect('home');
	}

	private function userHasLoggedIn($user) {

    	return redirect('/home');
	}


	

}

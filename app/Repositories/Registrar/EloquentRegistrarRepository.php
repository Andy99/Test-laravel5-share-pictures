<?php namespace App\Repositories\Registrar;

use Validator;

use App\User as User;

class EloquentRegistrarRepository implements RegistrarRepositoryInterface {

    
    public function validator(array $data){

        return Validator::make($data, [
            'first_name'    => 'required|max:255',
            'last_name'     => 'required|max:255',
            'email'         => 'required|email|max:255|unique:users',
            'username'      => 'required|max:255|unique:users',
            'password'      => 'required|confirmed|min:6',
            'avatar'        => 'mimes:jpg,jpeg,png|max:5000',
        ]);
    }

    public function EditPasswordvalidator(array $data){

        return Validator::make($data, [
            'password'      => 'required|confirmed|min:6'
        ]);
    }

    public function createOrUpdate(array $data, $id = null)
    {
        if(is_null($id))
        {
            $user = new User;
            $user->first_name = $data['first_name'];
            $user->last_name  = $data['last_name'];
            $user->email      = $data['email'];
            $user->username   = $data['username'];
            $user->password   = bcrypt($data['password']);
            // Save new user
            $user->save();
            return $user;
        }
        else
        {
            $user = User::findOrFail($id);
            $user->first_name = $data['first_name'];
            $user->last_name  = $data['last_name'];
            // Update user
            $user->save();
            return $user;
        }
    }

    public function createAvatar($id, $data)
    {
        $uploads = public_path() . '/uploads';
        $path = public_path() . '/uploads/avatar';
        $thumbs_path = public_path() . '/uploads/avatar/thumbs';

        if(! \File::exists($uploads)) // Create public paths if not exist
        {
            \File::makeDirectory($uploads);
            \File::makeDirectory($path);
            \File::makeDirectory($thumbs_path);
        }

        $extension = $data->getClientOriginalExtension();
        $filename = uniqid() . '.' . $extension;

        //Upload the file
        $origin = $data->move($path, $filename);
        \Image::configure(array('driver' => 'gd'));

        $thumb = \Image::make($path . '/' . $filename)->resize(40, 40)->save($thumbs_path . '/' . $filename );

        $user = User::find($id);
        
        if(! $user->update(['avatar' => $filename]))// Update user's row with avatar file name
        {
           return false; 
        }

        return true;
    }
}
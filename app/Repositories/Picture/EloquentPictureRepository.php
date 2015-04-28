<?php namespace App\Repositories\Picture;

use Illuminate\Contracts\Filesystem\Filesystem as File;
use Intervention\Image\ImageServiceProvider as Image;
use App\User as User;
use App\Picture as Picture;
use Validator;

class EloquentPictureRepository implements PictureRepositoryInterface {

	public function pictureValidator(array $data)
    {
        foreach ($data as $image) {
            return Validator::make(['picture' => $image], [
                'picture'        => 'required|mimes:jpg,jpeg,png|max:5000'
            ]);
        }

    }

    public function createPictrues(array $data, $id)
    {
        $Picture = new Picture();
        $path = public_path() . '/uploads/';
        $thumb_path = public_path() . '/uploads/thumbs';

        if(! \File::exists($path))
        {
            \File::makeDirectory($path);
        }

        if( ! \File::exists($thumb_path))
        {
            \File::makeDirectory($thumb_path); 
        }
               

        foreach ($data as $image) {

            $extension = $image->getClientOriginalExtension();
            $filename = uniqid() . '.' . $extension;
            
            //Upload the file
            $origin = $image->move($path, $filename);
            \Image::configure(array('driver' => 'gd'));
            //Create Thumb
            $thumb = \Image::make($path . '/' . $filename)->resize(206, 206)->save($thumb_path . '/' . $filename );

            $Picture->create([
                'user_id'    => $id,
                'file_name'     => $filename,
            ]);

        }
        return true;
    }

    public function getPictureById($id)
    {
    	if(! $picture = Picture::find($id))
        {
            return false;
        }

        return $picture;
    }

    public function createTitlePictureValidator($data)
    {
        return Validator::make(['title' => $data], [
            'title'    => 'required|max:300',
        ]);
    }

    public function createTitlePicture($data, $id)
    {
        $picture = $this->getPictureById($id);
        $picture->title = $data;
        $picture->save();
        
        return true;
    }

    public function deletePicture($id)
    {
        $picture = $this->getPictureById($id);
        $picture->delete();

        if(! \File::delete('uploads/'.$picture->file_name) || ! \File::delete('uploads/thumbs/'. $picture->file_name))
        {
           return false;  
        }
        return true;
    }

}





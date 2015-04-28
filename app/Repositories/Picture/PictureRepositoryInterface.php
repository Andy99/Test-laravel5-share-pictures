<?php namespace App\Repositories\Picture;
 
interface PictureRepositoryInterface {

	public function pictureValidator(array $data);
	public function createPictrues(array $data, $id);
	public function getPictureById($id);
	public function createTitlePictureValidator($data);
	public function createTitlePicture($data, $id);
	public function deletePicture($id);

}
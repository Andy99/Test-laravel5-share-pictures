<?php namespace App\Repositories\Registrar;
 
interface RegistrarRepositoryInterface {

	public function validator(array $data);
	public function createAvatar($id, $data);
	public function createOrUpdate(array $data, $id = null);
	public function EditPasswordvalidator(array $data);

}
<?php

namespace App\Models\Entity;

use Selective\ArrayReader\ArrayReader;

class UserEntity{
	public ?int    $id;
	public ?string $name;
	public ?string $email;
	public ?string $password;
	public ?string $currentState;

	public function __construct(?array $array){
		if(isset($array)){
			$data = new ArrayReader($array);

			$this->id           = $data->findInt("id", null);
			$this->name         = $data->findString("name", null);
			$this->email        = $data->findString("email", null);
			$this->password     = $data->findString("password", null);
			$this->currentState = $data->findString("currentState", null);
		}
	}
}

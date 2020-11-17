<?php

namespace App\Models;

use App\Models\Entity\UserEntity;
use Cake\Database\Exception;
use PDOException;
use System\Models\EntityModel;

class UserModel extends EntityModel{
	private const TABLE = "users";

	public function addUser(UserEntity $user): array{
		$ret = [
			"code"        => -1,
			"description" => "successfully created",
		];

		try{


			$query = $this->queryFactory->newInsert(self::TABLE, (array) $user)
			                            ->execute();

			$ins = $query->lastInsertId();

			if($ins > 0 || is_integer($ins)){
				$ret["code"]        = 0;
				$ret["description"] = "successfully created" . $ins;
			}
			else{
				$ret["code"]        = -1;
				$ret["description"] = "failed the creation: " . $query->errorCode();
			}
			var_dump($query->errorInfo());
		}
		catch(Exception|PDOException $e){
			$ret["code"]        = -1;
			$ret["description"] = "failed the creation: " . $e->getMessage();
		}

		return $ret;
	}
}

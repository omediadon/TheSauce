<?php
/******************************************************************************
 * Copyright (C) Omedia.top - All Rights Reserved 2020                        *
 *                                                                            *
 * Unauthorized copying of this file, via any medium is strictly prohibited   *
 * Proprietary and confidential                                               *
 *                                                                            *
 * Written by Omar SAKHRAOUI <webmaster@omedia.top>, 8/2020                   *
 ******************************************************************************/

namespace App\Models\Entity;

use Selective\ArrayReader\ArrayReader;
use System\Models\Entity;

class TopicEntity extends Entity{
	public ?int    $id;
	public ?string $category;
	public ?int    $categoryId;
	public ?string $type;
	public ?string $slug;
	public ?string $title;
	public ?string $description;
	public ?string $text;
	public ?string $image;
	public ?string $color;
	public ?string $date_created;


	public function __construct(?array $array){
		if(isset($array)){
			$data = new ArrayReader($array);

			$this->id           = $data->findInt("id", null);
			$this->category     = $data->findString("category", null);
			$this->categoryId   = $data->findInt("t_category_id", null);
			$this->type         = $data->findString("type", null);
			$this->title        = $data->findString("title", null);
			$this->slug         = $data->findString("slug", null);
			$this->description  = $data->findString("description", null);
			$this->text         = $data->findString("text", null);
			$this->image        = $data->findString("image", null);
			$this->color        = $data->findString("color", null);
			$this->date_created = date('d M Y H:i', $data->findString("date_created", "0"));
		}
	}

}

<?php

namespace App\Models\Entity;

use Selective\ArrayReader\ArrayReader;
use System\Models\Entity;

class CategoryEntity extends Entity{
	public ?int    $id;
	public ?string $type;
	public ?string $slug;
	public ?string $title;
	public ?string $description;
	public ?string $image;
	public ?string $color;
	public ?string $date_created;
	public ?int    $topics_count;


	public function __construct(?array $array){
		if(isset($array)){
			$data = new ArrayReader($array);

			$this->id           = $data->findInt("id", null);
			$this->type         = $data->findString("type", null);
			$this->title        = $data->findString("title", null);
			$this->slug         = $data->findString("slug", null);
			$this->description  = $data->findString("description", null);
			$this->image        = $data->findString("image", null);
			$this->color        = $data->findString("color", null);
			$this->topics_count  = $data->findString("topics_count", null);
			$this->date_created = date('d M Y H:i', $data->findString("date_created", "0"));
		}
	}
}

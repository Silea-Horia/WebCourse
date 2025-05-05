<?php

class Product implements JsonSerializable {
	private $id;
	private $name;
	private $categoryId;

	public function __construct($id, $name, $categoryId) {
		$this->id = $id;
		$this->name = $name;
		$this->categoryId = $categoryId;
	}

	public function getId() {
		return $this->id;
	}
	public function getName() {
		return $this->name;
	}
	public function getCategoryId() {
		return $this->categoryId;
	}

	public function jsonSerialize() {
        $vars = get_object_vars($this);
        return $vars;
    }
}

?>

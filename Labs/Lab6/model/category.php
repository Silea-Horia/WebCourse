<?php

class Category implements JsonSerializable {
	private $id;
	private $name;

	public function __construct($id, $name) {
		$this->id = $id;
		$this->name = $name;
	}

	public function getId() {
		return $this->id;
	}
	
	public function getName() {
		return $this->name;
	}

	public function jsonSerialize() {
        $vars = get_object_vars($this);
        return $vars;
    }
}

?>

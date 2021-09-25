<?php

final class JsonAPI {

	private $method;
	private $loc;
	private $input;
	private $response;

	public function __construct($method, $loc, $input) {

		$this->method = $method; // post, get, put, patch, delete

		// POST (CREATE)
		// GET (READ)
		// PUT (REPLACE)
		// PATCH (MODIFY)
		// DELETE (DELETE)

		$this->loc = $loc;
		$this->input = $input;
		$this->response = '{}';

	}


	private function post() {

	}

	private function get() {

	}

	private function put() {

	}

	private function patch() {

	}

	private function delete() {

	}

	public function response() {

		return $this->response;

	}


}

?>
<?php

//! Front-end processor
class Types_of_regular_transportation extends Controller {

	//! GET all method
	function GetTypes($f3) {
		$db = $this->db;

		$res = $db->exec('select * from directory.types_of_regular_transportation ORDER BY id');

		header('Content-Type: application/json');
		$f3->status(200);

		echo json_encode($res, JSON_UNESCAPED_UNICODE);
	}

	//! GET by id
	function GetType($f3) {
		$db = $this->db;

		$res = $db->exec('select * from directory.types_of_regular_transportation WHERE id = ?', $f3->get('PARAMS.id'));
		
		if($res == null)
		{
			$f3->error(404);
		}

		header('Content-Type: application/json');
		$f3->status(200);

		echo json_encode($res[0], JSON_UNESCAPED_UNICODE);
	}

	//! POST data
	function AddType($f3) {

		$db = $this->db;

		$body = json_decode($f3->get('BODY'), TRUE);
		if($body == null)
		{
			$f3->status(500);
			exit;
		}

		$type = new DB\SQL\Mapper($db,'directory.types_of_regular_transportation');

		$type->name = $body['name'];
		$type->accounting_start_date = $body['accounting_start_date'];
		$type->accounting_end_date = $body['accounting_end_date'];
		$type->save();
		$f3->status(204);
	}

	//! PUT data
	function UpdateType($f3) {
		$db = $this->db;

		$body = json_decode($f3->get('BODY'), TRUE);
		if($body == null)
		{
			$f3->status(500);
			exit;
		}

		$type = new DB\SQL\Mapper($db,'directory.types_of_regular_transportation');
		$type->load(array('@id=?',$f3->get('PARAMS.id')));
		if($type->dry())
		{
			$f3->error(404);
		}

		if($body['name'] != null){
			$type->name = $body['name'];
		}
		if($body['accounting_start_date'] != null){
			$type->accounting_start_date = $body['accounting_start_date'];
		}
		if($body['accounting_end_date'] != null){
			$type->accounting_end_date = $body['accounting_end_date'];
		}
		$type->save();
		$f3->status(204);

	}

	//! DELETE method
	function DeleteType($f3) {

		$db = $this->db;

		$type = new DB\SQL\Mapper($db,'directory.types_of_regular_transportation');
		$type->load(array('@id=?',$f3->get('PARAMS.id')));
		if($type->dry())
		{
			$f3->error(404);
		}
		$type->erase();
		$f3->status(204);
	}

}

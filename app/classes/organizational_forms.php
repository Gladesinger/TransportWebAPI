<?php

//! Front-end processor
class Organizational_forms extends Controller {

	//! GET all method
	function GetAll($f3) {
		$db = $this->db;

		$res = $db->exec('select * from directory.organizational_legal_forms ORDER BY id');

		header('Content-Type: application/json');
		$f3->status(200);

		echo json_encode($res, JSON_UNESCAPED_UNICODE);
	}

	//! GET by id
	function GetOne($f3) {
		$db = $this->db;

		$res = $db->exec('select * from directory.organizational_legal_forms WHERE id = ?', $f3->get('PARAMS.id'));
		
		if($res == null)
		{
			$f3->error(404);
		}

		header('Content-Type: application/json');
		$f3->status(200);

		echo json_encode($res[0], JSON_UNESCAPED_UNICODE);
	}

	//! POST data
	function Add($f3) {

		$db = $this->db;

		$body = json_decode($f3->get('BODY'), TRUE);
		if($body == null)
		{
			$f3->status(500);
			exit;
		}

		$object = new DB\SQL\Mapper($db,'directory.organizational_legal_forms');

		$object->name = $body['name'];
		$object->short_name = $body['short_name'];
		$object->accounting_start_date = $body['accounting_start_date'];
		$object->accounting_end_date = $body['accounting_end_date'];
		$object->save();
		$f3->status(204);
	}

	//! PUT data
	function Update($f3) {
		$db = $this->db;

		$body = json_decode($f3->get('BODY'), TRUE);
		if($body == null)
		{
			$f3->status(500);
			exit;
		}

		$object = new DB\SQL\Mapper($db,'directory.organizational_legal_forms');
		$object->load(array('@id=?',$f3->get('PARAMS.id')));
		if($object->dry())
		{
			$f3->error(404);
		}

		if($body['name'] != null){
			$object->name = $body['name'];
		}
		if($body['short_name'] != null){
			$object->short_name = $body['short_name'];
		}
		if($body['accounting_start_date'] != null){
			$object->accounting_start_date = $body['accounting_start_date'];
		}
		if($body['accounting_end_date'] != null){
			$object->accounting_end_date = $body['accounting_end_date'];
		}
		$object->save();
		$f3->status(204);

	}

	//! DELETE method
	function Delete($f3) {

		$db = $this->db;

		$object = new DB\SQL\Mapper($db,'directory.organizational_legal_forms');
		$object->load(array('@id=?',$f3->get('PARAMS.id')));
		if($object->dry())
		{
			$f3->error(404);
		}
		$object->erase();
		$f3->status(204);
	}

}

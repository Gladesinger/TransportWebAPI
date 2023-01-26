<?php

//! Front-end processor
class Route_types extends Controller {

	//! GET all method
	function GetAll($f3) {
		$db = $this->db;

		$res = $db->exec('select * from route.route_types ORDER BY id');

		header('Content-Type: application/json');
		$f3->status(200);

		echo json_encode($res, JSON_UNESCAPED_UNICODE);
	}

	//! GET by id
	function GetOne($f3) {
		$db = $this->db;

		$res = $db->exec('select * from route.route_types WHERE id = ?', $f3->get('PARAMS.id'));
		
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

		$object = new DB\SQL\Mapper($db,'route.route_types');

		$object->code = $body['code'];
		$object->name = $body['name'];
		$object->transplant_time = $body['transplant_time'];
		$object->remark = $body['remark'];
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

		$object = new DB\SQL\Mapper($db,'route.route_types');
		$object->load(array('@id=?',$f3->get('PARAMS.id')));
		if($object->dry())
		{
			$f3->error(404);
		}

		if(!is_null($body['code'])){
			$object->code = $body['code'];
		}
		if(!is_null($body['name'])){
			$object->name = $body['name'];
		}
		if(!is_null($body['transplant_time'])){
			$object->transplant_time = $body['transplant_time'];
		}
		if(!is_null($body['remark'])){
			$object->remark = $body['remark'];
		}
		if(!is_null($body['accounting_start_date'])){
			$object->accounting_start_date = $body['accounting_start_date'];
		}
		if(!is_null($body['accounting_end_date'])){
			$object->accounting_end_date = $body['accounting_end_date'];
		}
		$object->save();
		$f3->status(204);

	}

	//! DELETE method
	function Delete($f3) {

		$db = $this->db;

		$object = new DB\SQL\Mapper($db,'route.route_types');
		$object->load(array('@id=?',$f3->get('PARAMS.id')));
		if($object->dry())
		{
			$f3->error(404);
		}
		$object->erase();
		$f3->status(204);
	}

}

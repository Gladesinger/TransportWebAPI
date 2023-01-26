<?php

//! Front-end processor
class Transport_types extends Controller {

	//! GET all method
	function GetAll($f3) {
		$db = $this->db;

		$res = $db->exec('select * from common.transport_types ORDER BY id');

		header('Content-Type: application/json');
		$f3->status(200);

		echo json_encode($res, JSON_UNESCAPED_UNICODE);
	}

	//! GET by id
	function GetOne($f3) {
		$db = $this->db;

		$res = $db->exec('select * from common.transport_types WHERE id = ?', $f3->get('PARAMS.id'));
		
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

		$object = new DB\SQL\Mapper($db,'common.transport_types');

		$object->name = $body['name'];
		$object->short_name = $body['short_name'];
		$object->max_speed = $body['max_speed'];
		$object->is_public_transport = $body['is_public_transport'];
		$object->icon = $body['icon'];
		$object->color = $body['color'];
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

		$object = new DB\SQL\Mapper($db,'common.transport_types');
		$object->load(array('@id=?',$f3->get('PARAMS.id')));
		if($object->dry())
		{
			$f3->error(404);
		}

		if(!is_null($body['name'])){
			$object->name = $body['name'];
		}
		if(!is_null($body['short_name'])){
			$object->short_name = $body['short_name'];
		}
		if(!is_null($body['max_speed'])){
			$object->max_speed = $body['max_speed'];
		}
		if(!is_null($body['is_public_transport'])){
			$object->is_public_transport = $body['is_public_transport'];
		}
		if(!is_null($body['icon'])){
			$object->icon = $body['icon'];
		}
		if(!is_null($body['color'])){
			$object->color = $body['color'];
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

		$object = new DB\SQL\Mapper($db,'common.transport_types');
		$object->load(array('@id=?',$f3->get('PARAMS.id')));
		if($object->dry())
		{
			$f3->error(404);
		}
		$object->erase();
		$f3->status(204);
	}

}

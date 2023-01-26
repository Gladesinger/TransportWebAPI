<?php

//! Front-end processor
class Vehicle_lock extends Controller {

	//! GET all method
	function GetAll($f3) {
		$db = $this->db;

		$res = $db->exec('select * from directory.vehicle_lock ORDER BY id');

		foreach($res as &$value)
		{
			$res2 = $db->exec('select * from common.vehicles WHERE id = ?', $value['vehicle_id']);
			$value['vehicle'] = $res2[0];
		}

		header('Content-Type: application/json');
		$f3->status(200);

		echo json_encode($res, JSON_UNESCAPED_UNICODE);
	}

	//! GET by id
	function GetOne($f3) {
		$db = $this->db;

		$res = $db->exec('select * from directory.vehicle_lock WHERE id = ?', $f3->get('PARAMS.id'));

		$res2 = $db->exec('select * from common.vehicles WHERE id = ?', $res[0]['vehicle_id']);
		$res[0]['vehicle'] = $res2[0];
		
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

		$object = new DB\SQL\Mapper($db,'directory.vehicle_lock');

		$object->vehicle_id = $body['vehicle_id'];
		$object->action = $body['action'];
		$object->date_of_action = $body['date_of_action'];
		$object->remark = $body['remark'];
		
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

		$object = new DB\SQL\Mapper($db,'directory.vehicle_lock');
		$object->load(array('@id=?',$f3->get('PARAMS.id')));
		if($object->dry())
		{
			$f3->error(404);
		}

		if($body['vehicle_id'] != null){
			$object->vehicle_id = $body['vehicle_id'];
		}
		if($body['action'] != null){
			$object->action = $body['action'];
		}
		if($body['date_of_action'] != null){
			$object->date_of_action = $body['date_of_action'];
		}
		if($body['remark'] != null){
			$object->remark = $body['remark'];
		}
		
		
		$object->save();
		$f3->status(204);

	}

	//! DELETE method
	function Delete($f3) {

		$db = $this->db;

		$object = new DB\SQL\Mapper($db,'directory.vehicle_lock');
		$object->load(array('@id=?',$f3->get('PARAMS.id')));
		if($object->dry())
		{
			$f3->error(404);
		}
		$object->erase();
		$f3->status(204);
	}

}

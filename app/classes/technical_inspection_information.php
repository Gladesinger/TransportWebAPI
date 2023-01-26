<?php

//! Front-end processor
class Technical_inspection_information extends Controller {

	//! GET all method
	function GetAll($f3) {
		$db = $this->db;

		$res = $db->exec('select * from directory.technical_inspection_information ORDER BY id');

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

		$res = $db->exec('select * from directory.technical_inspection_information WHERE id = ?', $f3->get('PARAMS.id'));

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

		$object = new DB\SQL\Mapper($db,'directory.technical_inspection_information');

		$object->vehicle_id = $body['vehicle_id'];
		$object->inspection_date = $body['inspection_date'];
		$object->details = $body['details'];
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

		$object = new DB\SQL\Mapper($db,'directory.technical_inspection_information');
		$object->load(array('@id=?',$f3->get('PARAMS.id')));
		if($object->dry())
		{
			$f3->error(404);
		}

		if($body['vehicle_id'] != null){
			$object->vehicle_id = $body['vehicle_id'];
		}
		if($body['inspection_date'] != null){
			$object->inspection_date = $body['inspection_date'];
		}
		if($body['details'] != null){
			$object->details = $body['details'];
		}
		$object->save();
		$f3->status(204);

	}

	//! DELETE method
	function Delete($f3) {

		$db = $this->db;

		$object = new DB\SQL\Mapper($db,'directory.technical_inspection_information');
		$object->load(array('@id=?',$f3->get('PARAMS.id')));
		if($object->dry())
		{
			$f3->error(404);
		}
		$object->erase();
		$f3->status(204);
	}

}

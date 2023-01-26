<?php

//! Front-end processor
class Cities extends Controller {

	//! GET all method
	function GetAll($f3) {
		$db = $this->db;

		$res = $db->exec('select * from directory.cities ORDER BY id');

		header('Content-Type: application/json');
		$f3->status(200);

		echo json_encode($res, JSON_UNESCAPED_UNICODE);
	}

	//! GET by id
	function GetOne($f3) {
		$db = $this->db;

		$res = $db->exec('select * from directory.cities WHERE id = ?', $f3->get('PARAMS.id'));
		
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

		$status = new DB\SQL\Mapper($db,'directory.cities');

		$status->town = $body['town'];
		$status->district = $body['district'];
		$status->region = $body['region'];
		$status->save();
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

		$status = new DB\SQL\Mapper($db,'directory.cities');
		$status->load(array('@id=?',$f3->get('PARAMS.id')));
		if($status->dry())
		{
			$f3->error(404);
		}

		if($body['town'] != null){
			$status->town = $body['town'];
		}
		if($body['district'] != null){
			$status->district = $body['district'];
		}
		if($body['region'] != null){
			$status->region = $body['region'];
		}
		$status->save();
		$f3->status(204);

	}

	//! DELETE method
	function Delete($f3) {

		$db = $this->db;

		$status = new DB\SQL\Mapper($db,'directory.cities');
		$status->load(array('@id=?',$f3->get('PARAMS.id')));
		if($status->dry())
		{
			$f3->error(404);
		}
		$status->erase();
		$f3->status(204);
	}

}

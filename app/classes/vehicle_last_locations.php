<?php

//! Front-end processor
class Vehicle_last_locations extends Controller {

	//! GET all method
	function GetAll($f3) {
		$db = $this->db;

		$res = $db->exec('select * from history.last_vehicle_locations ORDER BY id');


		foreach($res as &$value)
		{
			if(!is_null($value['vehicle_id']))
			{
				$res2 = $db->exec('select * from common.vehicles WHERE id = ?', $value['vehicle_id']);
				$value['vehicle'] = $res2[0];
			}
		}

		header('Content-Type: application/json');
		$f3->status(200);

		echo json_encode($res, JSON_UNESCAPED_UNICODE);
	}

	//! GET by id
	function GetOne($f3) {
		$db = $this->db;

		$res = $db->exec('select * from history.last_vehicle_locations WHERE id = ?', $f3->get('PARAMS.id'));

		if(!is_null($res[0]['vehicle_id']))
		{
			$res2 = $db->exec('select * from common.vehicles WHERE id = ?', $res[0]['vehicle_id']);
			$res[0]['vehicle'] = $res2[0];
		}
		
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

		$object = new DB\SQL\Mapper($db,'history.last_vehicle_locations');

		$object->vehicle_id = $body['vehicle_id'];
		$object->latitude = $body['latitude'];
		$object->longitude = $body['longitude'];
		$object->direction = $body['direction'];
		$object->speed = $body['speed'];
		$object->altitude = $body['altitude'];
		$object->measuring_time = $body['measuring_time'];
		$object->receiving_time = $body['receiving_time'];
		$object->ignition = $body['ignition'];
		$object->alarm = $body['alarm'];
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

		$object = new DB\SQL\Mapper($db,'history.last_vehicle_locations');
		$object->load(array('@id=?',$f3->get('PARAMS.id')));
		if($object->dry())
		{
			$f3->error(404);
		}

		if($body['vehicle_id'] != null){
			$object->vehicle_id = $body['vehicle_id'];
		}
		if($body['latitude'] != null){
			$object->latitude = $body['latitude'];
		}
		if($body['longitude'] != null){
			$object->longitude = $body['longitude'];
		}
		if($body['direction'] != null){
			$object->direction = $body['direction'];
		}
		if($body['speed'] != null){
			$object->speed = $body['speed'];
		}
		if($body['altitude'] != null){
			$object->altitude = $body['altitude'];
		}
		if($body['measuring_time'] != null){
			$object->measuring_time = $body['measuring_time'];
		}
		if($body['receiving_time'] != null){
			$object->receiving_time = $body['receiving_time'];
		}
		if($body['ignition'] != null){
			$object->ignition = $body['ignition'];
		}
		if($body['alarm'] != null){
			$object->alarm = $body['alarm'];
		}
		$object->save();
		$f3->status(204);

	}

	//! DELETE method
	function Delete($f3) {

		$db = $this->db;

		$object = new DB\SQL\Mapper($db,'history.last_vehicle_locations');
		$object->load(array('@id=?',$f3->get('PARAMS.id')));
		if($object->dry())
		{
			$f3->error(404);
		}
		$object->erase();
		$f3->status(204);
	}

}

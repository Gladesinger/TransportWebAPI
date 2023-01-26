<?php

//! Front-end processor
class Vehicle_devices extends Controller {

	//! GET all method
	function GetAll($f3) {
		$db = $this->db;

		$res = $db->exec('select *  from common.vehicle_devices ORDER BY vehicle_devices.id');
		
		foreach($res as &$value)
		{
			$res2 = $db->exec('select * from common.vehicles WHERE id = ?', $value['vehicle_id']);
			$value['vehicle'] = $res2[0];
		}

		foreach($res as &$value)
		{
			$res3 = $db->exec('select * from common.navigation_devices LEFT JOIN common.device_types on common.navigation_devices.device_type_id = common.device_types.id WHERE common.navigation_devices.id = ?', $value['navigation_device_id']);
			$value['navigation_device'] = $res3[0];
		}

		header('Content-Type: application/json');
		$f3->status(200);

		echo json_encode($res, JSON_UNESCAPED_UNICODE);
	}

	//! GET by id
	function GetOne($f3) {
		$db = $this->db;

		$res = $db->exec('select * from common.vehicle_devices WHERE vehicle_devices.id = ? ORDER BY vehicle_devices.id', $f3->get('PARAMS.id'));
		
		$res2 = $db->exec('select * from common.vehicles WHERE id = ?', $res[0]['vehicle_id']);
		$res[0]['vehicle'] = $res2[0];
		
		$res3 = $db->exec('select * from common.navigation_devices LEFT JOIN common.device_types on common.navigation_devices.device_type_id = common.device_types.id WHERE common.navigation_devices.id = ?', $res[0]['navigation_device_id']);
		$res[0]['navigation_device'] = $res3[0];
			
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

		$object = new DB\SQL\Mapper($db,'common.vehicle_devices');


		$object->vehicle_id = $body['vehicle_id'];
		$object->navigation_device_id = $body['navigation_device_id'];
		$object->active = $body['active'];
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

		$object = new DB\SQL\Mapper($db,'common.vehicle_devices');
		$object->load(array('@id=?',$f3->get('PARAMS.id')));
		if($object->dry())
		{
			$f3->error(404);
		}

		if($body['vehicle_id'] != null){
			$object->vehicle_id = $body['vehicle_id'];
		}
		if($body['navigation_device_id'] != null){
			$object->navigation_device_id = $body['navigation_device_id'];
		}
		if($body['active'] != null){
			$object->active = $body['active'];
		}
		
		$object->save();
		$f3->status(204);

	}

	//! DELETE method
	function Delete($f3) {

		$db = $this->db;

		$object = new DB\SQL\Mapper($db,'common.vehicle_devices');
		$object->load(array('@id=?',$f3->get('PARAMS.id')));
		if($object->dry())
		{
			$f3->error(404);
		}
		$object->erase();
		$f3->status(204);
	}

}





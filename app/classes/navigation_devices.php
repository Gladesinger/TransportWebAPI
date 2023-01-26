<?php

//! Front-end processor
class Navigation_devices extends Controller {

	//! GET all method
	function GetAll($f3) {
		$db = $this->db;

		$res = $db->exec('select *  from common.navigation_devices ORDER BY navigation_devices.id');
		
		foreach($res as &$value)
		{
			$res2 = $db->exec('select * from common.device_types WHERE id = ?', $value['device_type_id']);
			$value['device_type'] = $res2[0];
		}

		foreach($res as &$value)
		{
			$res3 = $db->exec('select * from common.organizations WHERE id = ?', $value['organization_id']);
			$value['organization'] = $res3[0];
		}

		header('Content-Type: application/json');
		$f3->status(200);

		echo json_encode($res, JSON_UNESCAPED_UNICODE);
	}

	//! GET by id
	function GetOne($f3) {
		$db = $this->db;

		$res = $db->exec('select * from common.navigation_devices WHERE id = ? ORDER BY id', $f3->get('PARAMS.id'));

		if($res == null)
		{
			$f3->error(404);
		}
		
		$res2 = $db->exec('select * from common.device_types WHERE id = ?', $res[0]['device_type_id']);
		$res[0]['device_type'] = $res2[0];
		
		$res3 = $db->exec('select * from common.organizations WHERE id = ?', $res[0]['organization_id']);
		$res[0]['organization'] = $res3[0];
			
		

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

		$object = new DB\SQL\Mapper($db,'common.navigation_devices');

		$object->device_type_id = $body['device_type_id'];
		$object->active = $body['active'];
		$object->firmware_version = $body['firmware_version'];
		$object->serial_number = $body['serial_number'];
		$object->sim_number = $body['sim_number'];
		$object->brand = $body['brand'];
		$object->model = $body['model'];
		$object->accounting_start_date = $body['accounting_start_date'];
		$object->accounting_end_date = $body['accounting_end_date'];
		$object->organization_id = $body['organization_id'];
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

		$object = new DB\SQL\Mapper($db,'common.navigation_devices');
		$object->load(array('@id=?',$f3->get('PARAMS.id')));
		if($object->dry())
		{
			$f3->error(404);
		}

		if($body['device_type_id'] != null){
			$object->device_type_id = $body['device_type_id'];
		}
		if($body['active'] != null){
			$object->active = $body['active'];
		}
		if($body['firmware_version'] != null){
			$object->firmware_version = $body['firmware_version'];
		}
		if($body['serial_number'] != null){
			$object->serial_number = $body['serial_number'];
		}
		if($body['sim_number'] != null){
			$object->sim_number = $body['sim_number'];
		} 
		if($body['brand'] != null){
			$object->brand = $body['brand'];
		}
		if($body['model'] != null){
			$object->model = $body['model'];
		}
		if($body['accounting_start_date'] != null){
			$object->accounting_start_date = $body['accounting_start_date'];
		}
		if($body['accounting_end_date'] != null){
			$object->accounting_end_date = $body['accounting_end_date'];
		}
		if($body['organization_id'] != null){
			$object->organization_id = $body['organization_id'];
		}
		$object->save();
		$f3->status(204);

	}

	//! DELETE method
	function Delete($f3) {

		$db = $this->db;

		$object = new DB\SQL\Mapper($db,'common.navigation_devices');
		$object->load(array('@id=?',$f3->get('PARAMS.id')));
		if($object->dry())
		{
			$f3->error(404);
		}
		$object->erase();
		$f3->status(204);
	}

}

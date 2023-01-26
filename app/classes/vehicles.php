<?php

//! Front-end processor
class Vehicles extends Controller {

	//! GET all method
	function GetAll($f3) {
		$db = $this->db;

		$res = $db->exec('SELECT v.*, dt.name as device_type, nd.serial_number as device_id 
				   	FROM common.vehicles as v 
				   	LEFT JOIN common.vehicle_devices as vd 
				   	ON vd.vehicle_id = v.id 
				   	LEFT JOIN common.navigation_devices as nd 
				   	ON vd.navigation_device_id = nd.id 
				   	LEFT JOIN common.device_types as dt 
				   	ON dt.id = nd.device_type_id');
		foreach($res as &$value)
		{
			if(!is_null($value['state_number_type_id']))
			{
				$res2 = $db->exec('select * from directory.registration_masks_of_vehicle_signs WHERE id = ?', $value['state_number_type_id']);
				$value['state_number_type'] = $res2[0];
			}
		}
		foreach($res as &$value)
		{
			if(!is_null($value['vehicle_class_id']))
			{
				$res3 = $db->exec('select * from directory.vehicle_class WHERE id = ?', $value['vehicle_class_id']);
				$value['vehicle_class'] = $res3[0];
			}
		}
		foreach($res as &$value)
		{
			if(!is_null($value['vehicle_model_id']))
			{
				$res4 = $db->exec("select m.*, br.name as brand_name, br.consumption_rate, cat.name as category_name, cat.description as category_description, typ.name as type_name, typ.is_public_transport, len.name as length_class_name, len.description as length_class_description, cap.name as capacity_class_name, cap.description as capacity_class_description, eco.name as ecological_class_name   
FROM directory.vehicle_models m 
LEFT JOIN directory.vehicle_brand br on m.vehicle_brand_id = br.id
LEFT JOIN directory.vehicle_categories cat on m.vehicle_category_id = cat.id
LEFT JOIN common.transport_types typ on m.vehicle_type_id = typ.id
LEFT JOIN directory.vehicle_length_class len on m.vehicle_length_class_id = len.id
LEFT JOIN directory.vehicle_capacity_class cap on m.vehicle_capacity_class_id = cap.id
LEFT JOIN directory.vehicle_ecological_class eco on m.vehicle_ecological_class_id = eco.id
 WHERE id = ?", $value['vehicle_model_id']);
				$value['vehicle_model'] = $res4[0];
			}
		}
		foreach($res as &$value)
		{
			if(!is_null($value['organization_id']))
			{
				$res5 = $db->exec('select * from common.organizations WHERE id = ?', $value['organization_id']);
				$value['organization'] = $res5[0];
			}
		}
		header('Content-Type: application/json');
		$f3->status(200);

		echo json_encode($res, JSON_UNESCAPED_UNICODE);
	}

	//! GET by id
	function GetOne($f3) {
		$db = $this->db;

		$res = $db->exec('SELECT v.*, dt.name as device_type, nd.serial_number as device_id 
				   	FROM common.vehicles as v 
				   	LEFT JOIN common.vehicle_devices as vd 
				   	ON vd.vehicle_id = v.id 
				   	LEFT JOIN common.navigation_devices as nd 
				   	ON vd.navigation_device_id = nd.id 
				   	LEFT JOIN common.device_types as dt 
				   	ON dt.id = nd.device_type_id 
					WHERE v.id = ?', $f3->get('PARAMS.id'));
		if(!is_null($res[0]['state_number_type_id']))
		{
			$res2 = $db->exec('select * from directory.registration_masks_of_vehicle_signs WHERE id = ?', $res[0]['state_number_type_id']);
			$res[0]['state_number_type'] = $res2[0];
		}
		
		if(!is_null($res[0]['vehicle_class_id']))
		{
		$res3 = $db->exec('select * from directory.vehicle_class WHERE id = ?', $res[0]['vehicle_class_id']);
		$res[0]['vehicle_class'] = $res3[0];
		}
		
		if(!is_null($res[0]['vehicle_model_id']))
		{
			$res4 = $db->exec("select m.*, br.name as brand_name, br.consumption_rate, cat.name as category_name, cat.description as category_description, typ.name as type_name, typ.is_public_transport, len.name as length_class_name, len.description as length_class_description, cap.name as capacity_class_name, cap.description as capacity_class_description, eco.name as ecological_class_name   
	FROM directory.vehicle_models m 
	LEFT JOIN directory.vehicle_brand br on m.vehicle_brand_id = br.id
	LEFT JOIN directory.vehicle_categories cat on m.vehicle_category_id = cat.id
	LEFT JOIN common.transport_types typ on m.vehicle_type_id = typ.id
	LEFT JOIN directory.vehicle_length_class len on m.vehicle_length_class_id = len.id
	LEFT JOIN directory.vehicle_capacity_class cap on m.vehicle_capacity_class_id = cap.id
	LEFT JOIN directory.vehicle_ecological_class eco on m.vehicle_ecological_class_id = eco.id
	 WHERE id = ?", $res[0]['vehicle_model_id']);
			$res[0]['vehicle_model'] = $res4[0];
		}
		
		$res5 = $db->exec('select * from common.organizations as org LEFT JOIN common.geozones as geo ON geo.id = org.geozone_id  WHERE org.id = ?', $res[0]['organization_id']);
		$res[0]['organization'] = $res5[0];
		
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

		$object = new DB\SQL\Mapper($db,'common.vehicles');

		$object->state_number = $body['state_number'];
		$object->number = $body['number'];
		$object->active = $body['active'];
		$object->code = $body['code'];
		$object->vin = $body['vin'];
		$object->state_number_type_id = $body['state_number_type_id'];
		$object->year_of_issue = $body['year_of_issue'];
		$object->vehicle_class_id = $body['vehicle_class_id'];
		$object->vehicle_model_id = $body['vehicle_model_id'];
		$object->max_speed = $body['max_speed'];
		$object->note_for_the_card = $body['note_for_the_card'];
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

		$object = new DB\SQL\Mapper($db,'common.vehicles');
		$object->load(array('@id=?',$f3->get('PARAMS.id')));
		if($object->dry())
		{
			$f3->error(404);
		}

		if($body['state_number'] != null){
			$object->state_number = $body['state_number'];
		}
		if($body['number'] != null){
			$object->number = $body['number'];
		}
		if($body['active'] != null){
			$object->active = $body['active'];
		}
		if($body['code'] != null){
			$object->code = $body['code'];
		}
		if($body['vin'] != null){
			$object->vin = $body['vin'];
		}
		if($body['state_number_type_id'] != null){
			$object->state_number_type_id = $body['state_number_type_id'];
		}
		if($body['year_of_issue'] != null){
			$object->year_of_issue = $body['year_of_issue'];
		}
		if($body['vehicle_class_id'] != null){
			$object->vehicle_class_id = $body['vehicle_class_id'];
		}
		if($body['vehicle_model_id'] != null){
			$object->vehicle_model_id = $body['vehicle_model_id'];
		}
		if($body['max_speed'] != null){
			$object->max_speed = $body['max_speed'];
		}
		if($body['note_for_the_card'] != null){
			$object->note_for_the_card = $body['note_for_the_card'];
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

		$object = new DB\SQL\Mapper($db,'common.vehicles');
		$object->load(array('@id=?',$f3->get('PARAMS.id')));
		if($object->dry())
		{
			$f3->error(404);
		}
		$object->erase();
		$f3->status(204);
	}

}

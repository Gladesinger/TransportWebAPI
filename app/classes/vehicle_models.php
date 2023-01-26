<?php

//! Front-end processor
class Vehicle_models extends Controller {

	//! GET all method
	function GetAll($f3) {
		$db = $this->db;

		/*$res = $db->exec("select m.*, br.name as brand_name, br.consumption_rate, cat.name as category_name, cat.description as category_description, typ.name as type_name, typ.is_public_transport, len.name as length_class_name, len.description as length_class_description, cap.name as capacity_class_name, cap.description as capacity_class_description, eco.name as ecological_class_name   
FROM directory.vehicle_models m 
LEFT JOIN directory.vehicle_brand br on m.vehicle_brand_id = br.id
LEFT JOIN directory.vehicle_categories cat on m.vehicle_category_id = cat.id
LEFT JOIN common.transport_types typ on m.vehicle_type_id = typ.id
LEFT JOIN directory.vehicle_length_class len on m.vehicle_length_class_id = len.id
LEFT JOIN directory.vehicle_capacity_class cap on m.vehicle_capacity_class_id = cap.id
LEFT JOIN directory.vehicle_ecological_class eco on m.vehicle_ecological_class_id = eco.id");*/

		$res = $db->exec('SELECT * FROM directory.vehicle_models');

		foreach($res as &$value)
		{
			if(!is_null($value['vehicle_brand_id']))
			{
				$res2 = $db->exec('select * from directory.vehicle_brands WHERE id = ?', $value['vehicle_brand_id']);
				$value['vehicle_brand'] = $res2[0];
			}
		}
		foreach($res as &$value)
		{
			if(!is_null($value['vehicle_type_id']))
			{
				$res2 = $db->exec('select * from common.transport_types WHERE id = ?', $value['vehicle_type_id']);
				$value['vehicle_type'] = $res2[0];
			}
		}
		foreach($res as &$value)
		{
			if(!is_null($value['vehicle_category_id']))
			{
				$res2 = $db->exec('select * from directory.vehicle_categories WHERE id = ?', $value['vehicle_category_id']);
				$value['vehicle_category'] = $res2[0];
			}
		}
		foreach($res as &$value)
		{
			if(!is_null($value['vehicle_length_class_id']))
			{
				$res2 = $db->exec('select * from directory.vehicle_length_class WHERE id = ?', $value['vehicle_length_class_id']);
				$value['vehicle_length_class'] = $res2[0];
			}
		}
		foreach($res as &$value)
		{
			if(!is_null($value['vehicle_capacity_class_id']))
			{
				$res2 = $db->exec('select * from directory.vehicle_capacity_class WHERE id = ?', $value['vehicle_capacity_class_id']);
				$value['vehicle_capacity_class'] = $res2[0];
			}
		}
		foreach($res as &$value)
		{
			if(!is_null($value['vehicle_ecological_class_id']))
			{
				$res2 = $db->exec('select * from directory.vehicle_ecological_class WHERE id = ?', $value['vehicle_ecological_class_id']);
				$value['vehicle_ecological_class'] = $res2[0];
			}
		}

		header('Content-Type: application/json');
		$f3->status(200);

		echo json_encode($res, JSON_UNESCAPED_UNICODE);
	}

	//! GET by id
	function GetOne($f3) {
		$db = $this->db;

		$res = $db->exec('SELECT * FROM directory.vehicle_models WHERE id = ?', $f3->get('PARAMS.id'));

		if(!is_null($res[0]['vehicle_brand_id']))
		{
			$res2 = $db->exec('select * from directory.vehicle_brands WHERE id = ?', $res[0]['vehicle_brand_id']);
			$res[0]['vehicle_brand'] = $res2[0];
		}

		if(!is_null($res[0]['vehicle_type_id']))
		{
			$res3 = $db->exec('select * from common.transport_types WHERE id = ?', $res[0]['vehicle_type_id']);
			$res[0]['vehicle_type'] = $res3[0];
		}


		if(!is_null($res[0]['vehicle_category_id']))
		{
			$res4 = $db->exec('select * from directory.vehicle_categories WHERE id = ?', $res[0]['vehicle_category_id']);
			$res[0]['vehicle_category'] = $res4[0];
		}

		if(!is_null($res[0]['vehicle_length_class_id']))
		{
			$res5 = $db->exec('select * from directory.vehicle_length_class WHERE id = ?', $res[0]['vehicle_length_class_id']);
			$res[0]['vehicle_length_class'] = $res5[0];
		}

		if(!is_null($res[0]['vehicle_capacity_class_id']))
		{
			$res6 = $db->exec('select * from directory.vehicle_capacity_class WHERE id = ?', $res[0]['vehicle_capacity_class_id']);
			$res[0]['vehicle_capacity_class'] = $res6[0];
		}

		if(!is_null($res[0]['vehicle_ecological_class_id']))
		{
			$res7 = $db->exec('select * from directory.vehicle_ecological_class WHERE id = ?', $res[0]['vehicle_ecological_class_id']);
			$res[0]['vehicle_ecological_class'] = $res7[0];
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

		$object = new DB\SQL\Mapper($db,'directory.vehicle_models');

		$object->vehicle_brand_id = $body['vehicle_brand_id'];
		$object->vehicle_model = $body['vehicle_model'];
		$object->vehicle_type_id = $body['vehicle_type_id'];
		$object->vehicle_category_id = $body['vehicle_category_id'];
		$object->vehicle_length_class_id = $body['vehicle_length_class_id'];
		$object->vehicle_capacity_class_id = $body['vehicle_capacity_class_id'];
		$object->vehicle_ecological_class_id = $body['vehicle_ecological_class_id'];
		$object->sitting_capacity = $body['sitting_capacity'];
		$object->standing_capacity = $body['standing_capacity'];
		$object->low_floor_vehicle = $body['low_floor_vehicle'];
		$object->cabin_area = $body['cabin_area'];
		$object->vehicle_weight = $body['vehicle_weight'];
		$object->vehicle_length = $body['vehicle_length'];
		$object->vehicle_width = $body['vehicle_width'];
		$object->vehicle_height = $body['vehicle_height'];
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

		$object = new DB\SQL\Mapper($db,'directory.vehicle_models');
		$object->load(array('@id=?',$f3->get('PARAMS.id')));
		if($object->dry())
		{
			$f3->error(404);
		}

		if($body['vehicle_brand_id'] != null){
			$object->vehicle_brand_id = $body['vehicle_brand_id'];
		}
		if($body['vehicle_model'] != null){
			$object->vehicle_model = $body['vehicle_model'];
		}
		if($body['vehicle_type_id'] != null){
			$object->vehicle_type_id = $body['vehicle_type_id'];
		}
		if($body['vehicle_category_id'] != null){
			$object->vehicle_category_id = $body['vehicle_category_id'];
		}
		if($body['vehicle_length_class_id'] != null){
			$object->vehicle_length_class_id = $body['vehicle_length_class_id'];
		}
		if($body['vehicle_capacity_class_id'] != null){
			$object->vehicle_capacity_class_id = $body['vehicle_capacity_class_id'];
		}
		if($body['vehicle_ecological_class_id'] != null){
			$object->vehicle_ecological_class_id = $body['vehicle_ecological_class_id'];
		}
		if($body['sitting_capacity'] != null){
			$object->sitting_capacity = $body['sitting_capacity'];
		}
		if($body['standing_capacity'] != null){
			$object->standing_capacity = $body['standing_capacity'];
		}
		if($body['low_floor_vehicle'] != null){
			$object->low_floor_vehicle = $body['low_floor_vehicle'];
		}
		if($body['cabin_area'] != null){
			$object->cabin_area = $body['cabin_area'];
		}
		if($body['vehicle_weight'] != null){
			$object->vehicle_weight = $body['vehicle_weight'];
		}
		if($body['vehicle_length'] != null){
			$object->vehicle_length = $body['vehicle_length'];
		}
		if($body['vehicle_width'] != null){
			$object->vehicle_width = $body['vehicle_width'];
		}
		if($body['vehicle_height'] != null){
			$object->vehicle_height = $body['vehicle_height'];
		}
		$object->save();
		$f3->status(204);

	}

	//! DELETE method
	function Delete($f3) {

		$db = $this->db;

		$object = new DB\SQL\Mapper($db,'directory.vehicle_models');
		$object->load(array('@id=?',$f3->get('PARAMS.id')));
		if($object->dry())
		{
			$f3->error(404);
		}
		$object->erase();
		$f3->status(204);
	}

}

<?php

//! Front-end processor
class Control_points extends Controller {

	//! GET all method
	function GetAll($f3) {
		$db = $this->db;

		$res = $db->exec('select * from route.control_points ORDER BY id');
		
		foreach($res as &$value)
		{
			if(!is_null($value['control_point_type_id']))
			{
				$res2 = $db->exec('select * from route.control_point_types WHERE id = ?', $value['control_point_type_id']);
				$value['control_point_type'] = $res2[0];
			}
		}
		foreach($res as &$value)
		{
			if(!is_null($value['city_id']))
			{
				$res3 = $db->exec('select * from directory.cities WHERE id = ?', $value['city_id']);
				$value['city'] = $res3[0];
			}
		}
		foreach($res as &$value)
		{
			if(!is_null($value['time_zone_id']))
			{
				$res4 = $db->exec('select * from directory.time_zones WHERE id = ?', $value['time_zone_id']);
				$value['time_zone'] = $res4[0];
			}
		}
		
		foreach($res as &$value)
		{
			if(!is_null($value['organization_id']))
			{
				$res5 = $db->exec('select * from common.organizations  WHERE id = ?', $value['organization_id']);
				$value['organization'] = $res5[0];
				$value['geozone_id'] = $res5[0]['geozone_id'];

				if(!is_null($res5[0]['geozone_id']))
				{
					$res6 = $db->exec('select * from common.geozones WHERE id = ?', $res5[0]['geozone_id']);
					$res[0]['geozone'] = $res6[0];
				}

			}
		}
		

		header('Content-Type: application/json');
		$f3->status(200);

		echo json_encode($res, JSON_UNESCAPED_UNICODE);
	}

	//! GET by id
	function GetOne($f3) {
		$db = $this->db;

		$res = $db->exec('select * from route.control_points WHERE id = ?', $f3->get('PARAMS.id'));
		
		if(!is_null($res[0]['control_point_type_id']))
		{
			$res2 = $db->exec('select * from route.control_point_types WHERE id = ?', $res[0]['control_point_type_id']);
			$res[0]['control_point_type'] = $res2[0];
		}
		
		if(!is_null($res[0]['city_id']))
		{
			$res3 = $db->exec('select * from directory.cities WHERE id = ?', $res[0]['city_id']);
			$res[0]['city'] = $res3[0];
		}
		
		if(!is_null($res[0]['time_zone_id']))
		{
			$res4 = $db->exec('select * from directory.time_zones WHERE id = ?', $res[0]['time_zone_id']);
			$res[0]['time_zone'] = $res4[0];
		}
		
		if(!is_null($res[0]['organization_id']))
		{
			$res5 = $db->exec('select * from common.organizations WHERE id = ?', $res[0]['organization_id']);
			$res[0]['organization'] = $res5[0];

			$res[0]['geozone_id'] = $res5[0]['geozone_id'];

			if(!is_null($res5[0]['geozone_id']))
			{
				$res6 = $db->exec('select * from common.geozones WHERE id = ?', $res5[0]['geozone_id']);
				$res[0]['geozone'] = $res6[0];
			}		
		
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

		$object = new DB\SQL\Mapper($db,'route.control_points');

		$object->latitude = $body['latitude'];
		$object->longitude = $body['longitude'];
		$object->name = $body['name'];
		$object->radius = $body['radius'];
		$object->control_point_type_id = $body['control_point_type_id'];
		$object->direction_id = $body['direction_id'];
		$object->address = $body['address'];
		$object->code = $body['code'];
		$object->city_id = $body['city_id'];
		$object->external_system_id = $body['external_system_id'];
		$object->time_zone_id = $body['time_zone_id'];
		$object->description = $body['description'];
		$object->group_of_stop_points = $body['group_of_stop_points'];
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

		$object = new DB\SQL\Mapper($db,'route.control_points');
		$object->load(array('@id=?',$f3->get('PARAMS.id')));
		if($object->dry())
		{
			$f3->error(404);
		}

		if($body['latitude'] != null){
			$object->latitude = $body['latitude'];
		}
		if($body['longitude'] != null){
			$object->longitude = $body['longitude'];
		}
		if($body['name'] != null){
			$object->name = $body['name'];
		}
		if($body['radius'] != null){
			$object->radius = $body['radius'];
		}
		if($body['control_point_type_id'] != null){
			$object->control_point_type_id = $body['control_point_type_id'];
		}
		if($body['direction_id'] != null){
			$object->direction_id = $body['direction_id'];
		}
		if($body['address'] != null){
			$object->address = $body['address'];
		}
		if($body['code'] != null){
			$object->code = $body['code'];
		}
		if($body['city_id'] != null){
			$object->city_id = $body['city_id'];
		}
		if($body['external_system_id'] != null){
			$object->external_system_id = $body['external_system_id'];
		}
		if($body['time_zone_id'] != null){
			$object->time_zone_id = $body['time_zone_id'];
		}
		if($body['description'] != null){
			$object->description = $body['description'];
		}
		if($body['group_of_stop_points'] != null){
			$object->group_of_stop_points = $body['group_of_stop_points'];
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

		$object = new DB\SQL\Mapper($db,'route.control_points');
		$object->load(array('@id=?',$f3->get('PARAMS.id')));
		if($object->dry())
		{
			$f3->error(404);
		}
		$object->erase();
		$f3->status(204);
	}

}

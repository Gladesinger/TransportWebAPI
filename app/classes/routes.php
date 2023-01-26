<?php

//! Front-end processor
class Routes extends Controller {

	//! GET all method
	function GetAll($f3) {
		$db = $this->db;

		$res = $db->exec('select * from route.routes ORDER BY id');
		foreach($res as &$value)
		{
			$straight = $db->exec('SELECT control_point_id, is_checkpoint 
						   FROM route.route_structures 
						   WHERE route_id = ? AND direction_id = 0 
						   ORDER BY number ASC', $value['id']);

			$reverse = $db->exec('SELECT control_point_id, is_checkpoint 
						   FROM route.route_structures 
						   WHERE route_id = ? AND direction_id = 1 
						   ORDER BY number ASC', $value['id']);
			$value['straight_direction'] = $straight;
			$value['reverse_direction'] = $reverse;
		}
		foreach($res as &$value)
		{	
			if(!is_null($value['transport_type_id']))
			{
				$res2 = $db->exec('select * from common.transport_types WHERE id = ?', $value['transport_type_id']);
				$value['transport_type'] = $res2[0];
			}
		}
		foreach($res as &$value)
		{
			if(!is_null($value['status_id']))
			{
				$res3 = $db->exec('select * from directory.statuses WHERE id = ?', $value['status_id']);
				$value['status'] = $res3[0];
			}
		}
		foreach($res as &$value)
		{
			if(!is_null($value['route_type_id']))
			{
				$res4 = $db->exec('select * from route.route_types WHERE id = ?', $value['route_type_id']);
				$value['route_type'] = $res4[0];
			}
		}
		foreach($res as &$value)
		{
			if(!is_null($value['type_of_regular_transportation_id']))
			{
				$res5 = $db->exec('select * from directory.types_of_regular_transportation WHERE id = ?', $value['type_of_regular_transportation_id']);
				$value['type_of_regular_transportation'] = $res5[0];
			}
		}
		foreach($res as &$value)
		{
			if(!is_null($value['procedures_for_boarding_id']))
			{
				$res6 = $db->exec('select * from directory.procedures_for_boarding_unboarding_passengers WHERE id = ?', $value['procedures_for_boarding_id']);
				$value['procedures_for_boarding'] = $res6[0];
			}
		}
		foreach($res as &$value)
		{
			if(!is_null($value['organization_id']))
			{
				$res7 = $db->exec('select * from common.organizations WHERE id = ?', $value['organization_id']);
				$value['organization'] = $res7[0];
				$value['geozone_id'] = $res7[0]['geozone_id'];

				if(!is_null($res7[0]['geozone_id']))
				{
					$res8 = $db->exec('select * from common.geozones WHERE id = ?', $res7[0]['geozone_id']);
					$res[0]['geozone'] = $res8[0];
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

		$res = $db->exec('select * from route.routes WHERE id = ?', $f3->get('PARAMS.id'));

		$straight = $db->exec('SELECT control_point_id, is_checkpoint 
						   FROM route.route_structures 
						   WHERE route_id = ? AND direction_id = 0 
						   ORDER BY number ASC', $res[0]['id']);

		$reverse = $db->exec('SELECT control_point_id, is_checkpoint 
						   FROM route.route_structures 
						   WHERE route_id = ? AND direction_id = 1 
						   ORDER BY number ASC', $res[0]['id']);
		
		$res[0]['straight_direction'] = $straight;
		$res[0]['reverse_direction'] = $reverse;
		
		$res2 = $db->exec('select * from common.transport_types WHERE id = ?', $res[0]['transport_type_id']);
		$res[0]['transport_type'] = $res2[0];
		
		if(!is_null($res[0]['status_id']))
		{
			$res3 = $db->exec('select * from directory.statuses WHERE id = ?', $res[0]['status_id']);
			$res[0]['status'] = $res3[0];
		}
		
		if(!is_null($res[0]['route_type_id']))
		{
			$res4 = $db->exec('select * from route.route_types WHERE id = ?', $res[0]['route_type_id']);
			$res[0]['route_type'] = $res4[0];
		}
		
		if(!is_null($res[0]['type_of_regular_transportation_id']))
		{
			$res5 = $db->exec('select * from directory.types_of_regular_transportation WHERE id = ?', $res[0]['type_of_regular_transportation_id']);
			$res[0]['type_of_regular_transportation'] = $res5[0];
		}
		
		if(!is_null($res[0]['procedures_for_boarding_id']))
		{
			$res6 = $db->exec('select * from directory.procedures_for_boarding_unboarding_passengers WHERE id = ?', $res[0]['procedures_for_boarding_id']);
			$res[0]['type_of_regular_transportation'] = $res6[0];
		}
		
		$res7 = $db->exec('select * from common.organizations as org LEFT JOIN common.geozones as geo ON geo.id = org.geozone_id  WHERE org.id = ?', $res[0]['organization_id']);
		$res[0]['organization'] = $res7[0];
		$res[0]['geozone_id'] = $res7[0]['geozone_id'];

		if(!is_null($res7[0]['geozone_id']))
		{
			$res8 = $db->exec('select * from common.geozones WHERE id = ?', $res7[0]['geozone_id']);
			$res[0]['geozone'] = $res8[0];
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

		$object = new DB\SQL\Mapper($db,'route.routes');

		$object->name = $body['name'];
		$object->transport_type_id = $body['transport_type_id'];
		$object->start_control_point_id = $body['start_control_point_id'];
		$object->end_control_point_id = $body['end_control_point_id'];
		$object->color = $body['color'];
		$object->operational_speed = $body['operational_speed'];
		$object->route_number = $body['route_number'];
		$object->status_id = $body['status_id'];
		$object->route_type_id = $body['route_type_id'];
		$object->type_of_regular_transportation_id = $body['type_of_regular_transportation_id'];
		$object->procedure_for_boarding_id = $body['procedure_for_boarding_id'];
		$object->streets_and_roads = $body['streets_and_roads'];
		$object->version = $body['version'];
		$object->version_description = $body['version_description'];
		$object->version_start_date = $body['version_start_date'];
		$object->version_end_date = $body['version_end_date'];
		$object->route_line_information = $body['route_line_information'];
		$object->route_feature = $body['route_feature'];
		$object->is_displayed_on_web = $body['is_displayed_on_web'];
		$object->length = $body['length'];
		$object->accounting_start_date = $body['accounting_start_date'];
		$object->accounting_end_date = $body['accounting_end_date'];
		$object->organization_id = $body['organization_id'];
		
		$object->save();

		$route_id = $object->id;

		foreach($body['straight_direction'] as &$value)
		{
			$res = $db->exec('insert into route.route_structures (route_id, direction_id, control_point_id, is_checkpoint) values (?, ?, ?, ?)', array($route_id, 0, $value['control_point_id'], $value['is_checkpoint']));
		}

		foreach($body['reverse_direction'] as &$value)
		{
			$res = $db->exec('insert into route.route_structures (route_id, direction_id, control_point_id, is_checkpoint) values (?, ?, ?, ?)', array($route_id, 1, $value['control_point_id'], $value['is_checkpoint']));
		}

		$f3->status(200);
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

		$object = new DB\SQL\Mapper($db,'route.routes');
		$object->load(array('@id=?',$f3->get('PARAMS.id')));

		if($object->dry())
		{
			$f3->error(404);
		}

		if($body['name'] != null){
			$object->name = $body['name'];
		}
		if($body['transport_type_id'] != null){
			$object->transport_type_id = $body['transport_type_id'];
		}
		if($body['start_control_point_id'] != null){
		$object->start_control_point_id = $body['start_control_point_id'];
		}
		if($body['end_control_point_id'] !=null){
		$object->end_control_point_id = $body['end_control_point_id'];
		}
		if($body['color'] != null){
			$object->color = $body['color'];
		}
		if($body['operational_speed']!=null){
			$object->operational_speed = $body['operational_speed'];
		}
		if($body['route_number']!=null){
		$object->route_number = $body['route_number'];
		}
		if($body['status_id']!=null){
		$object->status_id = $body['status_id'];
		}
		if($body['route_type_id'] !=null){
		$object->route_type_id = $body['route_type_id'];
		}
		if($body['type_of_regular_transportation_id']!=null){
		$object->type_of_regular_transportation_id = $body['type_of_regular_transportation_id'];
		}
		if($body['procedure_for_boarding_id']!=null){
		$object->procedure_for_boarding_id = $body['procedure_for_boarding_id'];
		}
		if($body['streets_and_roads']!=null){
		$object->streets_and_roads = $body['streets_and_roads'];
		}
		if($body['version']!=null){
		$object->version = $body['version'];
		}
		if($body['version_description']!=null){
		$object->version_description = $body['version_description'];
		}
		if($body['version_start_date']!=null){
		$object->version_start_date = $body['version_start_date'];
		}
		if($body['version_end_date']!=null){
		$object->version_end_date = $body['version_end_date'];
		}
		if($body['route_line_information']!=null){
		$object->route_line_information = $body['route_line_information'];
		}
		if($body['route_feature']!=null){
		$object->route_feature = $body['route_feature'];
		}
		if($body['is_displayed_on_web']!=null){
		$object->is_displayed_on_web = $body['is_displayed_on_web'];
		}
		if($body['length']!=null){
		$object->length = $body['length'];
		}
		if($body['accounting_start_date']!=null){
		$object->accounting_start_date = $body['accounting_start_date'];
		}
		if($body['accounting_end_date']!=null){
		$object->accounting_end_date = $body['accounting_end_date'];
		}
		if($body['organization_id']!=null){
		$object->organization_id = $body['organization_id'];
		}
		$object->save();
		
		$route_id = $object->id;
		
		if($body['straight_direction'] != null){
			$res = $db->exec('delete from route.route_structures where route_id=? and direction_id=?', array($route_id, 0));

			foreach($body['straight_direction'] as &$value)
			{
				$res = $db->exec('insert into route.route_structures (route_id, direction_id, control_point_id, is_checkpoint) values (?, ?, ?, ?)', array($route_id, 0, $value['control_point_id'], $value['is_checkpoint']));
			}
		}

		if($body['reverse_direction'] != null){
			$res = $db->exec('delete from route.route_structures where route_id=? and direction_id=?', array($route_id, 1));

			foreach($body['reverse_direction'] as &$value)
			{
				$res = $db->exec('insert into route.route_structures (route_id, direction_id, control_point_id, is_checkpoint) values (?, ?, ?, ?)', array($route_id, 1, $value['control_point_id'], $value['is_checkpoint']));
			}
		}

		$f3->status(204);

	}

	//! DELETE method
	function Delete($f3) {

		$db = $this->db;

		$db->exec('delete from route.route_structures WHERE route_id = ?', $f3->get('PARAMS.id'));

		$object = new DB\SQL\Mapper($db,'route.routes');
		$object->load(array('@id=?',$f3->get('PARAMS.id')));
		if($object->dry())
		{
			$f3->error(404);
		}
		$object->erase();
		$f3->status(204);
	}

}

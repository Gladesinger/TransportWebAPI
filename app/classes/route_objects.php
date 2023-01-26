<?php

//! Front-end processor
class Route_objects extends Controller {

	//! GET all method
	function GetAll($f3) {
		$db = $this->db;

		$res = $db->exec('select * from directory.route_objects ORDER BY id');
			
		foreach($res as &$value)
		{
			$res2 = $db->exec('select * from route.routes WHERE id = ?', $value['route_id']);
			$value['route'] = $res2[0];
		}

		foreach($res as &$value)
		{
			$res3 = $db->exec('select * from directory.object_types WHERE id = ?', $value['object_type_id']);
			$value['object_type'] = $res3[0];
		}

		header('Content-Type: application/json');
		$f3->status(200);

		echo json_encode($res, JSON_UNESCAPED_UNICODE);
	}

	//! GET by id
	function GetOne($f3) {
		$db = $this->db;

		$res = $db->exec('select * from directory.route_objects WHERE id = ? ORDER BY id', $f3->get('PARAMS.id'));

		$res2 = $db->exec('select * from route.routes WHERE id = ?', $res[0]['route_id']);
			$res[0]['route'] = $res2[0];

		if(!is_null($res[0]['object_type_id']))
		{
			$res3 = $db->exec('select * from directory.object_types WHERE id = ?', $res[0]['object_type_id']);
			$res[0]['object_type'] = $res3[0];
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

		$object = new DB\SQL\Mapper($db,'directory.route_objects');

		$object->route_id = $body['route_id'];
		$object->name = $body['name'];
		$object->figure_type = $body['figure_type'];
		$object->figure_width = $body['figure_width'];
		$object->figure_color = $body['figure_color'];
		$object->description = $body['description'];
		$object->object_type_id = $body['object_type_id'];
		$object->show_in_slider = $body['show_in_slider'];
		$object->accounting_start_date = $body['accounting_start_date'];
		$object->accounting_end_date = $body['accounting_end_date'];
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

		$object = new DB\SQL\Mapper($db,'directory.route_objects');
		$object->load(array('@id=?',$f3->get('PARAMS.id')));
		if($object->dry())
		{
			$f3->error(404);
		}

		if($body['route_id'] != null){
			$object->route_id = $body['route_id'];
		}
		if($body['name'] != null){
			$object->name = $body['name'];
		}
		if($body['figure_type'] != null){
			$object->figure_type = $body['figure_type'];
		}
		if($body['figure_width'] != null){
			$object->figure_width = $body['figure_width'];
		}
		if($body['figure_color'] != null){
			$object->figure_color = $body['figure_color'];
		}
		if($body['description'] != null){
			$object->description = $body['description'];
		}
		if($body['object_type_id'] != null){
			$object->object_type_id = $body['object_type_id'];
		}
		if($body['show_in_slider'] != null){
			$object->show_in_slider = $body['show_in_slider'];
		}
		if($body['accounting_start_date'] != null){
			$object->accounting_start_date = $body['accounting_start_date'];
		}
		if($body['accounting_end_date'] != null){
			$object->accounting_end_date = $body['accounting_end_date'];
		}
		$object->save();
		$f3->status(204);

	}

	//! DELETE method
	function Delete($f3) {

		$db = $this->db;

		$object = new DB\SQL\Mapper($db,'directory.route_objects');
		$object->load(array('@id=?',$f3->get('PARAMS.id')));
		if($object->dry())
		{
			$f3->error(404);
		}
		$object->erase();
		$f3->status(204);
	}

}

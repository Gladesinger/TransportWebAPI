<?php

//! Front-end processor
class Parks extends Controller {

	//! GET all method
	function GetAll($f3) {
		$db = $this->db;

		$res = $db->exec('select * from common.parks ORDER BY id');
		
		foreach($res as &$value)
		{
			if(!is_null($value['control_point_id']))
			{
				$res2 = $db->exec('select * from route.control_points WHERE id = ?', $value['control_point_id']);
				$value['control_point'] = $res2[0];
			}
		}

		header('Content-Type: application/json');
		$f3->status(200);

		echo json_encode($res, JSON_UNESCAPED_UNICODE);
	}

	//! GET by id
	function GetOne($f3) {
		$db = $this->db;

		$res = $db->exec('select * from common.parks WHERE id = ?', $f3->get('PARAMS.id'));
		
		if(!is_null($res[0]['control_point_id']))
		{
			$res2 = $db->exec('select * from route.control_points WHERE id = ?', $res[0]['control_point_type_id']);
			$res[0]['control_point'] = $res2[0];
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

		$object = new DB\SQL\Mapper($db,'common.parks');

		$object->control_point_id = $body['control_point_id'];
		$object->short_name = $body['short_name'];
		$object->full_name = $body['full_name'];
		$object->address = $body['address'];

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

		$object = new DB\SQL\Mapper($db,'common.parks');
		$object->load(array('@id=?',$f3->get('PARAMS.id')));
		if($object->dry())
		{
			$f3->error(404);
		}

		if($body['control_point_id'] != null){
			$object->control_point_id = $body['control_point_id'];
		}
		if($body['short_name'] != null){
			$object->short_name = $body['short_name'];
		}
		if($body['full_name'] != null){
			$object->full_name = $body['full_name'];
		}
		if($body['address'] != null){
			$object->address = $body['address'];
		}
		
		$object->save();
		$f3->status(204);

	}

	//! DELETE method
	function Delete($f3) {

		$db = $this->db;

		$object = new DB\SQL\Mapper($db,'common.parks');
		$object->load(array('@id=?',$f3->get('PARAMS.id')));
		if($object->dry())
		{
			$f3->error(404);
		}
		$object->erase();
		$f3->status(204);
	}

}

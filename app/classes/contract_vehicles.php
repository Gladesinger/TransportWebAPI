<?php

//! Front-end processor
class Contract_vehicles extends Controller {

	//! GET all method
	function GetAll($f3) {
		$db = $this->db;

		$res = $db->exec('select * from directory.contract_vehicles ORDER BY id');
			
		foreach($res as &$value)
		{
			if(!is_null($value['contract_id']))
			{
				$res2 = $db->exec('select * from directory.contracts WHERE id = ?', $value['contract_id']);
				$value['contract'] = $res2[0];
			}
		}

		foreach($res as &$value)
		{
			if(!is_null($value['vehicle_id']))
			{
				$res3 = $db->exec('select * from common.vehicles WHERE id = ?', $value['vehicle_id']);
				$value['vehicle'] = $res3[0];
			}
		}

		foreach($res as &$value)
		{
			if(!is_null($value['route_id']))
			{
				$res4 = $db->exec('select * from route.routes WHERE id = ?', $value['route_id']);
				$value['route'] = $res4[0];
			}
		}


		header('Content-Type: application/json');
		$f3->status(200);

		echo json_encode($res, JSON_UNESCAPED_UNICODE);
	}

	//! GET by id
	function GetOne($f3) {
		$db = $this->db;

		$res = $db->exec('select * from directory.contract_vehicles WHERE id = ? ORDER BY id', $f3->get('PARAMS.id'));

		if(!is_null($res[0]['contract_id']))
		{
			$res2 = $db->exec('select * from directory.contracts WHERE id = ?', $res[0]['contract_id']);
			$res[0]['contract'] = $res2[0];
		}

		if(!is_null($res[0]['vehicle']))
		{
			$res3 = $db->exec('select * from common.vehicles WHERE id = ?', $res[0]['vehicle_id']);
			$res[0]['vehicle'] = $res3[0];
		}

		if(!is_null($res[0]['route_id']))
		{
			$res4 = $db->exec('select * from route.routes WHERE id = ?', $res[0]['route_id']);
			$res[0]['route'] = $res4[0];
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

		$object = new DB\SQL\Mapper($db,'directory.contract_vehicles');

		$object->contract_id = $body['contract_id'];
		$object->route_id = $body['route_id'];
		$object->vehicle_id = $body['vehicle_id'];
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

		$object = new DB\SQL\Mapper($db,'directory.contract_vehicles');
		$object->load(array('@id=?',$f3->get('PARAMS.id')));
		if($object->dry())
		{
			$f3->error(404);
		}

		if($body['contract_id'] != null){
			$object->contract_id = $body['contract_id'];
		}
		if($body['route_id'] != null){
			$object->route_id = $body['route_id'];
		}
		if($body['vehicle_id'] != null){
			$object->vehicle_id = $body['vehicle_id'];
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

		$object = new DB\SQL\Mapper($db,'directory.contract_vehicles');
		$object->load(array('@id=?',$f3->get('PARAMS.id')));
		if($object->dry())
		{
			$f3->error(404);
		}
		$object->erase();
		$f3->status(204);
	}

}

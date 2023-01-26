<?php

//! Front-end processor
class Legal_entities extends Controller {

	//! GET all method
	function GetAll($f3) {
		$db = $this->db;

		$res = $db->exec('select * from common.legal_entities ORDER BY id');

		header('Content-Type: application/json');
		$f3->status(200);

		echo json_encode($res, JSON_UNESCAPED_UNICODE);
	}

	//! GET by id
	function GetOne($f3) {
		$db = $this->db;

		$res = $db->exec('select * from common.legal_entities WHERE id = ?', $f3->get('PARAMS.id'));

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

		$object = new DB\SQL\Mapper($db,'common.legal_entities');

		$object->name = $body['name'];
		$object->code = $body['code']; 
		$object->taxpayer_identification_number = $body['taxpayer_identification_number'];
		$object->manager = $body['legal_entity_id'];
		$object->legal_address = $body['legal_address'];
		$object->actual_address = $body['actual_address'];
		$object->phone = $body['phone'];
		$object->email = $body['email'];
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

		$object = new DB\SQL\Mapper($db,'common.legal_entities');
		$object->load(array('@id=?',$f3->get('PARAMS.id')));
		if($object->dry())
		{
			$f3->error(404);
		}

		if($body['name'] != null){
			$object->name = $body['name'];
		}
		if($body['code'] != null){
			$object->code = $body['code']; 
		}
		if($body['taxpayer_identification_number'] != null){
			$object->taxpayer_identification_number = $body['taxpayer_identification_number'];
		}
		if($body['manager'] != null){
			$object->manager = $body['manager'];
		}
		if($body['legal_address'] != null){
			$object->legal_address = $body['legal_address'];
		}
		if($body['actual_address'] != null){
			$object->actual_address = $body['actual_address'];
		}
		if($body['phone'] != null){
			$object->phone = $body['phone'];
		}
		if(!is_null($body['email'])){
			$object->email = $body['email'];
		}
		$object->save();
		$f3->status(204);

	}

	//! DELETE method
	function Delete($f3) {

		$db = $this->db;

		$object = new DB\SQL\Mapper($db,'common.legal_entities');
		$object->load(array('@id=?',$f3->get('PARAMS.id')));
		if($object->dry())
		{
			$f3->error(404);
		}
		$object->erase();
		$f3->status(204);
	}

}

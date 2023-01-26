<?php

//! Front-end processor
class Organizations extends Controller {

	//! GET all method
	function GetAll($f3) {
		$db = $this->db;

		$res = $db->exec('select * from common.organizations ORDER BY id');

		foreach($res as &$value)
		{
			if(!is_null($value['legal_entity_id']))
			{
				$res2 = $db->exec('select * from common.legal_entities WHERE id = ?', $value['legal_entity_id']);
				$value['legal_entity'] = $res2[0];
			}
		}

		foreach($res as &$value)
		{
			if(!is_null($value['geozone_id']))
			{
				$res3 = $db->exec('select * from common.geozones WHERE id = ?', $value['geozone_id']);
				$value['geozone'] = $res3[0];
			}
		}

		header('Content-Type: application/json');
		$f3->status(200);

		echo json_encode($res, JSON_UNESCAPED_UNICODE);
	}

	//! GET by id
	function GetOne($f3) {
		$db = $this->db;

		$res = $db->exec('select * from common.organizations WHERE id = ?', $f3->get('PARAMS.id'));
		
		if(!is_null($res[0]['legal_entitiy_id']))
		{
			$res2 = $db->exec('select * from common.legal_entities WHERE id = ?', $res[0]['legal_entitiy_id']);
			$res[0]['legal_entitiy'] = $res2[0];
		}
		
		if(!is_null($res[0]['geozone_id']))
		{
			$res3 = $db->exec('select * from common.geozones WHERE id = ?', $res[0]['geozone_id']);
			$res[0]['geozone'] = $res3[0];
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

		$object = new DB\SQL\Mapper($db,'common.organizations');

		$object->name = $body['name'];
		$object->active = $body['active']; 
		$object->parent_organization_id = $body['parent_organization_id'];
		$object->legal_entity_id = $body['legal_entity_id'];
		$object->priority = $body['priority'];
		$object->accounting_start_date = $body['accounting_start_date'];
		$object->accounting_end_date = $body['accounting_end_date'];
		$object->geozone_id = $body['geozone_id'];
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

		$object = new DB\SQL\Mapper($db,'common.organizations');
		$object->load(array('@id=?',$f3->get('PARAMS.id')));
		if($object->dry())
		{
			$f3->error(404);
		}

		if($body['name'] != null){
			$object->name = $body['name'];
		}
		if($body['active'] != null){
			$object->active = $body['active']; 
		}
		if($body['parent_organization_id'] != null){
			$object->parent_organization_id = $body['parent_organization_id'];
		}
		if($body['legal_entity_id'] != null){
			$object->legal_entity_id = $body['legal_entity_id'];
		}
		if($body['priority'] != null){
			$object->priority = $body['priority'];
		}
		if($body['accounting_start_date'] != null){
			$object->accounting_start_date = $body['accounting_start_date'];
		}
		if($body['accounting_end_date'] != null){
			$object->accounting_end_date = $body['accounting_end_date'];
		}
		if(!is_null($body['geozone_id'])){
			$object->geozone_id = $body['geozone_id'];
		}
		$object->save();
		$f3->status(204);

	}

	//! DELETE method
	function Delete($f3) {

		$db = $this->db;

		$object = new DB\SQL\Mapper($db,'common.organizations');
		$object->load(array('@id=?',$f3->get('PARAMS.id')));
		if($object->dry())
		{
			$f3->error(404);
		}
		$object->erase();
		$f3->status(204);
	}

}

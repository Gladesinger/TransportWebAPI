<?php

//! Front-end processor
class Contracts extends Controller {

	//! GET all method
	function GetAll($f3) {
		$db = $this->db;

		$res = $db->exec('select * from directory.contracts ORDER BY id');
			
		foreach($res as &$value)
		{
			if(!is_null($value['client_id']))
			{
				$res2 = $db->exec('select * from common.legal_entities WHERE id = ?', $value['client_id']);
				$value['client'] = $res2[0];
			}
		}

		foreach($res as &$value)
		{
			if(!is_null($value['client_id']))
			{
				$res3 = $db->exec('select * from common.organizations WHERE id = ?', $value['implementer_id']);
				$value['implementer'] = $res3[0];
			}
		}

		header('Content-Type: application/json');
		$f3->status(200);

		echo json_encode($res, JSON_UNESCAPED_UNICODE);
	}

	//! GET by id
	function GetOne($f3) {
		$db = $this->db;

		$res = $db->exec('select * from directory.contracts WHERE id = ? ORDER BY id', $f3->get('PARAMS.id'));

		if(!is_null($res[0]['client_id']))
		{
			$res2 = $db->exec('select * from common.legal_entities WHERE id = ?', $res[0]['client_id']);
			$res[0]['client'] = $res2[0];
		}

		
		if(!is_null($res[0]['implementer_id']))
		{
			$res3 = $db->exec('select * from directory.organizations WHERE id = ?', $res[0]['implementer_id']);
			$res[0]['implementer'] = $res3[0];
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

		$object = new DB\SQL\Mapper($db,'directory.contracts');

		$object->code = $body['code'];
		$object->client_id = $body['client_id'];
		$object->implementer_id = $body['implementer_id'];
		$object->contract_file = $body['contract_file'];
		$object->other_documents = $body['other_documents'];
		$object->remark = $body['remark'];
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

		$object = new DB\SQL\Mapper($db,'directory.contracts');
		$object->load(array('@id=?',$f3->get('PARAMS.id')));
		if($object->dry())
		{
			$f3->error(404);
		}

		if($body['code'] != null){
			$object->code = $body['code'];
		}
		if($body['client_id'] != null){
			$object->client_id = $body['client_id'];
		}
		if($body['implementer_id'] != null){
			$object->implementer_id = $body['implementer_id'];
		}
		if($body['contract_file'] != null){
			$object->contract_file = $body['contract_file'];
		}
		if($body['other_documents'] != null){
			$object->other_documents = $body['other_documents'];
		}
		if($body['remark'] != null){
			$object->remark = $body['remark'];
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

		$object = new DB\SQL\Mapper($db,'directory.contracts');
		$object->load(array('@id=?',$f3->get('PARAMS.id')));
		if($object->dry())
		{
			$f3->error(404);
		}
		$object->erase();
		$f3->status(204);
	}

}

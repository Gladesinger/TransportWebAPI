<?php

//! Front-end processor
class Drivers extends Controller {

	//! GET all method
	function GetAll($f3) {
		$db = $this->db;

		//$res = $db->exec('select * from common.drivers LEFT JOIN common.organizations ON common.organizations.id = common.drivers.organization_id ORDER BY drivers.id');

		$res = $db->exec('select * from common.drivers ORDER BY drivers.id');
			
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
			if(!is_null($value['transport_type_id']))
			{
				$res3 = $db->exec('select * from common.organizations WHERE id = ?', $value['organization_id']);
				$value['organization'] = $res3[0];
				if(!is_null($res3[0]['geozone_id']))
				{
					$res4 = $db->exec('select * from common.geozones WHERE id = ?', $res3[0]['geozone_id']);
					$value['geozone'] =  $res4[0];
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

		$res = $db->exec('select  * from common.drivers WHERE drivers.id = ? ORDER BY drivers.id', $f3->get('PARAMS.id'));

		if(!is_null($res[0]['transport_type_id']))
		{
			$res2 = $db->exec('select * from common.transport_types WHERE id = ?', $res[0]['transport_type_id']);
			$res[0]['transport_type'] = $res2[0];
		}
		
		if(!is_null($res[0]['organization_id']))
		{
			$res3 = $db->exec('select * from common.organizations WHERE id = ?', $res[0]['organization_id']);
			$res[0]['organization'] = $res3[0];

			if(!is_null($res3[0]['geozone_id']))
			{
				$res4 = $db->exec('select * from common.geozones WHERE id = ?', $res3[0]['geozone_id']);
				$res[0]['geozone'] = $res4[0];
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

		$object = new DB\SQL\Mapper($db,'common.drivers');

		$object->code = $body['code'];
		$object->table_number = $body['table_number'];
		$object->name = $body['name'];
		$object->surname = $body['surname'];
		$object->patronymic = $body['patronymic'];
		$object->driver_license = $body['driver_license'];
		$object->transport_type_id = $body['transport_type_id'];
		$object->phone = $body['phone'];
		$object->age = $body['age'];
		$object->driving_experience_start_date = $body['driving_experience_start_date'];
		$object->driving_experience_dcat_start_date = $body['driving_experience_dcat_start_date'];
		$object->medical_certificate_valid_until = $body['medical_certificate_valid_until'];
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

		$object = new DB\SQL\Mapper($db,'common.drivers');
		$object->load(array('@id=?',$f3->get('PARAMS.id')));
		if($object->dry())
		{
			$f3->error(404);
		}

		if($body['code'] != null){
			$object->code = $body['code'];
		}
		if($body['table_number'] != null){
			$object->table_number = $body['table_number'];
		}
		if($body['name'] != null){
			$object->name = $body['name'];
		}
		if($body['surname'] != null){
			$object->surname = $body['surname'];
		}
		if($body['patronymic'] != null){
			$object->patronymic = $body['patronymic'];
		}
		if($body['driver_license'] != null){
			$object->driver_license = $body['driver_license'];
		}
		if($body['phone'] != null){
			$object->phone = $body['phone'];
		}
		if($body['age'] != null){
			$object->age = $body['age'];
		}
		if($body['driving_experience_start_date'] != null){
			$object->driving_experience_start_date = $body['driving_experience_start_date'];
		}
		if($body['driving_experience_dcat_start_date'] != null){
			$object->driving_experience_dcat_start_date = $body['driving_experience_dcat_start_date'];
		}
		if($body['medical_certificate_valid_until'] != null){
			$object->medical_certificate_valid_until = $body['medical_certificate_valid_until'];
		}
		if($body['accounting_start_date'] != null){
			$object->accounting_start_date = $body['accounting_start_date'];
		}
		if($body['accounting_end_date'] != null){
			$object->accounting_end_date = $body['accounting_end_date'];
		}
		if($body['organization_id'] != null){
			$object->carrier_id = $body['organization_id'];
		}
		$object->save();
		$f3->status(204);

	}

	//! DELETE method
	function Delete($f3) {

		$db = $this->db;

		$object = new DB\SQL\Mapper($db,'common.drivers');
		$object->load(array('@id=?',$f3->get('PARAMS.id')));
		if($object->dry())
		{
			$f3->error(404);
		}
		$object->erase();
		$f3->status(204);
	}

}

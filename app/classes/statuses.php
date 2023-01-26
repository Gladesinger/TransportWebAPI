<?php

//! Front-end processor
class Statuses extends Controller {

	//! GET all method
	function GetStatuses($f3) {
		$db = $this->db;

		$res = $db->exec('select * from directory.statuses ORDER BY id');

		header('Content-Type: application/json');
		$f3->status(200);

		echo json_encode($res, JSON_UNESCAPED_UNICODE);
	}

	//! GET by id
	function GetStatus($f3) {
		$db = $this->db;

		$res = $db->exec('select * from directory.statuses WHERE id = ?', $f3->get('PARAMS.id'));
		
		if($res == null)
		{
			$f3->error(404);
		}

		header('Content-Type: application/json');
		$f3->status(200);

		echo json_encode($res[0], JSON_UNESCAPED_UNICODE);
	}

	//! POST data
	function AddStatus($f3) {

		$db = $this->db;

		$body = json_decode($f3->get('BODY'), TRUE);
		if($body == null)
		{
			$f3->status(500);
			exit;
		}

		$status = new DB\SQL\Mapper($db,'directory.statuses');

		$status->name = $body['name'];
		$status->accounting_start_date = $body['accounting_start_date'];
		$status->accounting_end_date = $body['accounting_end_date'];
		$status->save();
		$f3->status(204);
	}

	//! PUT data
	function UpdateStatus($f3) {
		$db = $this->db;

		$body = json_decode($f3->get('BODY'), TRUE);
		if($body == null)
		{
			$f3->status(500);
			exit;
		}

		$status = new DB\SQL\Mapper($db,'directory.statuses');
		$status->load(array('@id=?',$f3->get('PARAMS.id')));
		if($status->dry())
		{
			$f3->error(404);
		}

		if($body['name'] != null){
			$status->name = $body['name'];
		}
		if($body['accounting_start_date'] != null){
			$status->accounting_start_date = $body['accounting_start_date'];
		}
		if($body['accounting_end_date'] != null){
			$status->accounting_end_date = $body['accounting_end_date'];
		}
		$status->save();
		$f3->status(204);

	}

	//! DELETE method
	function DeleteStatus($f3) {

		$db = $this->db;

		$status = new DB\SQL\Mapper($db,'directory.statuses');
		$status->load(array('@id=?',$f3->get('PARAMS.id')));
		if($status->dry())
		{
			$f3->error(404);
		}
		$status->erase();
		$f3->status(204);
	}

}

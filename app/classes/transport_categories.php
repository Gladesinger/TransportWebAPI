<?php

//! Front-end processor
class Transport_categories extends Controller {

	//! GET all method
	function GetCategories($f3) {
		$db = $this->db;

		$res = $db->exec('select * from directory.vehicle_categories ORDER BY id');

		header('Content-Type: application/json');
		$f3->status(200);

		echo json_encode($res, JSON_UNESCAPED_UNICODE);
	}

	//! GET by id
	function GetCategory($f3) {
		$db = $this->db;

		$res = $db->exec('select * from directory.vehicle_categories WHERE id = ?', $f3->get('PARAMS.id'));
		
		if($res == null)
		{
			$f3->error(404);
		}

		header('Content-Type: application/json');
		$f3->status(200);

		echo json_encode($res[0], JSON_UNESCAPED_UNICODE);
	}

	//! POST data
	function AddCategory($f3) {

		$db = $this->db;

		$body = json_decode($f3->get('BODY'), TRUE);
		if($body == null)
		{
			$f3->status(500);
			exit;
		}

		$category = new DB\SQL\Mapper($db,'directory.vehicle_categories');

		$category->name = $body['name'];
		$category->description = $body['description'];
		$category->accounting_start_date = $body['accounting_start_date'];
		$category->accounting_end_date = $body['accounting_end_date'];
		$category->save();
		$f3->status(204);
	}

	//! PUT data
	function UpdateCategory($f3) {
		$db = $this->db;

		$body = json_decode($f3->get('BODY'), TRUE);
		if($body == null)
		{
			$f3->status(500);
			exit;
		}

		$category = new DB\SQL\Mapper($db,'directory.vehicle_categories');
		$category->load(array('@id=?',$f3->get('PARAMS.id')));
		if($category->dry())
		{
			$f3->error(404);
		}

		if($body['name'] != null){
			$category->name = $body['name'];
		}
		if($body['description'] != null){
			$category->description = $body['description'];
		}
		if($body['accounting_start_date'] != null){
			$category->accounting_start_date = $body['accounting_start_date'];
		}
		if($body['accounting_end_date'] != null){
			$category->accounting_end_date = $body['accounting_end_date'];
		}
		$category->save();
		$f3->status(204);

	}

	//! DELETE method
	function DeleteCategory($f3) {

		$db = $this->db;

		$category = new DB\SQL\Mapper($db,'directory.vehicle_categories');
		$category->load(array('@id=?',$f3->get('PARAMS.id')));
		if($category->dry())
		{
			$f3->error(404);
		}
		$category->erase();
		$f3->status(204);
	}

}

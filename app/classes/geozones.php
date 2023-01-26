<?php

//! Front-end processor
class Geozones extends Controller {

	//! GET all method
	function GetGeozones($f3) {
		$db = $this->db;

		$res = $db->exec('select * from common.geozones ORDER BY id');

		header('Content-Type: application/json');
		$f3->status(200);

		echo json_encode($res, JSON_UNESCAPED_UNICODE);
	}

	//! GET by id
	function GetGeozone($f3) {
		$db = $this->db;

		$res = $db->exec('select * from common.geozones WHERE id = ?', $f3->get('PARAMS.id'));

		if($res == null)
		{
			$f3->error(404);
		}

		header('Content-Type: application/json');
		$f3->status(200);

		echo json_encode($res[0], JSON_UNESCAPED_UNICODE);
	}

	//! POST data
	function AddGeozone($f3) {

		$db = $this->db;

		$body = json_decode($f3->get('BODY'), TRUE);
		if($body == null)
		{
			$f3->status(500);
			exit;
		}

		$geozone = new DB\SQL\Mapper($db,'common.geozones');

		$geozone->name = $body['name'];
		$geozone->save();
		$f3->status(204);
	}

	function UpdateGeozone($f3) {
		$db = $this->db;

		$body = json_decode($f3->get('BODY'), TRUE);
		if($body == null)
		{
			$f3->status(500);
			exit;
		}

		$geozone = new DB\SQL\Mapper($db,'common.geozones');
		$geozone->load(array('@id=?',$f3->get('PARAMS.id')));
		$geozone->name = $body['name'];
		$geozone->save();
		$f3->status(204);

	}

	function DeleteGeozone($f3) {

		$db = $this->db;

		$geozone = new DB\SQL\Mapper($db,'common.geozones');
		$geozone->load(array('@id=?',$f3->get('PARAMS.id')));
		$geozone->erase();
		$f3->status(204);
	}

}

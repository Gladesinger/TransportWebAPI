<?php

//! Front-end processor
class Vehicle_history extends Controller {

	//! GET all method
	function GetAll($f3) {
		$db = $this->db;

		$body = json_decode($f3->get('BODY'), TRUE);
		if($body == null)
		{
			$f3->status(500);
			exit;
		}

		$from = new DateTime($body['from']);
		$to = new DateTime($body['to']);

		$res = $db->exec("SELECT id, latitude, longitude, direction, speed, altitude, 
					   extract(epoch from measuring_time::timestamp without time zone)::integer 
					   as timestamp 
					   FROM history.monitoring_records 
					   WHERE vehicle_id = ? 
					   AND measuring_time >= 
					   CAST (to_timestamp(?) at time zone 'utc' AS timestamp without time zone) 
					   AND measuring_time <= 
					   CAST (to_timestamp(?) at time zone 'utc' AS timestamp without time zone) 
					   ORDER BY measuring_time ASC", array($body['id'], $from->getTimestamp(), $to->getTimestamp()));

		header('Content-Type: application/json');
		$f3->status(200);

		echo json_encode($res, JSON_UNESCAPED_UNICODE);
	}

	//! GET by id
	function GetOne($f3) {
		$db = $this->db;

		$body = json_decode($f3->get('BODY'), TRUE);
		if($body == null)
		{
			$f3->status(500);
			exit;
		}

		$from = new DateTime($body['from']);
		$to = new DateTime($body['to']);

		$res = $db->exec("SELECT id, latitude, longitude, direction, speed, altitude, 
					   extract(epoch from measuring_time::timestamp without time zone)::integer 
					   as timestamp 
					   FROM history.monitoring_records 
					   WHERE vehicle_id = ? 
					   AND measuring_time >= 
					   CAST (to_timestamp(?) at time zone 'utc' AS timestamp without time zone) 
					   AND measuring_time <= 
					   CAST (to_timestamp(?) at time zone 'utc' AS timestamp without time zone) 
					   ORDER BY measuring_time ASC", array($f3->get('PARAMS.id'), $from->getTimestamp(), $to->getTimestamp()));

		if($res == null)
		{
			$f3->error(404);
		}

		header('Content-Type: application/json');
		$f3->status(200);

		echo json_encode($res, JSON_UNESCAPED_UNICODE);
	}

}

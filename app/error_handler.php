<?php

//! Front-end processor
class Error_handler{

	function error($f3) {
		while (ob_get_level())
            		ob_end_clean();
		
		header('Content-Type: application/json');

		echo json_encode(
			array(	"Code" => $f3->get('ERROR.code'),
				"Level" => $f3->get('ERROR.level'),
				"Status" => $f3->get('ERROR.status'),
				"Text" => $f3->get('ERROR.text'),
				"Trace" => $f3->get('ERROR.trace')
			), JSON_UNESCAPED_UNICODE);

		$log=new Log('error.log');
		$log->write($f3->get('ERROR.text'));
		foreach ($f3->get('ERROR.trace') as $frame)
			if (isset($frame['file'])) {
				// Parse each backtrace stack frame
				$line='';
				$addr=$f3->fixslashes($frame['file']).':'.$frame['line'];
				if (isset($frame['class']))
					$line.=$frame['class'].$frame['type'];
				if (isset($frame['function'])) {
					$line.=$frame['function'];
					if (!preg_match('/{.+}/',$frame['function'])) {
						$line.='(';
						if (isset($frame['args']) && $frame['args'])
							$line.=$f3->csv($frame['args']);
						$line.=')';
					}
				}
				// Write to custom log
				$log->write($addr.' '.$line);
			}
	}

}

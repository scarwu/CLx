<?php
/**
 * CLx Entity Parser Component
 * 
 * @package		CLx
 * @author		ScarWu
 * @copyright	Copyright (c) 2012, ScarWu (http://scar.simcz.tw/)
 * @license		http://opensource.org/licenses/MIT Open Source Initiative OSI - The MIT License (MIT):Licensing
 * @link		http://github.com/scarwu/CLx
 */

namespace CLx\Component;

class EntityParser {
	
	private function __construct() {}
	
	public function init() {
		$headers = getallheaders();
		$contenttype = explode(';', $headers['Content-Type']);
		if($contenttype[0] == 'multipart/form-data') {
			$boundary = str_replace('boundary=', '', trim($contenttype[1], ' '));
			$data = $this->parsePutData($boundary);
			// TODO need test
			// $data = entityParser::init($boundary);
			self::$params = json_decode(trim($data['params'], "\r\n"), TRUE);
			self::$files = $data['file'];
		}
		else {
			parse_str(file_get_contents('php://input'), $input);
			self::$params = isset($input['params']) ? $input['params'] : NULL;
		}
	}

	private function parsePutData($boundary = NULL) {
		$result = NULL;
		
		if($boundary) {
			$input = NULL;
			$regeHeader = sprintf('/--%s\r\n((?:.+\r\n)+)\r\n((?:.|\n)*)/', $boundary);
			$regeEntity = sprintf('/((?:.|\n)*)\r\n(--%s(?:--)?\r\n(?:.|\n)*)/', $boundary);
			// FIXME
			$regeEntityEnd = sprintf('/\r\n--%s--$/', $boundary);
			$bufferSize = strlen($boundary)+5;
	
			$stream = fopen('php://input', 'r');
			$datablock = NULL;
			while($buffer = fread($stream, $bufferSize)) {
				$datablock .= $buffer;
				if(preg_match($regeHeader, $datablock, $match)) {
					$headers = $this->parseHeaders(explode("\r\n", $match[1]));
					
					// Parse multipart/form-data Entity
					if(isset($headers['Content-Disposition']['filename'])) {
						// Parse File Entity
						$name = $headers['Content-Disposition']['name'];
						$result[$name]['name'] = $headers['Content-Disposition']['filename'];
						$result[$name]['type'] = $headers['Content-Type'][0];
						$result[$name]['tmp_name'] = TEMP_DIR . hash('md5', time().$result[$name]['name']);
						$result[$name]['error'] = 0;
						
						$tmpbuffer = array();
						$tmpbuffer[0] = $match[2];
						$file = fopen($result[$name]['tmp_name'], 'w');
						while($buffer = fread($stream, $bufferSize)) {
							$tmpbuffer[1] = $buffer;
							if(preg_match($regeEntity, $tmpbuffer[0].$tmpbuffer[1], $match)) {
								// FIXME
								fwrite($file, preg_replace($regeEntityEnd, '', $match[1]));
								// fwrite($file, $match[1]);
								$datablock = $match[2];
								break;
							}
							else {
								$partfile = array_shift($tmpbuffer);
								fwrite($file, $partfile);
							}
						}
						fclose($file);
						$result[$name]['size'] = filesize($result[$name]['tmp_name']);
					}
					else {
						// Parse Plaintext Entity
						$tmpbuffer = $match[2];
						while($buffer = fread($stream, $bufferSize)) {
							$tmpbuffer .= $buffer;
							if(preg_match($regeEntity, $tmpbuffer, $match)) {
								$plaintext = $match[1];
								$datablock = $match[2];
								break;
							}
						}
						$result[$headers['Content-Disposition']['name']] = $plaintext;
					}
				}
			}
			fclose($stream);
		}
		return $result;
	}
	
	/**--------------------------------------------------
	 * Headers Parser
	 * --------------------------------------------------
	 */
	private function parseHeaders($headers) {
		$result = NULL;
		array_pop($headers);
		foreach($headers as $value) {
			$cHeader = explode(': ', $value);
			$result[$cHeader[0]] = $this->parseHeaderPramas(explode('; ', $cHeader[1]));
		}
		return $result;
	}
	
	/**--------------------------------------------------
	 * Header Pramas Parser
	 * --------------------------------------------------
	 */
	private function parseHeaderPramas($params) {
		$result = NULL;
		$paramsRegex1 = '/(.+)\=\"(.+)\"/';
		$paramsRegex2 = '/(.+)\=(.+)/';
		foreach($params as $value)
			if(preg_match($paramsRegex1, $value, $match))
				$result[$match[1]] = $match[2];
			elseif(preg_match($paramsRegex2, $value, $match))
				$result[$match[1]] = $match[2];
			else
				$result[0] = $value;
		return $result;
	}
}

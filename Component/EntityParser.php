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

	public static function parsePutData($boundary = NULL) {
		$result = NULL;
		
		if($boundary) {
			$input = NULL;
			$regex_header = sprintf('/--%s\r\n((?:.+\r\n)+)\r\n((?:.|\n)*)/', $boundary);
			$regex_entity = sprintf('/((?:.|\n)*)\r\n(--%s(?:--)?\r\n(?:.|\n)*)/', $boundary);
			// FIXME
			$regex_entity_end = sprintf('/\r\n--%s--$/', $boundary);
			$buffer_size = strlen($boundary)+5;
	
			$stream = fopen('php://input', 'r');
			$data_block = NULL;
			while($buffer = fread($stream, $buffer_size)) {
				$data_block .= $buffer;
				if(preg_match($regex_header, $data_block, $match)) {
					$headers = self::parseHeaders(explode("\r\n", $match[1]));
					
					// Parse multipart/form-data Entity
					if(isset($headers['Content-Disposition']['filename'])) {
						// Parse File Entity
						$name = $headers['Content-Disposition']['name'];
						$filename = explode('/', $headers['Content-Disposition']['filename']);
						$result[$name]['name'] = array_pop($filename);
						$result[$name]['type'] = $headers['Content-Type'][0];
						$result[$name]['tmp_name'] = TEMP_DIR . hash('md5', time().$result[$name]['name']);
						$result[$name]['error'] = 0;
						
						$tmp_buffer = array();
						$tmp_buffer[0] = $match[2];
						$file = fopen($result[$name]['tmp_name'], 'w');
						while($buffer = fread($stream, $buffer_size)) {
							$tmp_buffer[1] = $buffer;
							if(preg_match($regex_entity, $tmp_buffer[0].$tmp_buffer[1], $match)) {
								// FIXME
								fwrite($file, preg_replace($regex_entity_end, '', $match[1]));
								// fwrite($file, $match[1]);
								$data_block = $match[2];
								break;
							}
							else {
								$part_file = array_shift($tmp_buffer);
								fwrite($file, $part_file);
							}
						}
						fclose($file);
						$result[$name]['size'] = filesize($result[$name]['tmp_name']);
					}
					else {
						// Parse Plaintext Entity
						$tmp_buffer = $match[2];
						while($buffer = fread($stream, $buffer_size)) {
							$tmp_buffer .= $buffer;
							if(preg_match($regex_entity, $tmp_buffer, $match)) {
								$plaintext = $match[1];
								$data_block = $match[2];
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
	private static function parseHeaders($headers) {
		$result = NULL;
		array_pop($headers);
		foreach($headers as $value) {
			$content_header = explode(': ', $value);
			$result[$content_header[0]] = self::parseHeaderPramas(explode('; ', $content_header[1]));
		}
		return $result;
	}
	
	/**--------------------------------------------------
	 * Header Pramas Parser
	 * --------------------------------------------------
	 */
	private static function parseHeaderPramas($params) {
		$result = NULL;
		$params_regex_1 = '/(.+)\=\"(.+)\"/';
		$params_regex_2 = '/(.+)\=(.+)/';
		foreach($params as $value)
			if(preg_match($params_regex_1, $value, $match))
				$result[$match[1]] = $match[2];
			elseif(preg_match($params_regex_2, $value, $match))
				$result[$match[1]] = $match[2];
			else
				$result[0] = $value;
		return $result;
	}
}

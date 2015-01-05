<?php
/*
 * Part of PHPDotenv
 * Reads json and decodes it to an php array
 *
 * (yes lots of fancy code here)
 */
class json{

	/*
	 * Default function to boot, this function should return an array of data
	 * @param string
	 * @return array
	 */
	public function toArray($json){
		return json_decode($json, true);
	}


}

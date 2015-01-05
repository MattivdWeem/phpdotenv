<?php

class json{

	public function toArray($json){

		// convert json data to an array
		return json_decode($json);

	}


}

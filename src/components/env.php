<?php
/*
 * Reads .env files
 * Part off phpDotenv
 *
 * This class wil read .env file strings and transform them into an array
 * Note: Altough it will work with VERRY VERRY basic .env files; it won't work
 * with more complicated files that are using inline comment or quotes or .. basicly
 * evertyhing except basic begin off line comments and key=val lines.
 *
 */
class env{

	/*
	 * Holds the contents before being returend
	 * @var array
	 */
	protected static $contents = array();

	/*
	 * Holds the stack for items that should be removed
	 * @var array
	 */
	protected static $stack = array();

	/*
	 * Default function to boot, this function should return an array of data
	 * @param string
	 * @return array
	 */
	public function toArray($contents){
		static::$contents = split("\n", $contents);
		$this::loop();
		return static::$contents;
	}


	/*
	 * Run the loop with functions thru the lines
	 * Every stack rotation is chainable so use it.
	 */
	public function loop(){
		foreach(static::$contents as $key => $line):
			$this ->cleanComments($line,$key)->removeStack();
		endforeach;
		foreach(static::$contents as $key => $line):
			$this ->keyVal($line,$key)->removeStack();
		endforeach;
	}

	/*
	 * empties the current key stack
	 */
	public function removeStack(){
		foreach(static::$stack as $key):
			unset(static::$contents[$key]);
		endforeach;
		static::$stack = array();
		return $this;
	}

	/*
	 * Split up the key and value pairs
	 * @param string
	 * @param int
	 */
	public function keyVal($line,$key){
		$keyVal = explode('=', $line, 2);
		if(isset($keyVal)):
			if(isset($keyVal[1])):
				static::$contents[$keyVal[0]] = $keyVal[1];
			else:
				static::$contents[$keyVal[0]] = '';
			endif;
			static::$stack[] = $key;
		endif;
		return $this;
	}

	/*
	 * Add lines that start with # to stack
	 * @param string
	 * @param int
	 */
	public function cleanComments($line,$key){
		$first = substr($line,0,1);
		if($first === '#'):
			static::$stack[] = $key;
		endif;
		return $this;
	}


}

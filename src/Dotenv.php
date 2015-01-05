<?php

class Dotenv{

	/**
	 * The file types the library opens to check contents
	 * @var array
	 */
	protected static $fileTypes = array(
		'env',
		'json'
	);


	/**
	 * Ammount of levels to scope 0 = infinite
	 * @var int
	 */
	protected static $scope = 3;


	/**
	 *
	 * load the given file in to the system
	 * @param path
	 * @param scopes
	 * @return this
	 *
	 */
	public function load($path,$scopes = 0){

		$type = $this->fileType($path);
		if(static::$scope === 0):
			$limit = true;
		else:
			$limit = $scopes < static::$scope;
		endif;

		if($type === '@'):
			if($limit):
				$scopes++;
				foreach(glob($path.'/*') as $file):
					$this->load($file, $scopes);
				endforeach;
			endif;
		elseif(intval($type) < count(static::$fileTypes)):
			$this->push($this->readFile($path, $type));
		else:

		endif;


		return $this;

	}

	/**
	 * Pushes data in the super globals and localized globals
	 * @param data
	 *
	 */
	public function push($data){
		print_r($data);
	}

	/**
	 * Reads the file and get the content into an array
	 * @param path
	 * @param type
	 * @return array
	 *
	 */
	public function readFile($path,$type){
		$contents = file_get_contents($path);

		require_once('components/'.static::$fileTypes[$type].'.php');
		$read = new static::$fileTypes[$type];
		$contents = $read->toArray($contents);

		return $contents;
	}


	/**
	 *
	 * Matches a path against an array.
	 *
	 *
	 * @param $path
	 * @throws \RuntimeException
	 * @return int
	 *
	 */
	public static function fileType($path){
		try {
			if(is_dir($path)):
				return '@';
			endif;

			if(is_file($path)):
				$extension = pathinfo($path, PATHINFO_EXTENSION);
				$fileTypes = static::$fileTypes;
				if(in_array($extension, $fileTypes)):
					foreach($fileTypes as $key => $value):
						if($extension == $value):
							return $key;
						endif;
					endforeach;
				endif;
					throw new Exception('Given path "'.$path.'"  extension "'.$extension.'" does not match fileType array');
			endif;
			throw new Exception('Given path "'.$path.'" is no file or folder.');

		} catch (Exception $e) {
			echo($e->getMessage());
		}
		return false;

	}



	/**
	 * set scoping level
	 * @param int
	 * @return this
	 */
 	public function setScope($setScope){
		static::$scope = $setScope;
		return $this;
	}
}


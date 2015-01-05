<?php


class Dotenv{

 	/**
	 * The file types the library opens to check contents
	 * @var array
	 */
	protected static $fileTypes = array(
		'.env',
		'.json'
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

		if($type === '@' && $limit):
			$scopes++;
			foreach(glob($path.'/*') as $file):
				$this->load($file, $scopes);
			endforeach;
		endif;

		echo $this->fileType($path."\n");
		return $this;

	}

	public function readFile($path){

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
		if(is_dir($path)):
			return '@';
		endif;

		$extension = '.'.pathinfo($path, PATHINFO_EXTENSION);
		$fileTypes = static::$fileTypes;
		if(in_array($extension, $fileTypes)):
			foreach($fileTypes as $key => $value):
				if($extension == $value):
					return $key;
				endif;
			endforeach;
		endif;

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


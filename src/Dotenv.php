<?php
/*
 * PHP Dot Env
 *
 * Reads envoirmental variables
 * for licensing read the LICENSE FILE
 * @author matti van de weem <mvdweem@gmail.com>
 *
 */
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
	 * Required items that should be matched
	 * @var array
	 */
	protected static $required = array();


	/**
	 * temp stack to be hold for fast acces
	 * @var array
	 */
	protected static $stack = array();


	/**
	 * Ammount of levels to scope 0 = infinite
	 * @var int
	 */
	protected static $scope = 3;


	/**
	 * let the library change your current envi vars
	 * @var int
	 */
	protected static $overwrite = true;


	/**
	 *
	 * load the given file in to the system
	 * @param path
	 * @throws exception
	 * @param scopes
	 * @return this
	 *
	 */
	public function load($path,$scopes = 0){
		try {
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
				throw new Exception('File type "'.$type.'" does not match any patern');
			endif;
		} catch (Exception $e) {
			echo($e->getMessage());
		}
		return $this;

	}


	/**
	 * Pushes data in the super globals and localized globals
	 * @param data
	 *
	 */
	public function push($data){
		if(is_array($data) && !empty($data)):
			foreach($data as $key => $value):
				if(static::$overwrite || empty($_ENV[$key])):
					$_ENV[$key] = $value;
					static::$stack[$key] = $value;
				endif;
			endforeach;

		endif;
	}


	/*
	 * Require an class
	 */
	 private function loadClass($class) {
		 if( !class_exists($class, false) ):
			  $class_file = 'components/'.$class.'.php';
			  require_once($class_file);
		 endif;
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
		$this->load_class(static::$fileTypes[$type]);
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
	 * @throws Exception
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


	/**
	 * set overwriting
	 * @param int
	 * @return this
	 */
 	public function setOverwrite($setOverwrite){
		static::$overwrite = $setOverwrite;
		return $this;
	}


	/*
	 *  Add file type to the stack
	 *  @param type string / array of strings
	 */
	public function addFileType($type){
		if(is_array($type)):
			foreach($type as $typeString):
				static::$fileTypes[] = $typeString;
			endforeach;
		else:
			static::$fileTypes[] = $type;
		endif;
		return $this;
	}


	/*
	 * In case you want to use all the components instead of hand filling them in
	 * this function will also remove previous entered components
	 */
	public function useComponents(){
		static::$fileTypes = array();
		$components = glob(__DIR__.'/components/*{.php}', GLOB_BRACE);
		foreach($components as $component):
			static::$fileTypes[] =  str_replace('.php','',basename($component));
		endforeach;
		return $this;
	}


	/*
	 * Add an rquired item to the required stack
	 * @param string
	 */
	public function addRequired($required){
		if(is_array($required)):
			foreach($required as $requiredString):
				static::$required[] = $requiredString;
			endforeach;
		else:
			static::$required[] = $required;
		endif;
		return $this;
	}


	/*
	 * Check if all required items are set (if not trow error)
	 */
	public function checkRequired(){
		try {
			foreach(static::$required as $required):
				if(!isset(static::$stack[$required])):
					throw new Exception('Not all required items are set: '. $required.' missing.');
				endif;
			endforeach;
		} catch (Exception $e) {
			echo($e->getMessage());
		}
		return $this;
	}


	/*
	 * End of script destruction
	 * Check if the required stack exists
	 */
	function __destruct() {
		$this->checkRequired();
   	}
}


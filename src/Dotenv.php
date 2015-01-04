<?php


class Dotenv{

 	/**
	 * The file types the library opens to check contents
	 * @var array
	 */
	protected static $fileTypes = array(
		'.env',
		'.json',
	);


	/**
	 *
	 * Matches a path against an array.
	 * If given path is folder scope for files and return array
	 *
	 *
	 * @param $path
	 * @param $scope Once the path is an folder, read trough all files in given dir.
	 * @param $scopeLimit ammount of sub dirs to scope for files.
	 * @throws \RuntimeException
	 * @return int
	 *
	 */
	public static function fileType($path, $scope = true, $scopeLimit = 3){

	}
}


[![Build Status](https://travis-ci.org/MattivdWeem/phpdotenv.svg?branch=master)](https://travis-ci.org/MattivdWeem/phpdotenv) [![Latest Stable Version](https://poser.pugx.org/mattivdweem/phpdotenv/v/stable.svg)](https://packagist.org/packages/mattivdweem/phpdotenv) [![Total Downloads](https://poser.pugx.org/mattivdweem/phpdotenv/downloads.svg)](https://packagist.org/packages/mattivdweem/phpdotenv) [![Latest Unstable Version](https://poser.pugx.org/mattivdweem/phpdotenv/v/unstable.svg)](https://packagist.org/packages/mattivdweem/phpdotenv) [![License](https://poser.pugx.org/mattivdweem/phpdotenv/license.svg)](https://packagist.org/packages/mattivdweem/phpdotenv) [![SensioLabsInsight](https://insight.sensiolabs.com/projects/6d9e0cb2-933e-4d34-bc8c-257bd81fb81d/mini.png)](https://insight.sensiolabs.com/projects/6d9e0cb2-933e-4d34-bc8c-257bd81fb81d)

PHP dot env
=========

PHP library to read envoirment variables.


#### `.env`?
`.env` files are envoirment files, files and data which you typacly don't want to share with the whole wide world. By the use of `.env` files you can easily give these files premissions and git ignore them.

#### There are tons of librarys and build in functions for this?
Well yea thats true, but this version works a bit different; We use an envoirment folder this makes it easy for switching envoirments, but will also help with keeping your code clean.

#### How to use it?
Prefered method: Composer:

	require: "mattivdweem/phpdotenv": "1.1.3*@dev"

Optional method: clone or download the src folder and include it

	require_once('src/Dotenv.php');
	$Dotenv = new Dotenv;

#### Functions
Except the fact that its super easy to extend, and the part of reading folders intead of files.

Here is an example from my test files:

	$Dotenv
		->setScope(0)
		->setOverwrite(false)
		->useComponents()
		->addRequired(array('CFOO'))
		->load('.env/dev')
		->load('.env/mysql.env')
		->load('.env/github.json');

This is basicly everything it does in a small sum up.
You can read folders, .env files and json files `to add more extensions go to 'extending phpdotenvi'`.

You can change the scope and make the original envoirment files overwriteable.
There is an option to useComponents defined loaded from the component folder (if not used, the defaults will be used).

You can add required items. (these will be ran on __destruction of the class)

And you can load files.

####Function by function

##### `setScope(int)`
Sets the depth of folders to be scanned for files.

 `0 means infinite `

##### `setOverwrite(bool)`
When you have an other script running $_ENV vars you migth want to keep those, by turning the overwrite to false the vars won't be overwritten.

##### `addComponents(string/array)`
Files are matched against extension rules, make sure your prefered extension has a component to turn it into an array.

##### `useComponents()`
Developers are lazy guys, so if you want to be lazy and have no components in development phase, you can replace the systems extensions to all extensions found in the component folder.

##### `addRequired(string/array)`
You can match all your given inputs against required vars, this will throw an error if a required var does noet exists.

##### `load(string)`
This does what it says it does. it loads the given file.


#### Adding Components
The files get matched and run trough an component, the match is simply done with an extension. But getting an usable php array from an file is a bit more tricky, since every file is different.

I like working with json, so i added an json component (this is not magical or something, just an json_decode wrapped in a class). Since it is an dotenvi library there is also a little class to read .env files.

Creating a new component is easy, start off by simply duplicating the json example. Or by following the steps:

 - create a new php file in the src/components folder named after your extension (e.g. json.php)
 - create a class inside this php file called after your extension (e.g. json)
 - create a `public function toArray($contents){}` in your file, this function will be called and used to obtain the php array from you extension
 - inside the class you can do whatever you like as long the toArray function returns an array and recieves an string.

###### Example:

	<?php
	/*
	 * Custom component for custom extension
	 * @param extension = '.custom'
	 */
	class custom{

		/*
		 * Default function to boot, this function should return an array of data
		 * @param string
		 * @return array
		 */
		public function toArray($contents){
			return array();
		}

	}


I did this now what?
You can register your component in different ways:

 * set useComponents() on launch
 * use addComponents('your comps')
 * edit Dotenv.php  //Not suggested



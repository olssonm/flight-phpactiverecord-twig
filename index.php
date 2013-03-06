<?php
	error_reporting(E_ALL);
	ini_set('display_errors', '1');

	include dirname(__FILE__) . '/vendor/autoload.php';

	/**
	* Initiate Twig, and register to Flight
	*/
	$loader = new Twig_Loader_Filesystem(dirname(__FILE__) . '/views'); 
	$twigConfig = array(
		// 'cache'	=>	'./cache/twig/',
		// 'cache'	=>	false,
		'debug'	=>	true,
	);
	Flight::register('view', 'Twig_Environment', array($loader, $twigConfig), function($twig) {
		$twig->addExtension(new Twig_Extension_Debug()); // Add the debug extension
	});

	/**
	* Initiate ActiveRecord
	*/
	ActiveRecord\Config::initialize(function($cfg) {
		$cfg->set_model_directory('./models');
		$cfg->set_connections(
			// mysql://
			array('development' => 'mysql://root:root@localhost/test')
		);
		$cfg->set_default_connection('development');
	});

	/**
	* Add /controllers to the include-path
	*/
	Flight::path(dirname(__FILE__) . '/controllers');

	Flight::route('/', array('IndexController', 'index'));

	Flight::route('/test', function(){
		echo "hello!";
	});

	Flight::route('/test-user', function(){
		$user = new User();
		$user->firstname = 'Marcus';
		$user->surname = 'Olsson';
		$user->email = 'trash@dumpster.com';
		$user->save();

		print_r(User::last());
	});

	Flight::route('/hello', function(){
		$data = array(
			'name' => Flight::request()->query->name
		);
		Flight::view()->display('template.html', $data);
	});

	Flight::start();
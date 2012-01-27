<?php

function is_get() {
	return (empty($_POST));
}

function is_put() {
	if (is_get()) return false;
	return (isset($_POST['_method']) && $_POST['_method'] == 'put');
}

function is_delete() {
	if (is_get()) return false;
	return (isset($_POST['_method']) && $_POST['_method'] == 'delete');
}

function is_post() {
	return ( is_get() || is_put() || is_delete() ) ? false : true;
}

function request_method()
{
	if ( is_get() ) return 'get';
	else if ( is_put() ) return 'put';
	else if ( is_delete() ) return 'delete';
	else if ( is_post() ) return 'post';
	else return 'unkown';
}

function redirect($uri) {
	header('location: ' . $uri);
	exit;
}

function run( $controller )
{
	if ( defined('USE_REWRITES') ) {
		require PROJECT_PATH.'/controllers/'.$controller.'.php';
	}

	$params = $_REQUEST;
	if (function_exists('on_before')) {
		$on_before_params = on_before($params);
		$params = (is_array($on_before_params)) ? $on_before_params : $params;
	}

	$output = '';

	switch( request_method() ) {
	case 'put':
		if (function_exists('on_put'))
		   	$output = on_put($params);
		else
			$output = template('error.php', array('error' => 'on_put is not defined for this resource'));
		break;

	case 'post':
		if (function_exists('on_post'))
		   	$output = on_post($params);
		else
			$output = template('error.php', array('error' => 'on_post is not defined for this resource'));
		break;

	case 'delete':
		if (function_exists('on_delete'))
			$output = on_delete($params);
		else
			$output = template('error.php', array('error' => 'on_delete is not defined for this resource'));
		break;

	case 'get':
		if (function_exists('on_get'))
			$output = on_get($params);
		else
			$output = template('error.php', array('error' => 'on_get is not defined for this resource'));
		break;

	default:
		$output = template('error.php', array('error' =>'unable to dispatch'));
	}
	echo $output;
}
function dispatch()
{
	$request_path = path_info();
	$controller_path = PROJECT_PATH.'/controllers/'.$request_path.'.php';
	//var_dump($request_path);
	//var_dump($_SERVER);
	//var_dump($_GET);
	if (is_file($controller_path)) {
		require $controller_path;
		//die('found a controller '.$controller_path);
	} else {
		die('no controller found for '.$controller_path.', dying');
	}
	run(basename($controller_path,'.php') );
}

function path_info()
{
	if (!isset($_SERVER['PATH_INFO'])) {
		$request_path = substr($_SERVER['REQUEST_URI'],strlen(basename(__FILE__).'php/'));
		if (false !== strpos($request_path, '?')) {
			$request_path = substr($request_path,0,strpos($request_path,'?'));
		}
	} else {
		$request_path = substr($_SERVER['PATH_INFO'],1);
	}
	if (strpos($request_path, '..')) die('go away');
	return $request_path;
}
/**
 * return a sanitized html safe string
 */
function h($str) {
	return htmlspecialchars($str, ENT_QUOTES, 'UTF-8', false);
}

/**
 * simple template function which will inclide the $file from /templates if found
 * @param string $file to look for
 * @param array $params variables to extract
 * @return void
 */
function template($file,$params = array()) {
	if (strpos($file, '..') !== false) return false; // @todo better security here!
	$use_layout = (substr(basename($file),0,1) !== '_' && ! isset($params['_no_layout']) ) ? true : false;
	$render_me = template_path($file);
	$p = $params;
	ob_start();
		if ( $render_me && is_file($render_me) ) {
			require $render_me;
		} else {
			$render_me = $file;
			require template_path('error.php');
		}
	$p['_content'] = ob_get_clean();
	$p['_no_layout'] = true;
	return ($use_layout) ? template('layout.php',$p) : $p['_content'];
}

/**
 * @eturn array of template directories
 */
function template_paths()
{
	return array(PROJECT_PATH.'/templates', COLTRANE_PATH.'/templates' );
}

/**
 * resolves template path
 * @param string $file to look for
 * @return string $file for template() to require
 */
function template_path($file)
{
	foreach(template_paths() as $template_path)
	{
		if (is_file($template_path.'/'.$file)) {
			return $template_path.'/'.$file;
		} else {
		}
	}
	return false;
}

function flash($str, $type = 'alert')
{
  $_SESSION['flash'][$type] = $str;
}

<?php
/*
Plugin Name: Framework for games page
Plugin URI: http://meat.no/
Description: Extend functionality of WP
Author: Meat
Version: 0.1
Author URI: http://meat.no/
*/

/**
 * AUTO Add files from class folder
 */
$path = pathinfo(__FILE__);
$path = $path['dirname'].'/objects/';
$list = scandir($path);
foreach ($list as $file){
    $info = pathinfo($file);
    if($info['extension'] != 'php'){
        continue;
    }
    require_once ($path.$file);
}

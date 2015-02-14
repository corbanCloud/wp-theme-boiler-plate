<?php
/**
 * @package WordPress
 * @subpackage ShopBox Theme
 */

require_once(dirname(__FILE__) . "lib/php/class-nimbus-theme.php");
require_once(dirname(__FILE__) . "lib/php/class-nimbus-utilities.php");

//////////////////////////////
//  Theme Utility Functions //
//////////////////////////////


class Theme extends NimbusTheme{
	//Make theme functions here
}
class ToolKit extends NimbusUtilities{

}
$Tools = new ToolKit();

$Theme = new Theme('Nimbus');

//$Theme->add_script('name', '../path/something');

//$Theme->add_style('name', '../path/something');

//$Theme->add_post_type('Products');
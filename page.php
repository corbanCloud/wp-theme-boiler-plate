<?php
/**
 * @package WordPress
 * @subpackage Nimbus-Boiler-Plate
 */

get_header(); 

if ( have_posts() ) : while ( have_posts() ) : the_post();  

?>


<?php endwhile; endif; // End WP Loop ?>
<?php get_footer(); ?>
<?php
/**
 *
 * @wordpress-plugin
 * Plugin Name:       Lawyer CPT
 * Plugin URI:        https://github.com/hopelight24/lawyer-wordpress-cpt
 * Description:       CPT for Lawyers, use shortcode [lawyer-list] to display in grid
 * Version:           1.0.0
 * Author:            Sazzad Mahmud
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       lawyer-cpt
 * Domain Path:       /languages
 */

function cpt_register_my_cpts_lawyers() {

	/**
	 * Post Type: Lawyers.
	 */

	$labels = [
		"name" => __( "Lawyers", "lawyer-cpt" ),
		"singular_name" => __( "Lawyer", "lawyer-cpt" ),
	];

	$args = [
		"label" => __( "Lawyers", "lawyer-cpt" ),
		"labels" => $labels,
		"description" => "",
		"public" => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"show_in_rest" => true,
		"rest_base" => "",
		"rest_controller_class" => "WP_REST_Posts_Controller",
		"rest_namespace" => "wp/v2",
		"has_archive" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"delete_with_user" => false,
		"exclude_from_search" => false,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"can_export" => false,
		"rewrite" => [ "slug" => "lawyers", "with_front" => true ],
		"query_var" => true,
		"supports" => [ "title", "editor", "thumbnail", "excerpt" ],
		"taxonomies" => [ "category", "post_tag" ],
		"show_in_graphql" => true,
	];

	register_post_type( "lawyers", $args );
}

add_action( 'init', 'cpt_register_my_cpts_lawyers' );

// Load style
function lawyer_cpt_styles()
{
    wp_register_style('lawyer-cpt', plugin_dir_url( __FILE__ ) . 'assets/lawyer-styles.css');
    wp_enqueue_style( 'lawyer-cpt' );
}
add_action('wp_enqueue_scripts', 'lawyer_cpt_styles');

// Shortcode for Lawyers in grid
function lawyer_cpt_create_grid() {
  
    $args = array(
                    'post_type'      => 'lawyers',
                    'posts_per_page' => '8',
                    'publish_status' => 'published',
                    'order' => 'ASC'
                 );
  
    $query = new WP_Query($args);
  
    if($query->have_posts()) :

        $result .= '<div class="lawyer-grid-container">';

        while($query->have_posts()) :
  
            $query->the_post() ;
                      
        $result .= '<div class="lawyer-item">';
            $result .= '<a href="' . get_permalink() . '">';
            $result .= '<div class="lawyer-poster"><img src="' . get_the_post_thumbnail_url() . '"></div>';
            $result .= '<div class="lawyer-position">' . get_field( 'position' ) . '</div>';
            $result .= '<div class="lawyer-name">' . get_the_title() . '</div>';
            $result .= '<div class="lawyer-desc">' . get_the_content() . '</div>';
            $result .= '</a>';
            $result .= '<div class="lawyer-social">';
                // if(get_field( 'instagram' )) ? $result .= '<a href="' . get_field( 'instagram' ) . '" alt="Instagram" target="_blank">Insta</a>' : '';
                // if(get_field( 'linkedin' )) ? $result .= '<a href="' . get_field( 'linkedin' ) . '" alt="linkedin" target="_blank">Insta</a>' : '';
                // if(get_field( 'gmail' )) ? $result .= '<a href="' . get_field( 'gmail' ) . '" alt="gmail" target="_blank">Insta</a>' : '';
                if(get_field( 'instagram' )) {
                    $result .= '<a href="' . get_field( 'instagram' ) . '" alt="Instagram" target="_blank">Insta</a>';
                }
                if(get_field( 'linkedin' )) {
                    $result .= '<a href="' . get_field( 'linkedin' ) . '" alt="linkedin" target="_blank">linkedin</a>';
                }
                if(get_field( 'gmail' )) {
                    $result .= '<a href="' . get_field( 'gmail' ) . '" alt="gmail" target="_blank">gmail</a>';
                }
            $result .= '</div>';
        $result .= '</div>';
  
        endwhile;
  
        wp_reset_postdata();

        $result .= '</div>';
    endif;    
  
    return $result;            
}
  
add_shortcode( 'lawyer-list', 'lawyer_cpt_create_grid' );
<?php
/* Friday Next Extra Functions */

// Custom header
remove_theme_support( 'custom-header' );
add_theme_support( 'custom-header', array(
	'width'           => 615,
	'height'          => 345,
	'header-selector' => '.site-title a',
	'header-text'     => false
) );

// custom post type permalinks
add_filter('post_type_link', 'deb_permalink', 10, 4);
function deb_permalink($permalink, $post_id, $leavename) {
    if (strpos($permalink, '%taxonomy_name%') === FALSE) return $permalink;

    // Get post
    $post = get_post($post_id);
    if (!$post) return $permalink;

    // Get taxonomy terms
    $terms = wp_get_object_terms($post->ID, 'product_types');
    if (!is_wp_error($terms) && !empty($terms) && is_object($terms[0])) {
    	$taxonomy_slug = '';
		if ( $terms[0]->parent ) {
    		$taxonomy_slug .= $terms[1]->slug;
			$taxonomy_slug .= '/';
    	}
		$taxonomy_slug .= $terms[0]->slug;
    } else 
		$taxonomy_slug = 'other';
    return str_replace('%taxonomy_name%', $taxonomy_slug, $permalink);
}

// taxonomy archive links
add_filter( 'term_link', 'db_term_permalink', 10, 3 );
function db_term_permalink( $url, $term, $taxonomy ) {
	if( $term->parent ) {
		$parent = get_term( $term->parent, $taxonomy );
		$replacement = $parent->slug . '/' . $term->slug;
		$url = str_replace( $term->slug, $replacement, $url );
	} else {}
		
	return $url;
}

add_action( 'init', 'fn_rewrite_rules' );
function fn_rewrite_rules() {
	// rewrite rules for CPT matches
	add_rewrite_rule( 'selection/(.*)/(.+)', 'index.php?product=$matches[2]' );
	
	// rewrite rules for taxonomy archives
	add_rewrite_rule( 'selections/(.+)/(.+)', 'index.php?product_types=$matches[2]' );
	
	// remove WP/genesis/studiopress credits
	remove_action( 'genesis_footer', 'genesis_footer_markup_open', 5 );
	remove_action( 'genesis_footer', 'genesis_do_footer' );
	remove_action( 'genesis_footer', 'genesis_footer_markup_close', 15 );
}

add_action( 'wp_head', 'fn_custom_css' );
function fn_custom_css() {
	$css_loc = get_stylesheet_directory_uri();
	
	// Styles for header, etc
	echo '<link rel="stylesheet" id="product-css" type="text/css" href="' . $css_loc . '/fn-extras/fn-basic_styles.css" />';
	if( get_post_type() == 'product' || is_tax() ) {
		echo '<link rel="stylesheet" id="product-css" type="text/css" href="' . $css_loc . '/fn-extras/fn-styles.css" />'; ?>
		
		<!-- ShrareThis JS -->
		<script charset="utf-8" type="text/javascript">var switchTo5x=true;</script>
		<script charset="utf-8" type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
		<script charset="utf-8" type="text/javascript">stLight.options({"publisher":"wp.b4a12d1b-f35f-4036-9725-bfc5c698f3dd"});var st_type="wordpress4.0";</script>
		
		<!-- jssor slider js -->
	    <script type="text/javascript" src="<?php echo $css_loc; ?>/fn-extras/jssor.js"></script>
	    <script type="text/javascript" src="<?php echo $css_loc; ?>/fn-extras/jssor.slider.js"></script>
		<script type="text/javascript" src="<?php echo $css_loc; ?>/fn-extras/sliderstuff.js"></script>
		
		<!-- Featherlight Lightbox -->
		<link rel="stylesheet" type="text/css" href="<?php echo $css_loc; ?>/fn-extras/featherlight.min.css"></script>
		<link rel="stylesheet" type="text/css" href="<?php echo $css_loc; ?>/fn-extras/featherlight.gallery.css"></script>
				
		<?php 
		
	}
}

add_action( 'wp_footer', 'fn_custom_scripts' );
function fn_custom_scripts() {
	$css_loc = get_stylesheet_directory_uri();
	if( get_post_type() == 'product' ) { ?>
		<script type="text/javascript" src="<?php echo $css_loc; ?>/fn-extras/featherlight.min.js"></script>
		<script type="text/javascript" src="<?php echo $css_loc; ?>/fn-extras/featherlight.gallery.min.js"></script>
	<?php }
}

<?php
/**
 *
 * @author	Friday Next
 * @link 	https://friday-next.com
 *
 * Single Product Template
**/

// TODO: Show breadcrumbs before post 

remove_action( 'genesis_loop', 'genesis_do_loop' );
add_action( 'genesis_loop', 'fn_pt_tax_loop' );
function fn_pt_tax_loop() {
	// find all children (manufacturers) in current taxonomy and list
	//  first three are images, rest are text
	if( is_tax() ) {
		global $wp_query;
	    $current_term = $wp_query->get_queried_object();
	}
	if( !$current_term->parent ) {
		$main_term = $current_term->name;
		$main_term_slug = $current_term->slug;
		// Display 3 images from specified folder
		$css_loc = get_stylesheet_directory_uri() . '/fn-extras';
		for( $count = 1; $count <=3; $count++ ) {
			echo '<img src="' . $css_loc . '/img/' . $main_term_slug . '/' . $main_term_slug . '_' . $count . '.jpg" alt="' . $main_term . ' - Debi\'s Bridal San Antonio" title="' . $main_term . ' - Debi\'s Bridal San Antonio" class="tax_three_photos" />';
		}
	}
	$post = get_post( get_the_ID() );
	
	// Show only child items (post types) if child
	if( $terms = get_the_terms( $post->ID, 'product_types' ) ) {
		// Show all manufacturers if parent
		if( !$current_term->parent ) {
			
			// taxonomy archive title here
	
			// Query all manufacturers (child tax) under the main taxonomy
			$manufacturers = get_term_children( $current_term->term_id, 'product_types' );
			$count = 1;

			foreach( $manufacturers as $manufacturer ) {
				$this_term = get_term_by( 'id', $manufacturer, 'product_types' );
				$term_id = (int) $this_term->term_id;
				$name_array[ $this_term->name ] = get_term_link( $term_id, 'product_types' );
			}
			ksort( $name_array );
			
			// echo list of manufacturers underneath the three photos
			echo '<div class="manufacturers">';
				foreach( $name_array as $name => $url ) {
					echo '<a href="' . $url . '">' . $name . '</a><br />';
				}
			echo '</div>';
		} else {
			// We have a child - Query all Post Types with this taxonomy
			$parent = $current_term->parent;
			$parent = get_term_by( 'id', $parent, 'product_types' );
			$parent_name = $parent->name;
			$parent_slug = $parent->slug;
			echo '<h1 class="archive-title">' . $parent_name . ' - ' . $current_term->name . '</h1>';
			$slug = $current_term->slug;
			$args = array(
				'post_type'		=> 'product',
				'product_types'	=> $parent_slug, // parent tax
				'product_types'	=> $slug, // child tax
				'sort'			=> 'title',
				'order'			=> 'ASC',
				'posts_per_page'	=> '-1'
			);
			$products = get_posts( $args );
			
			$count = 1;
			foreach( $products as $product ) :
				$attr = array(
					'class' 	=> 'tax_archive_list'
				);
				if( $count % 3 == 0 )
					$css_last = ' last';
				else $css_last = '';
				
				echo '<div class="product' . $css_last . '">';
					echo '<a href="' . get_permalink( $product->ID ) . '">' . get_the_post_thumbnail( $product->ID, 'full', $attr ) . '</a>';
					echo '<a href="' . get_permalink( $product->ID ) . '"><h3 class="title">' . $product->post_title . '</h3></a>';
				echo '</div>';
				if( $css_last == ' last' ) {
					echo '<div class="separation"></div>';
				}
				$count++;
			endforeach;
			wp_reset_postdata();	
		}
		
	} else {
		// Didn't find anything here
		echo "<strong>New products coming soon!</strong>";

	}
	
}	

genesis();
?>
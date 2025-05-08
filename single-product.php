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
add_action( 'genesis_loop', 'fn_db_loop' );
function fn_db_loop() {
	
	// Pagination
	echo '<div class="pagination">';
	// get all products in child taxonomy and determine proper pagination
	if( get_post_type() == 'product' ) {
		$terms = get_the_terms( get_the_ID(), 'product_types' );
		foreach( $terms as $term ) {
			if( !$term->parent ) {
				//skip it - it's the parent 
			} else {
				// this is the child tax - find all items in this tax
				$term_slug = $term->slug;
				$args = array(
					'post_type'		=> 'product',
					'product_types'	=> $term_slug,
					'sort'			=> 'title',
					'order'			=> 'ASC',
					'posts_per_page'=> '-1'
				);
				$products_in_term = query_posts( $args );
				$current_id = get_the_ID();
				$prev_nav = '';
				$next_product = '';
				foreach( $products_in_term as $product ) {
					// check if it's the current product, and if so, create pagination
					if( $product->ID == $current_id ) {
						// we have the current product
						
						// set next nav
						if( $next = $products_in_term[ $count + 1 ] ) {
							$next_nav = get_permalink( $next->ID );
							$next_title = $next->post_title;
							break;
						} else {
							$next_nav = null;
							break;
						}
						
					} else {
						// we do not yet have the current product
						$prev_nav = get_permalink( $product->ID );
						$prev_title = $product->post_title;
						$count++;
					}
				}
			}
		}
		// display pagination
		if( $prev_nav ) {
			echo '<span class="prev-nav"><a href="' . $prev_nav . '" title="' . $prev_title . '" alt="' . $prev_title . ' - Debi\'s Bridal San Antonio">Previous</a></span>';
		}
		if ( $next_nav ) {
			echo '<span class="next-nav"><a href="' . $next_nav . '" title="' . $next_title . '" alt="' . $next_title . ' - Debi\'s Bridal San Antonio">Next</a></span>';
		}
	}
	echo '</div>';
	
	echo '<h2>' . get_the_title() . '</h2><hr>';
	
	// Two sections, each 50% width, float left.
	//  Photos left, Info right
	
	// Photo Section
	echo '<div class="photo-left">';
	$images = get_field('photo_gallery');

	if( $images ): ?>
		<!-- Jssor Slider Begin -->
	    <div id="slider1_container" style="position: relative; width: 720px;
	        height: 960px; overflow: hidden;">
	        <!-- Loading Screen -->
	        <div u="loading" style="position: absolute; top: 0px; left: 0px;">
	            <div style="filter: alpha(opacity=70); opacity:0.7; position: absolute; display: block;
	                background-color: #000; top: 0px; left: 0px;width: 100%;height:100%;">
	            </div>
	            <div style="position: absolute; display: block; background: url(../img/loading.gif) no-repeat center center; top: 0px; left: 0px;width: 100%;height:100%;">
	            </div>
	        </div>
			
	        <!-- Slides Container -->
	        <div u="slides" style="cursor: move; position: absolute; left: 0px; top: 0px; width: 720px; height: 960px; overflow: hidden;">
	            <?php foreach( $images as $image ): ?>
				<div class="image-center">
					<style type="text/css">
					a.expander span {
						display: block;
						width: 24px;
						height: 24px;
						background: url('<?php echo get_stylesheet_directory_uri(); ?>/fn-extras/img/assets/sprite.png') no-repeat -5px -5px;
						overflow: hidden;
						text-indent: 250%;
						white-space: nowrap;
						perspective: -3000px !important;
					}
					</style>
	                <a class="gallery expander" href="<?php echo $image['url']; ?>"><span>Fullscreen</span></a>
					<img u="image" src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" style="width: 500px; height: auto !important;"/>
	                <img u="thumb" src="<?php echo $image['sizes']['thumbnail']; ?>" alt="<?php echo $image['alt']; ?>" />
	            </div>
				<?php endforeach; ?>
				<script>
				jQuery(document).ready(function(){
					jQuery('.gallery').featherlightGallery();
				});
				
				</script>
	       	 </div>
	         <!-- Thumbnail Navigator Skin Begin -->
	         <div u="thumbnavigator" class="jssort07" style="position: absolute; width: 720px; height: 100px; left:0px; bottom: 0px;">
	             <div style=" background-color: #000; filter:alpha(opacity=30); opacity:.3; width: 100%; height:100%;"></div>
	             <!-- Thumbnail Item Skin Begin -->
	             <style>
	                 /* jssor slider thumbnail navigator skin 07 css */
	                 /*
	                 .jssort07 .p            (normal)
	                 .jssort07 .p:hover      (normal mouseover)
	                 .jssort07 .pav          (active)
	                 .jssort07 .pav:hover    (active mouseover)
	                 .jssort07 .pdn          (mousedown)
	                 */
	                 .jssort07 .i {
	                     position: absolute;
	                     top: 0px;
	                     left: 0px;
	                     width: 99px;
	                     height: 66px;
	                     filter: alpha(opacity=80);
	                     opacity: .8;
	                 }

	                 .jssort07 .p:hover .i, .jssort07 .pav .i {
	                     filter: alpha(opacity=100);
	                     opacity: 1;
	                 }

	                 .jssort07 .o {
	                     position: absolute;
	                     top: 0px;
	                     left: 0px;
	                     width: 97px;
	                     height: 64px;
	                     border: 1px solid #000;
	                     transition: border-color .6s;
	                     -moz-transition: border-color .6s;
	                     -webkit-transition: border-color .6s;
	                     -o-transition: border-color .6s;
	                 }

	                 * html .jssort07 .o {
	                     /* ie quirks mode adjust */
	                     width /**/: 99px;
	                     height /**/: 66px;
	                 }

	                 .jssort07 .pav .o, .jssort07 .p:hover .o {
	                     border-color: #fff;
	                 }

	                 .jssort07 .pav:hover .o {
	                     border-color: #0099FF;
	                 }

	                 .jssort07 .p:hover .o {
	                     transition: none;
	                     -moz-transition: none;
	                     -webkit-transition: none;
	                     -o-transition: none;
	                 }
	             </style>
	             <div u="slides" style="cursor: move;">
	                 <div u="prototype" class="p" style="POSITION: absolute; WIDTH: 99px; HEIGHT: 66px; TOP: 0; LEFT: 0;">
	                     <thumbnailtemplate class="i" style="position:absolute;"></thumbnailtemplate>
	                     <div class="o">
	                     </div>
	                 </div>
	             </div>
	             <!-- Thumbnail Item Skin End -->
	             <!-- Arrow Navigator Skin Begin -->
	             <style>
	                     /* jssor slider arrow navigator skin 11 css */
	                     /*
	                 .jssora11l              (normal)
	                 .jssora11r              (normal)
	                 .jssora11l:hover        (normal mouseover)
	                 .jssora11r:hover        (normal mouseover)
	                 .jssora11ldn            (mousedown)
	                 .jssora11rdn            (mousedown)
	                 */
	                     .jssora11l, .jssora11r, .jssora11ldn, .jssora11rdn {
	                         position: absolute;
	                         cursor: pointer;
	                         display: block;
	                         background: url(../img/a11.png) no-repeat;
	                         overflow: hidden;
	                     }

	                     .jssora11l {
	                         background-position: -11px -41px;
	                     }

	                     .jssora11r {
	                         background-position: -71px -41px;
	                     }

	                     .jssora11l:hover {
	                         background-position: -131px -41px;
	                     }

	                     .jssora11r:hover {
	                         background-position: -191px -41px;
	                     }

	                     .jssora11ldn {
	                         background-position: -251px -41px;
	                     }

	                     .jssora11rdn {
	                         background-position: -311px -41px;
	                     }
	             </style>
	             <!-- Arrow Left -->
	             <span u="arrowleft" class="jssora11l" style="width: 37px; height: 37px; top: 123px; left: 8px;">
	             </span>
	             <!-- Arrow Right -->
	             <span u="arrowright" class="jssora11r" style="width: 37px; height: 37px; top: 123px; right: 8px">
	             </span>
	             <!-- Arrow Navigator Skin End -->
	         </div>
	         <!-- ThumbnailNavigator Skin End -->
	         <script>
	             jssor_slider1_starter('slider1_container');
	         </script>
	    </div>
	<?php endif;
	echo '</div>';
	
	// Info Section
	echo '<div class="info-right">';
	echo get_field('description');
	
	if( have_rows( 'product_attributes' ) ) :
		echo '<div class="product-attributes">';
		while( have_rows( 'product_attributes' ) ) : the_row();
		echo '<div class="row">';
			echo '<strong class="left">' . get_sub_field( 'attribute_name' ) . '</strong>';
			echo '<span class="right">' . get_sub_field( 'attribute_value' ) . '</span>';
		echo '</div>';
		endwhile;
		echo '</div>';
	endif;
	echo '<hr>'; 
	
	// Magnifier Script
	
	// ShareThis Buttons ?>
	<span class='st_facebook_large' st_title='<?php the_title(); ?>' st_url='<?php the_permalink(); ?>'></span>
	<span st_username='debibridal' class='st_twitter_large' st_title='<?php the_title(); ?>' st_url='<?php the_permalink(); ?>'></span>
	<span class='st_googleplus_large' st_title='<?php the_title(); ?>' st_url='<?php the_permalink(); ?>'></span>
	<span class='st_pinterest_large' st_title='<?php the_title(); ?>' st_url='<?php the_permalink(); ?>'></span>
	<p>Share with your friends and family!</p>
	<?php echo '</div>';
	
	// Add Book Appointment Button
	echo '<div class="book-appointment">';
	echo '<p>Want to get your hands on this product?  Book your appointment now!</p>';
	echo '<a href="' . get_site_url() . '/book-your-appointment/">Book Your Appointment!</a>';
	echo '</div>';
}

genesis();
?>
<?php
class Zo_Recent_Posts_Widget_With_Thumbnails extends WP_Widget {

	var $plugin_slug;  // identifier of this plugin for WP
	var $plugin_version; // number of current plugin version
	var $number_posts;  // number of posts to show in the widget
	var $default_thumb_width;  // width of the thumbnail
	var $default_thumb_height; // height of the thumbnail
	var $default_thumb_url; // URL of the default thumbnail

	function __construct() {
	   	$args = array(
			'name'        => esc_html__( 'Recent Posts With Thumbnails', 'auriane' ),
			'description' => esc_html__( '', 'auriane' ),
			'classname'   => ''
		);
		parent::__construct( 'zo-recent-posts-widget-with-thumbnails', esc_html__( 'Recent Posts With Thumbnails', 'auriane' ) , $args );

        
		$this->plugin_version  = '2.3.1';
		$this->number_posts  = 5;
        $this->image_size  = '160x160';
		$this->default_thumb_width  = 55;
		$this->default_thumb_height = 55;
		$this->default_thumb_url = get_template_directory_uri() .'/assets/images/default_thumb.png';
	}

	function widget( $args, $instance ) {
		$cache = array();
		if ( ! $this->is_preview() ) {
			$cache = wp_cache_get( $this->plugin_slug, 'widget' );
		}

		if ( ! is_array( $cache ) ) {
			$cache = array();
		}

		if ( ! isset( $args[ 'widget_id' ] ) ) {
			$args[ 'widget_id' ] = $this->id;
		}

		if ( isset( $cache[ $args[ 'widget_id' ] ] ) ) {
			echo do_shortcode($cache[ $args[ 'widget_id' ] ]);
			return;
		}

		ob_start();
		extract( $args );

		$title = ( ! empty( $instance[ 'title' ] ) ) ? $instance[ 'title' ] : '';

		/** This filter is documented in wp-includes/default-widgets.php */
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );


		$number_posts		= ( ! empty( $instance[ 'number_posts' ] ) ) ? absint( $instance[ 'number_posts' ] ) : $this->number_posts;
        $image_size		= ( ! empty( $instance[ 'image_size' ] ) ) ?  $instance[ 'image_size' ]  : $this->image_size;
		$thumb_width 		= ( ! empty( $instance[ 'thumb_width' ] ) ) ? absint( $instance[ 'thumb_width' ] ) : $this->default_thumb_width;
		$thumb_height 		= ( ! empty( $instance[ 'thumb_height' ] ) ) ? absint( $instance[ 'thumb_height' ] ) : $this->default_thumb_height;

		$keep_aspect_ratio  = isset( $instance[ 'keep_aspect_ratio' ] ) ? $instance[ 'keep_aspect_ratio' ] : false;
		$hide_title			= isset( $instance[ 'hide_title' ] ) ? $instance[ 'hide_title' ] : false;
        $hide_author			= isset( $instance[ 'hide_author' ] ) ? $instance[ 'hide_author' ] : false;
        $hide_thumbnail			= isset( $instance[ 'hide_thumbnail' ] ) ? $instance[ 'hide_thumbnail' ] : false;
		$hide_content		= isset( $instance[ 'hide_content' ] ) ? $instance[ 'hide_content' ] : false;
		$hide_button		= isset( $instance[ 'hide_button' ] ) ? $instance[ 'hide_button' ] : false;
		$show_date 			= isset( $instance[ 'show_date' ] ) ? $instance[ 'show_date' ] : false;
		$show_thumb 		= isset( $instance[ 'show_thumb' ] ) ? $instance[ 'show_thumb' ] : false;
		$use_default 		= isset( $instance[ 'use_default' ] ) ? $instance[ 'use_default' ] : false;
		$try_1st_img 		= isset( $instance[ 'try_1st_img' ] ) ? $instance[ 'try_1st_img' ] : false;
		$only_1st_img 		= isset( $instance[ 'only_1st_img' ] ) ? $instance[ 'only_1st_img' ] : false;

		// sanitizes vars
		if ( ! $number_posts ) 	$number_posts = $this->number_posts;
        if ( ! $image_size ) 	$image_size = $this->image_size;
		if ( ! $thumb_width )	$thumb_width = $this->default_thumb_width;
		if ( ! $thumb_height )	$thumb_height = $this->default_thumb_height;
	

		$size 	= array( $thumb_width, $thumb_height );

		/**
		 * Filter the arguments for the Recent Posts widget.
		 *
		 * @since 1.0
		 *
		 * @see WP_Query::get_posts()
		 *
		 * @param array $args An array of arguments used to retrieve the recent posts.
		 */
		$r = new WP_Query( apply_filters( 'widget_posts_args', array(
			'posts_per_page'      => $number_posts,
			'no_found_rows'       => true,
			'post_status'         => 'publish'
		) ) );

		if ( $r->have_posts()) :
		?>
		<?php echo do_shortcode($before_widget); ?>
		<?php if ( $title ) echo do_shortcode($before_title) . do_shortcode($title) . do_shortcode($after_title); ?>
		<ul class="ct_ul_ol widget_zo-recent-posts-widget-with-thumbnails">
		<?php while ( $r->have_posts() ) : $r->the_post(); ?>
			<li class="s_post">
				<div class="tb-recent-detail">
                    <div class="post-tumb">
                    <?php if ( ! $hide_thumbnail ) {  ?>
                        <?php if (function_exists('jws_getImageBySize')) {
                                 $attach_id = get_post_thumbnail_id();
                        
                                 $img = jws_getImageBySize(array('attach_id' => $attach_id, 'thumb_size' => $image_size, 'class' => 'attachment-large wp-post-image'));
                                 echo (!empty($img['thumbnail'])) ? ''.$img['thumbnail'] : '';
                        
                                 }
                    }?>    
                    </div>
                    <div class="post-title-and-date">
					<?php if ( ! $hide_title ) { ?>
							<h4><a href="<?php the_permalink();?>" title="<?php the_title();?>">		
								<?php 
									$trimtitle = get_the_title();
									$shorttitle = wp_trim_words( $trimtitle, $num_words = 6, $more = '');
									echo do_shortcode($shorttitle);  
								?>
								</a>
							</h4>
					<?php }?>
                    <?php if ( ! $hide_author ) { ?>
						<div class="tb-post-author">
							<p>		
								<?php 
								echo esc_html__('By ', 'auriane').'<span>'.get_the_author().'</span>'
								?>
							</p>
							
						</div>
					<?php }?> 
					 <?php if ( $show_date ) : ?> 
						<p class="post-meta">
                            <?php echo get_the_date(); ?>
                        </p>
					<?php	endif; ?>
					<?php if ( ! $hide_content ) { ?>
						<div class="tb-post-content">
							<?php 
							the_excerpt();	     
							?>
						</div>
					<?php }?>
					
					<?php if ( ! $hide_button ) { ?>
						<!-- Btn read mor -->
						<a href="<?php the_permalink(); ?>" class="btn btn-default"><?php esc_html_e('Read More', 'auriane');?></a>
					
					<?php }?>
                    </div>	
				</div>	
               </li>   			
		<?php endwhile; ?>
           
		</ul>
		<?php echo do_shortcode($after_widget); ?>
<?php
		// Reset the global $the_post as this query will have stomped on it
		wp_reset_postdata();

		endif;

		if ( ! $this->is_preview() ) {
			$cache[ $args[ 'widget_id' ] ] = ob_get_flush();
			wp_cache_set( $this->plugin_slug, $cache, 'widget' );
		} else {
			ob_end_flush();
		}
	}

	function update( $new_widget_settings, $old_widget_settings ) {
		$instance = $old_widget_settings;
		// sanitize user input before update
		$instance[ 'number_posts' ]	= absint( $new_widget_settings[ 'number_posts' ] );
        $instance[ 'image_size' ]	= $new_widget_settings[ 'image_size' ];
		$instance[ 'title' ] 		= strip_tags( $new_widget_settings[ 'title' ]);
	
		$instance[ 'keep_aspect_ratio' ] = isset( $new_widget_settings[ 'keep_aspect_ratio' ] ) ? (bool) $new_widget_settings[ 'keep_aspect_ratio' ] : false;
		$instance[ 'hide_title' ] 	= isset( $new_widget_settings[ 'hide_title' ] ) ? (bool) $new_widget_settings[ 'hide_title' ] : false;
        $instance[ 'hide_author' ] 	= isset( $new_widget_settings[ 'hide_author' ] ) ? (bool) $new_widget_settings[ 'hide_author' ] : false;
        $instance[ 'hide_thumbnail' ] 	= isset( $new_widget_settings[ 'hide_thumbnail' ] ) ? (bool) $new_widget_settings[ 'hide_thumbnail' ] : false;
		$instance[ 'hide_content' ] 	= isset( $new_widget_settings[ 'hide_content' ] ) ? (bool) $new_widget_settings[ 'hide_content' ] : false;
		$instance[ 'hide_button' ] 	= isset( $new_widget_settings[ 'hide_button' ] ) ? (bool) $new_widget_settings[ 'hide_button' ] : false;
		$instance[ 'show_date' ] 	= isset( $new_widget_settings[ 'show_date' ] ) ? (bool) $new_widget_settings[ 'show_date' ] : false;
		$instance[ 'use_default' ] 	= isset( $new_widget_settings[ 'use_default' ] ) ? (bool) $new_widget_settings[ 'use_default' ] : false;
		$instance[ 'try_1st_img' ] 	= isset( $new_widget_settings[ 'try_1st_img' ] ) ? (bool) $new_widget_settings[ 'try_1st_img' ] : false;
		$instance[ 'only_1st_img' ] = isset( $new_widget_settings[ 'only_1st_img' ] ) ? (bool) $new_widget_settings[ 'only_1st_img' ] : false;

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset( $alloptions[ $this->plugin_slug ]) )
			delete_option( $this->plugin_slug );

		// return sanitized current widget settings
		return $instance;
	}

	function form( $instance ) {
		$title        = isset( $instance[ 'title' ] ) ? esc_attr( $instance[ 'title' ] ) : '';

		$number_posts = isset( $instance[ 'number_posts' ] ) ? absint( $instance[ 'number_posts' ] ) : $this->number_posts;
        $image_size = isset( $instance[ 'image_size' ] ) ? $instance[ 'image_size' ] : $this->image_size;
		$keep_aspect_ratio = isset( $instance[ 'keep_aspect_ratio' ] ) ? (bool) $instance[ 'keep_aspect_ratio' ] : false;
		$hide_title   = isset( $instance[ 'hide_title' ] ) ? (bool) $instance[ 'hide_title' ] : false;
       	$hide_author   = isset( $instance[ 'hide_author' ] ) ? (bool) $instance[ 'hide_author' ] : false;
        $hide_thumbnail   = isset( $instance[ 'hide_thumbnail' ] ) ? (bool) $instance[ 'hide_thumbnail' ] : false;
		$hide_content = isset( $instance[ 'hide_content' ] ) ? (bool) $instance[ 'hide_content' ] : false;
		$hide_button  = isset( $instance[ 'hide_button' ] ) ? (bool) $instance[ 'hide_button' ] : false;
		$show_date    = isset( $instance[ 'show_date' ] ) ? (bool) $instance[ 'show_date' ] : false;
		$use_default  = isset( $instance[ 'use_default' ] ) ? (bool) $instance[ 'use_default' ] : false;
		$try_1st_img  = isset( $instance[ 'try_1st_img' ] ) ? (bool) $instance[ 'try_1st_img' ] : false;
		$only_1st_img = isset( $instance[ 'only_1st_img' ] ) ? (bool) $instance[ 'only_1st_img' ] : false;
		
		// sanitize vars
		if ( ! $number_posts )	$number_posts = $this->number_posts;
		if ( ! $image_size )	$image_size = $this->image_size;
		?>
        <p><input class="checkbox" type="checkbox" <?php checked( $hide_thumbnail ); ?> id="<?php echo esc_attr($this->get_field_id( 'hide_thumbnail' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'hide_thumbnail' )); ?>" />
		<label for="<?php echo esc_attr($this->get_field_id( 'hide_thumbnail' )); ?>"><?php esc_html_e( 'Do not show hide_thumbnail?', 'auriane' ); ?> <em><?php esc_html_e( 'Make sure you set a default thumbnail for posts without a tille.', 'auriane' ); ?></em></label> </p>
        
		<p><label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'auriane' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
        
		<p><label for="<?php echo esc_attr($this->get_field_id( 'number_posts' )); ?>"><?php esc_html_e( 'Number of posts to show:', 'auriane' ); ?></label>
		<input id="<?php echo esc_attr($this->get_field_id( 'number_posts' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'number_posts' )); ?>" type="text" value="<?php echo esc_attr($number_posts); ?>" size="3" /></p>
        
        <p><label for="<?php echo esc_attr($this->get_field_id( 'image_size' )); ?>"><?php esc_html_e( 'Image Size', 'auriane' ); ?></label>
		<input id="<?php echo esc_attr($this->get_field_id( 'image_size' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'image_size' )); ?>" type="text" value="<?php echo esc_attr($image_size); ?>"/></p>

		<p><input class="checkbox" type="checkbox" <?php checked( $show_date ); ?> id="<?php echo esc_attr($this->get_field_id( 'show_date' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'show_date' )); ?>" />
		<label for="<?php echo esc_attr($this->get_field_id( 'show_date' )); ?>"><?php esc_html_e( 'Display post date?', 'auriane' ); ?></label></p>
		
		<p><input class="checkbox" type="checkbox" <?php checked( $hide_title ); ?> id="<?php echo esc_attr($this->get_field_id( 'hide_title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'hide_title' )); ?>" />
		<label for="<?php echo esc_attr($this->get_field_id( 'hide_title' )); ?>"><?php esc_html_e( 'Do not show title?', 'auriane' ); ?> <em><?php esc_html_e( 'Make sure you set a default title for posts without a tille.', 'auriane' ); ?></em></label> </p>
		
        <p><input class="checkbox" type="checkbox" <?php checked( $hide_author ); ?> id="<?php echo esc_attr($this->get_field_id( 'hide_author' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'hide_author' )); ?>" />
		<label for="<?php echo esc_attr($this->get_field_id( 'hide_author' )); ?>"><?php esc_html_e( 'Do not show author?', 'auriane' ); ?> <em><?php esc_html_e( 'Make sure you set a default author for posts without a tille.', 'auriane' ); ?></em></label> </p>
        
		<p><input class="checkbox" type="checkbox" <?php checked( $hide_content ); ?> id="<?php echo esc_attr($this->get_field_id( 'hide_content' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'hide_content' )); ?>" />
		<label for="<?php echo esc_attr($this->get_field_id( 'hide_content' )); ?>"><?php esc_html_e( 'Do not show content?', 'auriane' ); ?> <em><?php esc_html_e( 'Make sure you set a default content for posts without a content.', 'auriane' ); ?></em></label> </p>
		
		<p><input class="checkbox" type="checkbox" <?php checked( $hide_button); ?> id="<?php echo esc_attr($this->get_field_id( 'hide_button' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'hide_button' )); ?>" />
		<label for="<?php echo esc_attr($this->get_field_id( 'hide_button' )); ?>"><?php esc_html_e( 'Do not show button read more?', 'auriane' ); ?> <em><?php esc_html_e( 'Make sure you set a default button for posts without a button.', 'auriane' ); ?></em></label> </p>
		
	<?php
	}

	/**
	 * Returns the id of the first image in the content, else 0
	 *
	 * @access   private
	 * @since     2.0
	 *
	 * @return    integer    the post id of the first content image
	 */
	function get_first_content_image_id ( $content ) {
		// set variables
		global $wpdb;
		// look for images in HTML code
		preg_match_all( '/<img[^>]+>/i', $content, $all_img_tags );
		if ( $all_img_tags ) {
			foreach ( $all_img_tags[ 0 ] as $img_tag ) {
				// find class attribute and catch its value
				preg_match( '/<img.*?class\s*=\s*[\'"]([^\'"]+)[\'"][^>]*>/i', $img_tag, $img_class );
				if ( $img_class ) {
					// Look for the WP image id
					preg_match( '/wp-image-([\d]+)/i', $img_class[ 1 ], $found_id );
					// if first image id found: check whether is image
					if ( $found_id ) {
						$img_id = absint( $found_id[ 1 ] );
						// if is image: return its id
						if ( wp_get_attachment_image_src( $img_id ) ) {
							return $img_id;
						}
					} // if(found_id)
				} // if(img_class)
				
				// else: try to catch image id by its url as stored in the database
				// find src attribute and catch its value
				preg_match( '/<img.*?src\s*=\s*[\'"]([^\'"]+)[\'"][^>]*>/i', $img_tag, $img_src );
				if ( $img_src ) {
					// delete optional query string in img src
					$url = preg_replace( '/([^?]+).*/', '\1', $img_src[ 1 ] );
					// delete image dimensions data in img file name, just take base name and extension
					$guid = preg_replace( '/(.+)-\d+x\d+\.(\w+)/', '\1.\2', $url );
					// look up its ID in the db
					$found_id = $wpdb->get_var( $wpdb->prepare( "SELECT `ID` FROM $wpdb->posts WHERE `guid` = '%s'", $guid ) );
					// if first image id found: return it
					if ( $found_id ) {
						return absint( $found_id );
					} // if(found_id)
				} // if(img_src)
			} // foreach(img_tag)
		} // if(all_img_tags)
		
		// if nothing found: return 0
		return 0;
	}

	/**
	 * Echoes the thumbnail of first post's image and returns success
	 *
	 * @access   private
	 * @since     2.0
	 *
	 * @return    bool    success on finding an image
	 */
	private function the_first_posts_image ( $content, $size ) {
		// look for first image
		$thumb_id = $this->get_first_content_image_id( $content );
		// if there is first image then show first image
		if ( $thumb_id ) :
			echo wp_get_attachment_image( $thumb_id, $size );
			return true;
		else :
			return false;
		endif; // thumb_id
	}

	/**
	 * Generate the css file with stored settings
	 *
	 * @since 2.3
	 */
	private function make_css_file () {

		// get stored settings
		$all_instances = $this->get_settings();

		// generate CSS
		$css_code = sprintf( '.%s ul { list-style: outside none none; }', $this->plugin_slug );
		$css_code .= "\n"; 
		$css_code .= sprintf( '.%s ul li { overflow: hidden; margin: 0 0 1.5em; }', $this->plugin_slug );
		$css_code .= "\n"; 
		$css_code .= sprintf( '.%s ul li img { display: inline; float: left; margin: .3em .75em .75em 0; }', $this->plugin_slug );
		$css_code .= "\n";
		$set_default = true;
		foreach ( $all_instances as $number => $settings ) {
			if ( isset( $settings[ 'thumb_width' ] ) ) {
				$width  = absint( $settings[ 'thumb_width' ]  );
				if ( ! $width ) $width = $this->default_thumb_width;
			}
			if ( isset( $settings[ 'thumb_height' ] ) ) {
				$height = absint( $settings[ 'thumb_height' ] );
				if ( ! $height ) $height = $this->default_thumb_height;
			}
			$keep_aspect_ratio = false;
			if ( isset( $settings[ 'keep_aspect_ratio' ] ) ) {
				$keep_aspect_ratio = (bool) $settings[ 'keep_aspect_ratio' ];
			}
			if ( $keep_aspect_ratio ) {
				$css_code .= sprintf( '#%s-%d img { max-width: %dpx; width: 100%%; height: auto; }', $this->plugin_slug, $number, $width );
				$css_code .= "\n"; 
			} else {
				$css_code .= sprintf( '#%s-%d img { width: %dpx; height: %dpx; }', $this->plugin_slug, $number, $width, $height );
				$css_code .= "\n"; 
			}
			$set_default = false;
		}
		// set at least this statement if no settings are stored
		if ( $set_default ) {
			$css_code .= sprintf( '.%s img { width: %dpx; height: %dpx; }', $this->plugin_slug, $this->default_thumb_width, $this->default_thumb_height );
			$css_code .= "\n"; 
		}
	}

} 


if(function_exists('insert_widgets')) {
    insert_widgets( 'Zo_Recent_Posts_Widget_With_Thumbnails' );
}
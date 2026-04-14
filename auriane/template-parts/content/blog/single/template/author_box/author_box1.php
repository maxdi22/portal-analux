<?php
/*Author*/
$bio = get_the_author_meta('description');
        if(empty($bio)) return;
		?>
		
		<div class="post-about-author">
            <div class="row row-eq-height">
			<div class="post-author-avatar col-xl-3 col-lg-3"><?php  echo get_avatar( get_the_author_meta( 'ID' ), 240 ); ?></div>
			<div class="post-author-info col-xl-9 col-lg-9">
                <h3 class="at-name"><a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author(); ?></a></h3>
				<div class="description"><?php echo ''.$bio; ?></div>
                <div class="icon-author">
                    <a href="<?php echo get_the_author_meta('facebook'); ?>"><i class="fab fa-facebook-f"></i></a>
                    <a href="<?php echo get_the_author_meta('twitter'); ?>"><i class="fab fa-twitter"></i></a>
                    <a href="<?php echo get_the_author_meta('linkedin'); ?>"><i class="fab fa-linkedin-in"></i></i></a>
                </div>
			</div>
            </div>
		</div>
<?php
/*
Template Name: confirm your email
*/
get_header();
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">
            <div class="page-mail container">
			<?php
            global $jws_option;  
            if(function_exists('jws_send_mail_content')) {
                echo jws_send_mail_content($jws_option);
            }
			?>
        </div>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
  
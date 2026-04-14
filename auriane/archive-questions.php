<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Zahar
 * @since 1.0.0
 */

  
?>
<div id="answer-question-list">
<ul>
<?php
if ( have_posts() ) :             
	while ( have_posts() ) :
		the_post();
        ?>    
          <li>
            <div class="q-name">
               <?php echo  get_post_meta( get_the_ID(), 'product_name', true ); ?>
            </div>
            <span class="q-question">Q: <?php echo get_the_title(); ?></span>
            <span class="q-date"><?php echo get_the_date(); ?></span>
            <div class="answer_content">
                <?php
                    $answer_content = get_post_meta( get_the_ID(), 'answer_content', true );
                    if($answer_content) {
                          $author_name  =  get_the_author_meta( 'display_name' );
                          echo '<div>'.$author_name.'</div>';
                      echo '<p>A: '.$answer_content.'</p>';
                    }else{
                      echo esc_html__('There are no answers for this question yet.','auriane');  
                    }
                 ?>
            </div>
          </li>
        
        <?php
	endwhile;
endif;
?>
</ul>
<?php
global $wp_query;   echo function_exists('jws_query_pagination') ? jws_query_pagination($wp_query) : ''; 
?>

</div>


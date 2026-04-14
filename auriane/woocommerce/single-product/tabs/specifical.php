<?php 
     	 $content = get_post_meta( get_the_ID(), 'specification', true );
   // The new tab content
   if(!empty($content)) {
       echo wpautop($content); 
   }
?>
<nav class="navigation post-navigation" role="navigation">
                    <div class="nav-links">
                        <?php
                        if (is_attachment()) :
                            previous_post_link('%link',
                                esc_html__('%title', 'auriane'));
                        else :
                            previous_post_link('<span class="left"> <i class="fas fa-caret-left"></i> %link </span>', 'PREV' , 'auriane');    
                            next_post_link('<span class="right"> %link <i class="fas fa-caret-right"></i> </span>', 'NEXT', 'auriane');
                        endif;
                        ?>
                    </div>
                    <!-- .nav-links -->
</nav><!-- .navigation -->
 <div class='wrap'>
          <h2>Settings</h2>
          <form method='post' action='options.php'>
          <?php 
               /* 'option_group' must match 'option_group' from register_setting call */
               settings_fields( 'option_group' );
               do_settings_sections( 'section_page_type' );
          ?>
               <?php submit_button(); ?>
          </form>
     </div>
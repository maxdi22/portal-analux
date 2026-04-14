<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
} 
/*
 * Saves new field to postmeta for navigation
 */
add_filter('wp_nav_menu_args', 'jws_modify_arguments', 100);
function jws_modify_arguments($arguments)
{
    $arguments['walker'] = new HeroMenuWalker();
    return $arguments;
}

add_action('wp_update_nav_menu_item', 'jws_custom_nav_update', 10, 3);
function jws_custom_nav_update($menu_id, $menu_item_db_id, $args)
{
    $fields = array('submenu_position', 'submenu_type', 'dropdown', 'widget_area', 'submenu_size', 'column_width', 'group', 'hide_link', 'bg_image', 'menu_icon','brand','brandbg');
    foreach ($fields as $i => $field) {
        if (isset($_REQUEST['menu-item-' . $field][$menu_item_db_id])) {
            $mega_value = $_REQUEST['menu-item-' . $field][$menu_item_db_id];
            update_post_meta($menu_item_db_id, '_menu_item_' . $field, $mega_value);
        }
    }
}

/*
 * Adds value of new field to $item object that will be passed to     Walker_Nav_Menu_Edit_Custom
 */
add_filter('wp_setup_nav_menu_item', 'jws_custom_nav_item');
function jws_custom_nav_item($menu_item)
{
    $fields = array('submenu_position', 'submenu_type', 'dropdown', 'widget_area', 'submenu_size', 'column_width', 'group', 'hide_link', 'bg_image', 'menu_icon','brand','brandbg');
    foreach ($fields as $i => $field) {
        $menu_item->$field = get_post_meta($menu_item->ID, '_menu_item_' . $field, true);
    }
    return $menu_item;
}

add_action('admin_enqueue_scripts', 'jws_add_js_mega_menu');
function jws_add_js_mega_menu()
{

    wp_enqueue_media();
    add_thickbox();
}

add_filter('wp_edit_nav_menu_walker', 'jws_custom_nav_edit_walker', 10, 2);
function jws_custom_nav_edit_walker($walker, $menu_id)
{
    return 'Walker_Nav_Menu_Edit_Custom';
}

/**
 * Create HTML list of nav menu input items.
 *
 * @package WordPress
 * @since 3.0.0
 * @uses Walker_Nav_Menu
 */
class Walker_Nav_Menu_Edit_Custom extends Walker_Nav_Menu
{
    /**
     * @see Walker_Nav_Menu::start_lvl()
     * @since 3.0.0
     *
     * @param string $output Passed by reference.
     */
    function start_lvl(&$output, $depth = 0, $args = array())
    {
    }

    /**
     * @see Walker_Nav_Menu::end_lvl()
     * @since 3.0.0
     *
     * @param string $output Passed by reference.
     */
    function end_lvl(&$output, $depth = 0, $args = array())
    {
    }

    /**
     * @see Walker::start_el()
     * @since 3.0.0
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param object $item Menu item data object.
     * @param int $depth Depth of menu item. Used for padding.
     * @param object $args
     */
    function start_el(&$output, $item, $depth = 0, $args = array(), $current_object_id = 0)
    {
        global $_wp_nav_menu_max_depth;
        $_wp_nav_menu_max_depth = $depth > $_wp_nav_menu_max_depth ? $depth : $_wp_nav_menu_max_depth;

        $indent = ($depth) ? str_repeat("\t", $depth) : '';

        ob_start();
        $item_id = esc_attr($item->ID);
        $removed_args = array(
            'action',
            'customlink-tab',
            'edit-menu-item',
            'menu-item',
            'page-tab',
            '_wpnonce',
        );

        $original_title = '';
        if ('taxonomy' == $item->type) {
            $original_title = get_term_field('name', $item->object_id, $item->object, 'raw');
            if (is_wp_error($original_title))
                $original_title = false;
        } elseif ('post_type' == $item->type) {
            $original_object = get_post($item->object_id);
            $original_title = $original_object->post_title;
        }

        $classes = array(
            'menu-item menu-item-depth-' . $depth,
            'menu-item-' . esc_attr($item->object),
            'menu-item-edit-' . ((isset($_GET['edit-menu-item']) && $item_id == $_GET['edit-menu-item']) ? 'active' : 'inactive'),
        );

        $title = $item->title;

        if (!empty($item->_invalid)) {
            $classes[] = 'menu-item-invalid';
            /* translators: %s: title of menu item which is invalid */
            $title = sprintf(esc_html__('%s (Invalid)', 'auriane'), $item->title);
        } elseif (isset($item->post_status) && 'draft' == $item->post_status) {
            $classes[] = 'pending';
            /* translators: %s: title of menu item in draft status */
            $title = sprintf(esc_html__('%s (Pending)', 'auriane'), $item->title);
        }

        $title = empty($item->label) ? $title : $item->label;

        ?>
    <li data-menuanchor="" id="menu-item-<?php echo esc_attr($item_id); ?>"
        class="<?php echo implode(' ', $classes); ?>">
        <dl class="menu-item-bar">
            <dt class="menu-item-handle">
                <span class="item-title"><?php echo esc_html($title); ?></span>
                <span class="item-controls">
                    <span class="item-type"><?php echo esc_html($item->type_label); ?></span>
                    <span class="item-order hide-if-js">
                        <a href="<?php
                        echo wp_nonce_url(
                            add_query_arg(
                                array(
                                    'action' => 'move-up-menu-item',
                                    'menu-item' => $item_id,
                                ),
                                remove_query_arg($removed_args, admin_url('nav-menus.php'))
                            ),
                            'move-menu_item'
                        );
                        ?>" class="item-move-up"><abbr title="<?php esc_attr_e('Move up', 'auriane'); ?>">&#8593;</abbr></a>
                        |
                        <a href="<?php
                        echo wp_nonce_url(
                            add_query_arg(
                                array(
                                    'action' => 'move-down-menu-item',
                                    'menu-item' => $item_id,
                                ),
                                remove_query_arg($removed_args, admin_url('nav-menus.php'))
                            ),
                            'move-menu_item'
                        );
                        ?>" class="item-move-down"><abbr
                                    title="<?php esc_attr_e('Move down', 'auriane'); ?>">&#8595;</abbr></a>
                    </span>
                    <a class="item-edit" id="edit-<?php echo esc_attr($item_id); ?>"
                       title="<?php esc_attr_e('Edit Menu Item', 'auriane'); ?>" href="<?php
                    echo (isset($_GET['edit-menu-item']) && $item_id == $_GET['edit-menu-item']) ? admin_url('nav-menus.php') : add_query_arg('edit-menu-item', $item_id, remove_query_arg($removed_args, admin_url('nav-menus.php#menu-item-settings-' . $item_id)));
                    ?>"></a>
                </span>
            </dt>
        </dl>

        <div class="menu-item-settings" id="menu-item-settings-<?php echo esc_attr($item_id); ?>">
            <?php if ('custom' == $item->type) : ?>
                <p class="field-url description description-wide">
                    <label for="edit-menu-item-url-<?php echo esc_attr($item_id); ?>">
                        <?php esc_html_e('URL', 'auriane'); ?><br/>
                        <input type="text" id="edit-menu-item-url-<?php echo esc_attr($item_id); ?>"
                               class="widefat code edit-menu-item-url"
                               name="menu-item-url[<?php echo esc_attr($item_id); ?>]"
                               value="<?php echo esc_attr($item->url); ?>"/>
                    </label>
                </p>
            <?php endif; ?>
            <p class="description description-thin">
                <label for="edit-menu-item-title-<?php echo esc_attr($item_id); ?>">
                    <?php esc_html_e('Navigation Label', 'auriane'); ?><br/>
                    <input type="text" id="edit-menu-item-title-<?php echo esc_attr($item_id); ?>"
                           class="widefat edit-menu-item-title"
                           name="menu-item-title[<?php echo esc_attr($item_id); ?>]"
                           value="<?php echo esc_attr($item->title); ?>"/>
                </label>
            </p>
            <p class="description description-thin">
                <label for="edit-menu-item-attr-title-<?php echo esc_attr($item_id); ?>">
                    <?php esc_html_e('Title Attribute', 'auriane'); ?><br/>
                    <input type="text" id="edit-menu-item-attr-title-<?php echo esc_attr($item_id); ?>"
                           class="widefat edit-menu-item-attr-title"
                           name="menu-item-attr-title[<?php echo esc_attr($item_id); ?>]"
                           value="<?php echo esc_attr($item->post_excerpt); ?>"/>
                </label>
            </p>
            <p class="field-link-target description">
                <label for="edit-menu-item-target-<?php echo esc_attr($item_id); ?>">
                    <input type="checkbox" id="edit-menu-item-target-<?php echo esc_attr($item_id); ?>" value="_blank"
                           name="menu-item-target[<?php echo esc_attr($item_id); ?>]"<?php checked($item->target, '_blank'); ?> />
                    <?php esc_html_e('Open link in a new window/tab', 'auriane'); ?>
                </label>
            </p>
            <p class="field-css-classes description description-thin">
                <label for="edit-menu-item-classes-<?php echo esc_attr($item_id); ?>">
                    <?php esc_html_e('CSS Classes (optional)', 'auriane'); ?><br/>
                    <input type="text" id="edit-menu-item-classes-<?php echo esc_attr($item_id); ?>"
                           class="widefat code edit-menu-item-classes"
                           name="menu-item-classes[<?php echo esc_attr($item_id); ?>]"
                           value="<?php echo esc_attr(implode(' ', $item->classes)); ?>"/>
                </label>
            </p>
            <p class="field-xfn description description-thin">
                <label for="edit-menu-item-xfn-<?php echo esc_attr($item_id); ?>">
                    <?php esc_html_e('Link Relationship (XFN)', 'auriane'); ?><br/>
                    <input type="text" id="edit-menu-item-xfn-<?php echo esc_attr($item_id); ?>"
                           class="widefat code edit-menu-item-xfn"
                           name="menu-item-xfn[<?php echo esc_attr($item_id); ?>]"
                           value="<?php echo esc_attr($item->xfn); ?>"/>
                </label>
            </p>
            
            <p class="field-css-brand description description-thin">
                <label for="edit-menu-item-brand-<?php echo esc_attr($item_id); ?>">
                    <?php esc_html_e('Brand Label Ex( Hot , New ...)', 'auriane'); ?><br/>
                    <input type="text" id="edit-menu-item-brand-<?php echo esc_attr($item_id); ?>"
                           class="widefat code edit-menu-item-brand"
                           name="menu-item-brand[<?php echo esc_attr($item_id); ?>]"
                           value="<?php echo esc_attr($item->brand); ?>"/>
                </label>
            </p>

            <p class="field-brandbg description description-thin">
                <label for="edit-menu-item-brandbg-<?php echo esc_attr($item_id); ?>">
                    <?php esc_html_e('Brand Bg Ex(#000000 , red)', 'auriane'); ?><br/>
                    <input type="text" id="edit-menu-item-brandbg-<?php echo esc_attr($item_id); ?>"
                           class="widefat code edit-menu-item-brandbg"
                           name="menu-item-brandbg[<?php echo esc_attr($item_id); ?>]"
                           value="<?php echo esc_attr($item->brandbg); ?>"/>
                </label>
            </p>
            
            
            
            <?php if (!$depth) { ?>
                <p class="field-description description description-wide">
                    <label for="edit-menu-item-description-<?php echo esc_attr($item_id); ?>">
                        <?php esc_html_e('Add Shortcode for mega menu', 'auriane'); ?><br/>
                        <textarea id="edit-menu-item-description-<?php echo esc_attr($item_id); ?>"
                                  class="widefat edit-menu-item-description" rows="3" cols="20"
                                  name="menu-item-description[<?php echo esc_attr($item_id); ?>]"><?php echo esc_html($item->description); ?></textarea>
                    </label>
                </p>
            <?php }
            /*
             * This is the added field
             */
            if (!$depth) {
                $title = 'Submenu Type';
                $key = "menu-item-submenu_type";
                $value = $item->submenu_type;
                ?>
                <p class="description description-wide jws-design">
                    <?php echo esc_html($title); ?><br/>
                    <label for="edit-<?php echo esc_attr($key . '-' . $item_id); ?>">
                        <select id="edit-<?php echo esc_attr($key . '-' . $item_id); ?>"
                                class=" <?php echo esc_attr($key); ?>"
                                name="<?php echo esc_attr($key . "[" . $item_id . "]"); ?>">
                            <option value="standard" <?php echo ''.($value == 'standard') ? ' selected="selected" ' : ''; ?>><?php esc_html_e('Standard Dropdown', 'auriane'); ?></option>
                            <option value="mega_menu" <?php echo ''.($value == 'mega_menu') ? ' selected="selected" ' : ''; ?>><?php esc_html_e('Mega With Set Size', 'auriane'); ?></option>
                            <option value="mega_menu_full_width" <?php echo ''.($value == 'mega_menu_full_width') ? ' selected="selected" ' : ''; ?>><?php esc_html_e('Mega Full Width', 'auriane'); ?></option>
                        </select>
                    </label>
                </p>
                <?php
            }
          
            if (!$depth) {
                ?>
                <p class="field-submenu_size description description-thin jws-width">
                    <label for="edit-menu-item-submenu_size-<?php echo esc_attr($item_id); ?>">
                        <?php esc_html_e('Add Width Mega Menu ', 'auriane'); ?><br/>
                        <input type="text" id="edit-menu-item-submenu_size-<?php echo esc_attr($item_id); ?>"
                               class="widefat code edit-menu-item-submenu_size"
                               name="menu-item-submenu_size[<?php echo esc_attr($item_id); ?>]"
                               value="<?php echo esc_attr($item->submenu_size); ?>"/>
                    </label>
                </p>
                <?php
            }
              /*
            * This is the added field align sub menu
            */
            if (!$depth) {
                $title = 'Position Menu';
                $key = "menu-item-submenu_position";
                $value = $item->submenu_position;
                ?>
                <p class="description-thin jws-position">
                    <?php echo esc_html($title); ?><br/>
                    <label for="edit-<?php echo esc_attr($key . '-' . $item_id); ?>">
                        <select id="edit-<?php echo esc_attr($key . '-' . $item_id); ?>"
                                class=" <?php echo esc_attr($key); ?>"
                                name="<?php echo esc_attr($key . "[" . $item_id . "]"); ?>">
                            <option value="center" <?php echo ''.($value == 'center') ? ' selected="selected" ' : ''; ?>><?php esc_html_e('Center', 'auriane'); ?></option>
                            <option value="left" <?php echo ''.($value == 'left') ? ' selected="selected" ' : ''; ?>><?php esc_html_e('Left', 'auriane'); ?></option>
                            <option value="right" <?php echo ''.($value == 'right') ? ' selected="selected" ' : ''; ?>><?php esc_html_e('Right', 'auriane'); ?></option>
                        </select>
                    </label>
                </p>
                <?php
            }
            ?>

            <div class="menu-item-actions description-wide submitbox">
                <?php if ('custom' != $item->type && $original_title !== false) : ?>
                    <p class="link-to-original">
                        <?php printf(esc_html__('Original: %s', 'auriane'), '<a href="' . esc_attr($item->url) . '">' . esc_html($original_title) . '</a>'); ?>
                    </p>
                <?php endif; ?>
                <a class="item-delete submitdelete deletion" id="delete-<?php echo esc_attr($item_id); ?>" href="<?php
                echo wp_nonce_url(
                    add_query_arg(
                        array(
                            'action' => 'delete-menu-item',
                            'menu-item' => $item_id,
                        ),
                        remove_query_arg($removed_args, admin_url('nav-menus.php'))
                    ),
                    'delete-menu_item_' . esc_attr($item_id)
                ); ?>"><?php esc_html_e('Remove', 'auriane'); ?></a> <span class="meta-sep"> | </span> <a
                        class="item-cancel submitcancel" id="cancel-<?php echo esc_attr($item_id); ?>"
                        href="<?php echo esc_url(add_query_arg(array('edit-menu-item' => $item_id, 'cancel' => time()), remove_query_arg($removed_args, admin_url('nav-menus.php'))));
                        ?>#menu-item-settings-<?php echo esc_attr($item_id); ?>"><?php esc_html_e('Cancel', 'auriane'); ?></a>
            </div>


            <input class="menu-item-data-db-id" type="hidden" name="menu-item-db-id[<?php echo esc_attr($item_id); ?>]"
                   value="<?php echo esc_attr($item_id); ?>"/>
            <input class="menu-item-data-object-id" type="hidden"
                   name="menu-item-object-id[<?php echo esc_attr($item_id); ?>]"
                   value="<?php echo esc_attr($item->object_id); ?>"/>
            <input class="menu-item-data-object" type="hidden"
                   name="menu-item-object[<?php echo esc_attr($item_id); ?>]"
                   value="<?php echo esc_attr($item->object); ?>"/>
            <input class="menu-item-data-parent-id" type="hidden"
                   name="menu-item-parent-id[<?php echo esc_attr($item_id); ?>]"
                   value="<?php echo esc_attr($item->menu_item_parent); ?>"/>
            <input class="menu-item-data-position" type="hidden"
                   name="menu-item-position[<?php echo esc_attr($item_id); ?>]"
                   value="<?php echo esc_attr($item->menu_order); ?>"/>
            <input class="menu-item-data-type" type="hidden" name="menu-item-type[<?php echo esc_attr($item_id); ?>]"
                   value="<?php echo esc_attr($item->type); ?>"/>
        </div><!-- .menu-item-settings-->
        <ul class="menu-item-transport"></ul>
        <?php
        $output .= ob_get_clean();
    }
}

class HeroMenuWalker extends Walker_Nav_Menu
{
    private $type_sub = 'sub_no_container';

    /**
     * Starts the list before the elements are added.
     *
     * @see Walker::start_lvl()
     *
     * @since 3.0.0
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param int $depth Depth of menu item. Used for padding.
     * @param array $args An array of arguments. @see wp_nav_menu()
     */
    public function start_lvl(&$output, $depth = 0, $args = array())
    {
        $indent = str_repeat("\t", $depth);
        $sub_position = $this->type_position;
        $sub_type = $this->type_sub;
        $class_ss = '';
        if ($depth < 1) {
            $class_ss = " no_shortcode";
        }
        if ($sub_type == 'mega_menu') {
            $this->type_sub = 'sub_mega_menu';
        } elseif ($sub_type == 'mega_menu_full_width') {
            $this->type_sub = 'sub_mega_menu_full_width';
        } else {
            $this->type_sub = 'sub_standard';
        }


        $sub_menu_class = "sub-menu";


        $output .= "\n$indent<ul class=\"$sub_menu_class\">\n";


    }

    /**
     * Ends the list of after the elements are added.
     *
     * @see Walker::end_lvl()
     *
     * @since 3.0.0
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param int $depth Depth of menu item. Used for padding.
     * @param array $args An array of arguments. @see wp_nav_menu()
     */
    public function end_lvl(&$output, $depth = 0, $args = array())
    {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent</ul>\n";
    }

    /**
     * Start the element output.
     *
     * @see Walker::start_el()
     *
     * @since 3.0.0
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param object $item Menu item data object.
     * @param int $depth Depth of menu item. Used for padding.
     * @param array $args An array of arguments. @see wp_nav_menu()
     * @param int $id Current item ID.
     */
    public function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0)
    {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';

        $classes = empty($item->classes) ? array() : (array)$item->classes;
        $classes[] = 'menu-item-' . $item->ID;

        $image = $label = $label_out = '';
        $width = get_post_meta($item->ID, '_menu_item_submenu_size', true);
        $icon = get_post_meta($item->ID, '_menu_item_menu_icon', true);
        $event = get_post_meta($item->ID, '_menu_item_event', true);
        $label = get_post_meta($item->ID, '_menu_item_label', true);
        $opanchor = get_post_meta($item->ID, '_menu_item_opanchor', true);
        $callbtn = get_post_meta($item->ID, '_menu_item_callbtn', true);
        $sub_type = get_post_meta($item->ID, '_menu_item_submenu_type', true);
        $sub_position = get_post_meta($item->ID, '_menu_item_submenu_position', true);
        $image = get_post_meta($item->ID, '_menu_item_bg_image', true);
        $brand = get_post_meta($item->ID, '_menu_item_brand', true);
        $brandbg = get_post_meta($item->ID, '_menu_item_brandbg', true);
        if ($sub_type == 'mega_menu') {
            $this->type_sub = 'mega_menu';
        } elseif ($sub_type == 'mega_menu_full_width') {
            $this->type_sub = 'mega_menu_full_width';
        } else {
            $this->type_sub = 'sub_standard';
        }


        if (empty($sub_type)) $sub_type = 'standard';
        $classes[] = 'menu-item-design-' . $sub_type;

    
        if($sub_type == 'mega_menu') {
           if ($sub_position == 'left') {
                $this->type_position = 'left';
            } elseif ($sub_position == 'right') {
                $this->type_position = 'right';
            } else {
                $this->type_position = 'center';
            } 
            if (empty($sub_position)) $sub_position = 'center';
            $classes[] = 'menu-item-position-' . $sub_position;
        }
        else {
           $this->type_position = ''; 
        }
        
        if(!empty($item->description)) {
           $classes[] = 'menu_has_shortcode'; 
        }

        if ($opanchor == 'enable') {
            $classes[] = 'onepage-link';
            if (($key = array_search('current-menu-item', $classes)) !== false) {
                unset($classes[$key]);
            }
        }

        if ($callbtn == 'enable') {
            $classes[] = 'callto-btn';
        }
       if (!empty($brand)) { 
            $classes[] = 'item-with-label';
            $label_out = '<span class="menu-label">' . esc_attr($brand) . '</span>';   
        }


        /**
         * Filter the CSS class(es) applied to a menu item's list item element.
         *
         * @since 3.0.0
         * @since 4.1.0 The `$depth` parameter was added.
         *
         * @param array $classes The CSS classes that are applied to the menu item's `<li>` element.
         * @param object $item The current menu item.
         * @param array $args An array of {@see wp_nav_menu()} arguments.
         * @param int $depth Depth of menu item. Used for padding.
         */
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

        /**
         * Filter the ID applied to a menu item's list item element.
         *
         * @since 3.0.1
         * @since 4.1.0 The `$depth` parameter was added.
         *
         * @param string $menu_id The ID that is applied to the menu item's `<li>` element.
         * @param object $item The current menu item.
         * @param array $args An array of {@see wp_nav_menu()} arguments.
         * @param int $depth Depth of menu item. Used for padding.
         */
        $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';

        $output .= $indent . '<li' . $id . $class_names . '>';

        $atts = array();
        $atts['title'] = !empty($item->attr_title) ? $item->attr_title : '';
        $atts['target'] = !empty($item->target) ? $item->target : '';
        $atts['rel'] = !empty($item->xfn) ? $item->xfn : '';
        $atts['href'] = !empty($item->url) ? $item->url : '';

        /**
         * Filter the HTML attributes applied to a menu item's anchor element.
         *
         * @since 3.6.0
         * @since 4.1.0 The `$depth` parameter was added.
         *
         * @param array $atts {
         *     The HTML attributes applied to the menu item's `<a>` element, empty strings are ignored.
         *
         * @type string $title Title attribute.
         * @type string $target Target attribute.
         * @type string $rel The rel attribute.
         * @type string $href The href attribute.
         * }
         * @param object $item The current menu item.
         * @param array $args An array of {@see wp_nav_menu()} arguments.
         * @param int $depth Depth of menu item. Used for padding.
         */
        $atts = apply_filters('nav_menu_link_attributes', $atts, $item, $args, $depth);

        $attributes = '';
        foreach ($atts as $attr => $value) {
            if (!empty($value)) {
                $value = ('href' === $attr) ? esc_url($value) : esc_attr($value);
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }
        $item_output = '';
      
        $item_output .= '<a' . $attributes . '>';
        if($depth != 0 && $icon != '') {
            $item_output .= '<i class="' . $icon . '"></i>';
        }
        /** This filter is documented in wp-includes/post-template.php */
        $item_output .= '<span>'.apply_filters('the_title', $item->title, $item->ID).'</span>';
        $item_output .= $label_out;
         if(is_array($item->classes) && $depth == 0 && (in_array("menu-item-has-children", $item->classes) || !empty($item->description) )) {
            $item_output .= '<i class="jws-icon-caret-down-thin"></i>';
        }
        $item_output .= '</a>';
      

        $styles = '';

        if ($depth == 0) {
            /**
             * Add background image to dropdown
             **/


            if ((!empty($item->description) && !in_array("menu-item-has-children", $item->classes)) && ($sub_type == 'mega_menu' || $sub_type == 'mega_menu_full_width')) {
                $item_output .= "\n$indent<div class=\"sub-menu-dropdown " . $this->type_sub . " " . $this->type_position . "\"><div class='jws_mega_sub'>\n";
                $item_output .= do_shortcode($item->description);
                $item_output .= "\n$indent</div></div>\n";


            }
        }

        if (!empty($width)) {
            $styles .= '.menu-item-' . $item->ID . ' > .sub-menu-dropdown {';
            $styles .= 'width: ' . $width . 'px !important; ';
            $styles .= '}'; 
        }
        
        if (!empty($brand)) {
            $bglabel = (!empty($brandbg)) ? $brandbg : '#000000';
            $styles .= '.menu-item-' . $item->ID . '  .menu-label {';
            $styles .= 'background: ' . $bglabel . '; ';
            $styles .= '}'; 
            $styles .= '.menu-item-' . $item->ID . '  .menu-label:before {';
            $styles .= 'border-color: ' . $bglabel . ' !important; ';
            $styles .= '}'; 
        }
            
        if ($styles != '') {
            $item_output .= '<style type="text/css">';
            $item_output .= $styles;
            $item_output .= '</style>';
        }

        /**
         * Filter a menu item's starting output.
         *
         * The menu item's starting output only includes `$args->before`, the opening `<a>`,
         * the menu item's title, the closing `</a>`, and `$args->after`. Currently, there is
         * no filter for modifying the opening and closing `<li>` for a menu item.
         *
         * @since 3.0.0
         *
         * @param string $item_output The menu item's starting HTML output.
         * @param object $item Menu item data object.
         * @param int $depth Depth of menu item. Used for padding.
         * @param array $args An array of {@see wp_nav_menu()} arguments.
         */
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
}

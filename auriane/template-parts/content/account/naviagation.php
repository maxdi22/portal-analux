<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
$user  = wp_get_current_user();

?>

<div class="jws-user-private-sidebar">

    <div class="clearfix jws-user-top">

        <div class="jws-user-avatar">
            <?php
            $link = add_query_arg(array('page_admin' => 'settings'), jws_get_author_link(''));

            $hide_empty = '';
            if (!empty($user_fields['image'])) {
                $hide_empty = 'hide-empty';
            } else {
                $hide_empty = 'hide-photo';
            } ?>

            <a href="<?php echo esc_url($link); ?>" class="jws-image-avatar image <?php echo esc_attr($hide_empty); ?>">
                <img class="img-responsive img-avatar" src="<?php echo esc_url($user_fields['image']); ?>"/>
                <div class="jws-empty-avatar-icon"><i class="fa fa-camera"></i></div>
            </a>
        </div>

        <div class="jws-user-profile-information">
            <a href="<?php echo esc_url($link); ?>"
               class="title heading-font"><?php echo esc_html($user->user_login); ?></a>
            <div class="title-sub"><?php esc_html_e('Private Seller', 'auriane'); ?></div>
            <?php if (!empty($user_fields['socials'])): ?>
                <div class="socials clearfix">
                    <?php foreach ($user_fields['socials'] as $social_key => $social): ?>
                        <a href="<?php echo esc_url($social); ?>">
                            <?php
                            if ($social_key == 'youtube') {
                                $social_key = 'youtube-play';
                            }
                            ?>
                            <i class="fa fa-<?php echo esc_attr($social_key); ?>"></i>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

    </div>

    <div class="jws-actions-list heading-font">

        <a
            href="<?php echo esc_url(jws_get_author_link('')); ?>">
            <i class="fa fa-car"></i><?php esc_html_e('My Inventory', 'auriane') ?>
        </a>

        <a
            href="<?php echo esc_url(add_query_arg(array('page_admin' => 'settings'), jws_get_author_link(''))); ?>">
            <i class="fa fa-cog"></i>
            <?php esc_html_e('Profile Settings', 'auriane') ?>
        </a>

    </div>

    <?php if (!empty($user_fields['phone'])): ?>
        <div class="jws-dealer-phone">
            <i class="fa fa-phone"></i>
            <div
                class="phone-label heading-font"><?php esc_html_e('Seller Contact Phone', 'auriane'); ?></div>
            <div class="phone"><?php echo esc_attr($user_fields['phone']); ?></div>
        </div>
    <?php endif; ?>

    <div class="jws-dealer-mail">
        <i class="fa fa-envelope-o"></i>
        <div class="mail-label heading-font"><?php esc_html_e('Seller Email', 'auriane'); ?></div>
        <div class="mail"><a href="mailto:<?php echo esc_attr($user->data->user_email); ?>">
                <?php echo esc_attr($user->data->user_email); ?>
            </a></div>
    </div>


    <div class="show-my-profile">
        <a href="<?php echo esc_url(jws_get_author_link('myself-view')); ?>" target="_blank"><i
                class="fa fa-external-link"></i><?php esc_html_e('Show my Public Profile', 'auriane'); ?>
        </a>
    </div>

</div>
<?php
global $return_action;
global $postas;
check_permissions(array('contributor'));
get_header(); ?>
<!-- section -->
<?php $user = new Theme\User(); ?>
<?php
$helper = new Theme\Helper();
?>
<div id="page">
    <div class="sidebar-with-content">
        <div class="article">
            <div id="content_box">
                <!-- change image -->
                <h3 class="featured-category-title"><?php _e('User profile') ?></h3>
                <div class="form_block">
                    <h6><?php _e('Profile image') ?></h6>
                    <?php
                    if (isset($return_action['profile_image'])) {
                        $helper->showMessages($return_action['profile_image']);
                    } ?>
                    <img src="<?php echo $user->getImageUrl(); ?>" alt="">
                    <form action="<?php echo get_permalink(147); ?>" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="controller" value="change_profile_image">
                        <ul>
                            <li>
                                <dl>
                                    <dt>
                                        <label for="profile_image"><?php _e('Profile image'); ?></label>
                                    </dt>
                                    <dd>
                                        <input id="profile_image" type="file" name="profile_image"/>
                                    </dd>
                                </dl>
                            </li>
                        </ul>
                        <div class="button_wrap">
                            <button><?php _e('Save'); ?></button>
                        </div>
                    </form>
                </div>
                <!-- change user info -->
                <div class="form_block">
                    <h6><?php _e('User info') ?></h6>
                    <form action="<?php echo get_permalink(147); ?>" method="post">
                        <!-- - upload image

                         - Tittel (title; input field)
                         - Epost (email; input field)
                         - Favorittspill (favorite game; input field)
                         - Beskrivelse (description; text area) -->
                        <?php
                        if (isset($return_action['user_info'])) {
                            $helper->showMessages($return_action['user_info']);
                        } ?>
                        <input type="hidden" name="controller" value="change_user_info">
                        <ul>
                            <li>
                                <dl>
                                    <dt>
                                        <label for="profile_name"><?php _e('Profile name'); ?></label>
                                    </dt>
                                    <dd>
                                        <input type="text" id="profile_name" name="profile_name" value="<?php echo (isset($postas['profile_name'])) ? $postas['profile_name'] : $user->getName(); ?>"/>
                                    </dd>
                                </dl>
                            </li>
                            <li>
                                <dl>
                                    <dt>
                                        <label for="profile_occupation"><?php _e('Occupation'); ?></label>
                                    </dt>
                                    <dd>
                                        <input type="text" id="profile_occupation" name="profile_occupation" value="<?php echo (isset($postas['profile_occupation'])) ? $postas['profile_occupation'] : $user->getUserOccupation(); ?>" />
                                    </dd>
                                </dl>
                            </li>
                            <li>
                                <dl>
                                    <dt>
                                        <label for="profile_emai"><?php _e('Profile email'); ?></label>
                                    </dt>
                                    <dd>
                                        <input type="email" id="profile_emai" name="profile_emai" value="<?php echo (isset($postas['profile_emai'])) ? $postas['profile_emai'] : $user->getEmail(); ?>"/>
                                    </dd>
                                </dl>
                            </li>
                            <li>
                                <dl>
                                    <dt>
                                        <label for="profile_favorite"><?php _e('Favorite game'); ?></label>
                                    </dt>
                                    <dd>
                                        <textarea id="profile_favorite" name="profile_favorite"><?php echo (isset($postas['profile_favorite'])) ? $postas['profile_favorite'] : $user->getFavourite_Games(); ?></textarea>
                                    </dd>
                                </dl>
                            </li>
                            <li>
                                <dl>
                                    <dt>
                                        <label for="profile_description"><?php _e('Description'); ?></label>
                                    </dt>
                                    <dd>
                                        <textarea id="profile_description" name="profile_description"><?php echo (isset($postas['profile_description'])) ? $postas['profile_description'] : $user->getDescription(); ?></textarea>
                                    </dd>
                                </dl>
                            </li>
                        </ul>
                        <div class="button_wrap">
                            <button><?php _e('Save'); ?></button>
                        </div>
                    </form>
                </div>
                <!-- change password -->
                <div class="form_block">
                    <h6><?php _e('Change password') ?></h6>
                    <?php
                    if (isset($return_action['password'])) {
                        $helper->showMessages($return_action['password']);
                    } ?>
                    <form action="<?php echo get_permalink(147); ?>" method="post">
                        <input type="hidden" name="controller" value="change_password">
                        <ul>
                            <li>
                                <dl>
                                    <dt><label for="password"><?php _e('New password'); ?></label>
                                    </dt>
                                    <dd><input id="password" type="password" name="password"/>
                                    </dd>
                                </dl>
                            </li>
                            <li>
                                <dl>
                                    <dt><label for="password2"><?php _e('Repeat password'); ?></label>
                                    </dt>
                                    <dd><input id="password2" type="password" name="password2"/>
                                    </dd>
                                </dl>
                            </li>
                        </ul>
                        <div class="button_wrap">
                            <button><?php _e('Save'); ?></button>
                        </div>
                    </form>
                    <!-- /section -->
                </div>
            </div>
        </div>
    </div>
    <?php get_sidebar(); ?>
</div>
<?php get_footer(); ?>

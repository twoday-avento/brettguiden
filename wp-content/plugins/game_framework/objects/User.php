<?php
namespace Theme;

class User {
    private $user;

    public function __construct($user_id = null) {
        if (!$user_id) {
            $user_id = get_current_user_id();
        }
        $this->setUser($user_id);
    }

    public function getId() {
        $user = $this->getUser();
        return $user->ID;
    }

    public function getUser() {
        return $this->user;
    }

    public function setUser($user) {
        if (is_numeric($user)) {
            $user = get_userdata($user);
        }
        $this->user = $user;
        return $this;
    }

    public function getName() {
        $user = $this->getUser();

        return trim($user->display_name);
    }

    public function getUserOccupation() {
        $data = get_user_meta($this->getId(), 'wpcf-occupation', true);
        if(!get_user_meta($this->getId(), 'wpcf-occupation', true)){
            return null;
        }
        return trim($data);
    }

    public function getEmail() {
        $user = $this->getUser();

        return $user->user_email;
    }

    public function getUserRoles() {
        $user = $this->getUser();
        if (!$user) {
            return null;
        }
        return $user->roles;
    }

    public function isAdmin() {
        if (!$this->getUser()) {
            return false;
        }
        if (in_array('administrator', $this->getUserRoles())) {
            return true;
        }
        return false;
    }

    public function getImageUrl() {
        $user = $this->getUser();
        $profile_post_id = absint(get_user_option('metronet_post_id', $user->ID));
        if (has_post_thumbnail($profile_post_id)) {
            $post_thumbnail_id = get_post_thumbnail_id($profile_post_id);

            $attachment = wp_get_attachment_image_src($post_thumbnail_id, 'user_image');
            $attachment_url = $attachment[0];

            return $attachment_url;
        } else {
            // to-do: set no image
            return null;
        }
    }

    static function changeProfileImage() {
        $attach_id = null;
        $post_id = null;
        $user_id = get_current_user_id();
        if (!function_exists('wp_generate_attachment_metadata')) {
            require_once(ABSPATH . "wp-admin" . '/includes/image.php');
            require_once(ABSPATH . "wp-admin" . '/includes/file.php');
            require_once(ABSPATH . "wp-admin" . '/includes/media.php');
        }
        if ($_FILES) {
            foreach ($_FILES as $file => $array) {
                if ($_FILES[$file]['error'] !== UPLOAD_ERR_OK) {
                    return "upload error : " . $_FILES[$file]['error'];
                }
                $attach_id = media_handle_upload($file, null);
            }
        }

        if ($attach_id > 0) {

            //Get/Create Profile Picture Post
            $post_args = array(
                'post_type' => 'mt_pp',
                'author' => $user_id,
                'post_status' => 'publish'
            );
            $posts = get_posts($post_args);
            if (!$posts) {
                $post_id = wp_insert_post(array(
                    'post_author' => $user_id,
                    'post_type' => 'mt_pp',
                    'post_status' => 'publish',
                ));
            } else {
                $post = end($posts);
                $post_id = $post->ID;
            }

            update_user_option($user_id, 'metronet_post_id', $post_id);
            update_user_option($user_id, 'metronet_image_id', $attach_id); //Added via this thread (Props Solinx) - https://wordpress.org/support/topic/storing-image-id-directly-as-user-meta-data
            set_post_thumbnail($post_id, $attach_id);
        }
    }

    static function changePassword() {
        global $postas;
        $postas = $_POST;
        $return['password']['error'] = null;
        $return['password']['success'] = false;
        $user_id = get_current_user_id();
        $password = $postas['password'];
        $password2 = $postas['password2'];

        if (!$user_id) {
            $return['password']['error'] = __('User error');
            return $return;
        }
        if ($password !== $password2) {
            $return['password']['error'] = __('Confirm password error');
            return $return;
        }
        if (strlen($password) < 8) {
            $return['password']['error'] = __('Password to short');
            return $return;
        }

        wp_set_password($password, $user_id);
        $return['password']['success'] = true;
        $return['password']['message'] = __('Password changed');

        return $return;
    }

    static function changeUserInfo() {
        global $postas;

        $return['user_info']['error'] = null;
        $return['user_info']['success'] = false;
        $user_id = get_current_user_id();

        $postas = $_POST;

        if (isset($postas['profile_name']) && !empty($postas['profile_name'])) {
            $data = wp_update_user(array('ID' => $user_id, 'display_name' => $postas['profile_name']));
            if (is_wp_error($data)) {
                $return['user_info']['error'] = $data->errors;
                return $return;
            }
        }

        if (isset($postas['profile_emai']) && !empty($postas['profile_emai'])) {
            $email = $postas['profile_emai'];
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $return['user_info']['error'] = __('This email address is not valid.');
                return $return;
            }
            $data = wp_update_user(array('ID' => $user_id, 'user_email' => $email));

            if (is_wp_error($data)) {

                $return['user_info']['error'] = $data->errors;
                return $return;
            }
        }
        if (isset($postas['profile_favorite'])) {
            update_user_meta($user_id, 'wpcf-favourite-games', $postas['profile_favorite']);
        }
        if (isset($postas['profile_description'])) {
            update_user_meta($user_id, 'description', $postas['profile_description']);
        }
        if (isset($postas['profile_occupation'])) {
            update_user_meta($user_id, 'wpcf-occupation', $postas['profile_occupation']);
        }

        $return['user_info']['success'] = true;
        $return['user_info']['message'] = __('User info updated');

        return $return;
    }

    function getFavourite_Games() {
        return get_user_meta($this->getId(), 'wpcf-favourite-games', true);
    }

    function getDescription() {
        return get_user_meta($this->getId(), 'description', true);
    }

}
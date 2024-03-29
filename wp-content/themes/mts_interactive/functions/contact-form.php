<?php
/*-----------------------------------------------------------------------------------*/
/*	AJAX Contact Form - mts_contact_form()
/*-----------------------------------------------------------------------------------*/
class mtscontact {
    public $errors = array();
    public $userinput = array('name' => '', 'email' => '', 'message' => '');
    public $success = false;
    
    public function __construct() {
        add_action('wp_ajax_mtscontact', array($this, 'ajax_mtscontact'));
        add_action('wp_ajax_nopriv_mtscontact', array($this, 'ajax_mtscontact'));
        add_action('init', array($this, 'init'));
        add_action('wp_enqueue_scripts', array($this, 'register_scripts'));
    }
    public function ajax_mtscontact() {
        if ($this->validate()) {
            if ($this->send_mail()) {
                echo json_encode('success');
                wp_create_nonce( "mtscontact" ); // purge used nonce
            } else {
                // wp_mail() unable to send
                $this->errors['sendmail'] = __('En feil har skjedd, vennligst kontakt administrator.', 'mythemeshop');
                echo json_encode($this->errors);
            }
        } else {
            echo json_encode($this->errors);
        }
        die();
    }
    public function init() {
        // No-js fallback
        if ( !defined( 'DOING_AJAX' ) || !DOING_AJAX ) {
            if (!empty($_POST['action']) && $_POST['action'] == 'mtscontact') {
                if ($this->validate()) {
                    if (!$this->send_mail()) {
                        $this->errors['sendmail'] = __('En feil har skjedd, vennligst kontakt administrator.', 'mythemeshop');
                    } else {
                        $this->success = true;
                    }
                }
            }
        }
    }
    public function register_scripts() {
        wp_register_script('mtscontact', get_template_directory_uri() . '/js/contact.js', true);
        wp_localize_script('mtscontact', 'mtscontact', array('ajaxurl' => admin_url('admin-ajax.php')));
    }
    
    private function validate() {
        // check nonce
        if (!check_ajax_referer( 'mtscontact', 'mtscontact_nonce', false )) {
            $this->errors['nonce'] = __('Vennligst prøv igjen.', 'mythemeshop');
        }
        
        // check honeypot // must be empty
        if (!empty($_POST['mtscontact_captcha'])) {
            $this->errors['captcha'] = __('Vennligst prøv igjen.', 'mythemeshop');
        }
        
        // name field
        $name = trim(str_replace(array("\n", "\r", "<", ">"), '', strip_tags($_POST['mtscontact_name'])));
        if (empty($name)) {
            $this->errors['name'] = __('Skriv inn ditt navn.', 'mythemeshop');
        }
        
        // email field
        $useremail = trim($_POST['mtscontact_email']);
        if (!is_email($useremail)) {
            $this->errors['email'] = __('Skriv inn en gyldig e-post adresse.', 'mythemeshop');
        }
        
        // message field
        $message = strip_tags($_POST['mtscontact_message']);
        if (empty($message)) {
            $this->errors['message'] = __('Skriv inn en melding.', 'mythemeshop');
        }
        
        // store fields for no-js
        $this->userinput = array('name' => $name, 'email' => $useremail, 'message' => $message);
        
        return empty($this->errors);
    }
    private function send_mail() {
        $email_to = get_option('admin_email');
        $email_subject = __('Contact Form Message from', 'mythemeshop').' '.get_bloginfo('name');
        $email_message = __('Navn:', 'mythemeshop').' '.$this->userinput['name']."\n\n".
                         __('E-post:', 'mythemeshop').' '.$this->userinput['email']."\n\n".
                         __('Melding:', 'mythemeshop').' '.$this->userinput['message'];
        return wp_mail($email_to, $email_subject, $email_message);
    }
    public function get_form() {
        wp_enqueue_script('mtscontact');
        
        $return = '';
        if (!$this->success) {
            $return .= '<form method="post" action="" id="mtscontact_form" class="contact-form">
            <input type="text" name="mtscontact_captcha" value="" style="display: none;" />
            <input type="hidden" name="mtscontact_nonce" value="'.wp_create_nonce( "mtscontact" ).'" />
            <input type="hidden" name="action" value="mtscontact" />
            
            <label for="mtscontact_name">'.__('Navn', 'mythemeshop').'</label>
            <input type="text" name="mtscontact_name" value="'.esc_attr($this->userinput['name']).'" id="mtscontact_name" />
            
            <label for="mtscontact_email">'.__('E-post', 'mythemeshop').'</label>
            <input type="text" name="mtscontact_email" value="'.esc_attr($this->userinput['email']).'" id="mtscontact_email" />
            
            <label for="mtscontact_message">'.__('Melding', 'mythemeshop').'</label>
            <textarea name="mtscontact_message" id="mtscontact_message">'.esc_textarea($this->userinput['message']).'</textarea>
            
            <input type="submit" value="'.__('Sent', 'mythemeshop').'" id="mtscontact_submit" />
        </form>';
        }
        $return .= '<div id="mtscontact_success"'.($this->success ? '' : ' style="display: none;"').'>'.__('Din melding har blitt sendt.', 'mythemeshop').'</div>';
        return $return;
    }
    public function get_errors() {
        $html = '';
        foreach ($this->errors as $error) {
            $html .= '<div class="mtscontact_error">'.$error.'</div>';
        }
        return $html;
    }
}
$mtscontact = new mtscontact;

// Simple wrappers, to be used in template files
function mts_contact_form() {
    global $mtscontact;
    echo $mtscontact->get_errors(); // if there are any
    echo $mtscontact->get_form();
}
function mts_get_contact_form() { // this could be used for shortcode support
    global $mtscontact;
    return $mtscontact->get_errors() . $mtscontact->get_form();
}

?>
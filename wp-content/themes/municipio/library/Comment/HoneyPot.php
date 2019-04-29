<?php

namespace Municipio\Comment;

class HoneyPot
{
    protected $field_content;
    protected $field_name;

    public function __construct()
    {
        //Not in admin
        if (is_admin()) {
            return;
        }

        //Verification values
        $this->field_content = substr(md5(NONCE_SALT . NONCE_KEY), 5, 15);
        $this->field_name = substr(md5(AUTH_KEY), 5, 15);

        //Print frontend fields
        add_filter('comment_form_logged_in_after', array($this, 'addHoneyPotFieldFilled'));
        add_filter('comment_form_after_fields', array($this, 'addHoneyPotFieldFilled'));

        add_filter('comment_form_logged_in_after', array($this, 'addHoneyPotFieldBlank'));
        add_filter('comment_form_after_fields', array($this, 'addHoneyPotFieldBlank'));

        //Print cookie method
        add_action('init', array($this, 'fakeImage'));

        //Catch fields
        add_filter('preprocess_comment', array($this, 'honeyPotValidateFieldContent'));
        add_filter('preprocess_comment', array($this, 'fakeImageCookieCheck'));

        //Add styling to hide field
        add_filter('comment_form', array($this, 'printFakeHideBox'));
        add_filter('comment_form', array($this, 'printFakeImage'));
    }

    /**
     * Outputs honey pot fake image
     */
    public function fakeImage()
    {
        if (isset($_GET[$this->field_name]) && $_GET[$this->field_name] == $this->field_content) {
            //Send svg header
            header('Content-type: image/svg+xml');

            //Set cookie
            setcookie($this->field_name, $this->field_content, time() + 3600, "/");

            //Return something tha looks like a image
            die('<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><circle cx="50" cy="50" r="40" stroke="black" stroke-width="3" fill="red" /></svg>');
        }
    }

    /**
     * Outputs honey pot fake image check
     */
    public function fakeImageCookieCheck($data)
    {
        if (isset($_COOKIE[$this->field_name]) && $_COOKIE[$this->field_name] == $this->field_content) {
            return $data;
        }

        wp_die(__("Could not verify that you are human (image check).", 'municipio'));
    }

    /**
     * Outputs honey pot fake image
     */
    public function printFakeImage()
    {
        echo '<div class="fake-hide"><img src="' . get_permalink() . '?' . $this->field_name . '=' . $this->field_content . '"></div>';
    }

    /**
     * Outputs honey pot css
     */
    public function printFakeHideBox()
    {
        echo '<style>.fake-hide {width: 1px; height: 1px; opacity: 0.0001; position: absolute;}</style>';
    }

    /**
     * Outputs honey pot filled field
     */
    public function addHoneyPotFieldFilled()
    {
        echo '<div class="fake-hide"><input name="'.$this->field_name.'_fi" type="text" value="'.$this->field_content.'" size="30"></div>';
    }

    /**
     * Outputs honey pot blank field
     */
    public function addHoneyPotFieldBlank()
    {
        echo '<div class="fake-hide"><input class="hidden" name="'.$this->field_name.'_bl" type="text" value="" size="30"></div>';
    }

    /**
     * Validate honeypot fields before saving comment
     * @param  array $data The comment data
     * @return array       Comment data or die
     */
    public function honeyPotValidateFieldContent($data)
    {
        if (isset($_POST[$this->field_name.'_fi']) && isset($_POST[$this->field_name.'_bl'])) {
            if (empty($_POST[$this->field_name.'_bl']) && $_POST[$this->field_name.'_fi'] == $this->field_content) {
                return $data;
            }

            wp_die(__("Could not verify that you are human (some hidden form fields are manipulated).", 'municipio'));
        }

        wp_die(__("Could not verify that you are human (some form fields are missing).", 'municipio'));
    }
}

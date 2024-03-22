<?php
namespace Theme;

class Contributor {
    private $id;
    
    public function __construct($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    function getName(){
        $user_object = get_userdata( $this->getId() );
        return $user_object->display_name;
    }

    function getEmail(){
        $user_object = get_userdata( $this->getId() );
        return $user_object->user_email;
    }

    function getTitle(){
        return get_user_meta( $this->getId(), 'wpcf-title', true );
    }

    function getAvatar(){
        return get_avatar( $this->getId() );
    }

    function getDescription(){
        return get_user_meta( $this->getId(), 'description', true );
    }

    function getFavourite_Games(){
        return get_user_meta( $this->getId(), 'wpcf-favourite-games', true );
    }

    function isNotActive() {
        $meta = get_user_meta( $this->getId(), 'wpcf-user-not-active', true );

        if (empty($meta)) {
            return false;
        } else {
            return true;
        }
    }

    // TODO: make url to reviewed games
    function getUrl(){
        return 'ok';
    }


}
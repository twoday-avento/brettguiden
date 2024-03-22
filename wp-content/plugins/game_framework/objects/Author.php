<?php
namespace Theme;

class Author {
    const AUTHOR_FIELD = '_wpcf_belongs_utvikleren_id';
    private $id;
    private $author;

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function __construct($id = null) {
        if (!$id) {
            $id = get_the_ID();
        }
        $this->setId($id);
        $this->setAuthor();
    }

    public function setAuthor() {
        $this->author = get_post($this->getId(), OBJECT, 'display');
        return $this;
    }

    public function getAuthor() {
        return $this->author;
    }

    public function getName() {
        return $this->getAuthor()->post_title;
    }
}
<?php
namespace Theme;

class Publisher {
    const PUBLISHER_FIELD = '_wpcf_belongs_utgiver_id';
    private $id;
    private $publisher;

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
        $this->setPublisher();
    }

    public function setPublisher() {
        $this->publisher = get_post($this->getId(), OBJECT, 'display');
        return $this;
    }

    public function getPublisher() {
        return $this->publisher;
    }

    public function getName() {
         return $this->getPublisher()->post_title;
    }
}
<?php
namespace Theme;

class Distributor {
    const DISTRIBUTOR_FIELD = '_wpcf_belongs_distributor_id';
    private $id;
    private $distributor;

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
        $this->setDistributor();
    }

    public function setDistributor() {
        $this->distributor = get_post($this->getId(), OBJECT, 'display');
        return $this;
    }

    public function getDistributor() {
        return $this->distributor;
    }

    public function getName() {
        return $this->getDistributor()->post_title;
    }
}
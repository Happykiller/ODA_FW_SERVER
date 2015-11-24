<?php
namespace Oda\SimpleObject;
class OdaUser {
    /**
     * @var string
     */
    public $codeUser;
    /**
     * @var int
     */
    public $indice;
    /**
     * @var boolean
     */
    public $active;

    public function __construct($codeUser){
        $this->codeUser = $codeUser;
    }
}
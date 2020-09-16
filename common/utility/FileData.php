<?php
namespace common\utility;


class FileData
{
    public $src = "/placeholder.png",
    $alt = "";

    function __construct($src = '', $alt = '')
    {
        if(!empty($src)) {
            $this->src = $src;
        }
        if(!empty($alt)) {
            $this->alt = $alt;
        }
    }

    public function setSrc($src)
    {
        if(!empty($src)) {
            $this->src = $src;
        }
    }

    public function setAlt($alt)
    {
        if(!empty($alt)) {
            $this->alt = $alt;
        }
    }

}

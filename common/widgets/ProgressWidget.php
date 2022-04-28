<?php

namespace common\widgets;

use Yii;
use yii\bootstrap\Widget;

class ProgressWidget extends Widget
{
    public $done;
    public $total;
	public $info = "";
    public $width=50;

    public function run()
    {
        $perc = round(($this->done * 100) / $this->total);
        $perc = ' '.$perc;
        if($perc < 10) $perc = ' '.$perc;
        $bar = round(($this->width * $perc) / 100);
        return sprintf("%s%%[%s>%s]%s\r", $perc, str_repeat("=", $bar), str_repeat(" ", $this->width-$bar), $this->info);
    }
} 
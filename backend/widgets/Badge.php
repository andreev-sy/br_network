<?php

namespace backend\widgets;

use yii\base\Widget;
use yii\helpers\Html;

class Badge extends Widget
{
    public $label = 'Плашка на карточках (рестораны и залы)';
    public $badge;

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        return $this->render('badge', [
            'label' => $this->label,
            'badge' => $this->badge,
        ]);
    }
}

<?php

namespace backend\widgets;

use yii\base\Widget;

class PriceRange extends Widget
{
    public $model;
    public $attribute;

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        return $this->render('price-range', [
            'model' => $this->model,
            'attribute' => $this->attribute,
        ]);
    }
}

<?php

namespace backend\widgets;

use yii\base\Widget;

class GridViewSort extends Widget
{
    public $model;

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        return $this->render('grid-view-sort', [
            'model' => $this->model,
        ]);
    }
}

<?php

namespace backend\widgets;

use Yii;
use yii\base\Widget;

class DatePicker extends Widget
{
    public $model;
    public $attribute;
    /**
     * {@inheritdoc}
     */
    public function run()
    {
        echo \kartik\date\DatePicker::widget([
            'name' => 'dp',
            'type' => \kartik\date\DatePicker::TYPE_COMPONENT_PREPEND,
            'pluginOptions' => [
                'autoclose' => TRUE,
                'format'    => 'yyyy-mm-dd',
                'todayHighlight' => true
            ],
            'model' => $this->model,
            'attribute' => $this->attribute,
        ]);
    }
}

<?php
namespace backend\widgets;
use yii\base\Widget;


class DateTimePicker extends Widget
{
    public $model;
    public $attribute;
    /**
     * {@inheritdoc}
     */
    public function run()
    {
        echo \kartik\datetime\DateTimePicker::widget([
            'name' => 'dtp',
            'type' =>\kartik\datetime\DateTimePicker::TYPE_COMPONENT_PREPEND,
            'pluginOptions' => [
                'autoclose'=>true,
                'format' => 'yyyy-mm-dd hh:ii:ss',
                'todayHighlight' => true

            ],
            'model' => $this->model,
            'attribute' => $this->attribute
        ]);
       
    }
}

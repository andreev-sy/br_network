<?php

namespace backend\widgets;

use dosamigos\tinymce\TinyMce;
use Yii;
use yii\base\Widget;
use yii\web\JsExpression;

class HtmlEditor extends Widget
{
    public $model;
    public $attribute;
    /**
     * {@inheritdoc}
     */
    public function run()
    {
        echo TinyMce::widget(
            [
                'model' => $this->model,
                'attribute' => $this->attribute,
                'options' => ['rows' => 10],
                'language' => 'ru',
                'clientOptions' => [
                    'autoresize_bottom_margin' => 10,
                    'plugins' => [
                        "advlist autolink lists link charmap preview anchor emoticons nonbreaking",
                        "searchreplace visualblocks code fullscreen",
                        "insertdatetime media table contextmenu paste"
                    ],
                    'toolbar' => "undo redo | bold italic forecolor backcolor| alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link | emoticons charmap nonbreaking ",
                    'setup' => new JsExpression('function (theEditor) {
                    $(theEditor.getElement()).on("focus", function () {
                        theEditor.show();
                        theEditor.focus();
                    });
                    theEditor.on("blur", function () {
                        theEditor.hide();
                    });
                    theEditor.on("init", function () {
                        theEditor.hide();
                    });
                }')
                ]
            ]
        );
    }
}

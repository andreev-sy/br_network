<?php
use yii\helpers\Html;
use kartik\popover\PopoverX;
use yii\web\JsExpression;

$cookie_name = 'settings_'.$model->tableName();
$cookies = Yii::$app->request->cookies;
$settings = $cookies->getValue($cookie_name);
$settings = !empty($settings) ? json_decode($settings, true) : [];


$content = [];
foreach($model->getAttributes() as $key=>$attr){
    $content[] = Html::checkbox($key, in_array($key, $settings), ['label'=>$model->getAttributeLabel($key)]);
}

$content = '<div data-settings style="max-height: 400px; overflow: auto;">'.implode('<br>', $content) . '</div>';

echo PopoverX::widget([
    'header' => Yii::t('app', 'Выберите необходимые поля'),
    'placement' => PopoverX::ALIGN_AUTO,
    'content' => $content,
    'size' => PopoverX::SIZE_LARGE,
    'footer' => Html::button(
        Yii::t('app','Сохранить'), 
        [
            'class'=>'btn btn-sm btn-success',
            'onclick' => new JsExpression('
                function handlePress(){
                    let active = [];
                    let $block = $("[data-settings='.$cookie_name.']");
                    $("[data-settings]").find("input").each(function(){
                        if($(this).prop("checked")){
                            let attr = $(this).attr("name")
                            $block.find("[name="+attr+"]").show();
                            active.push(attr);
                        }
                    });
                    // let activeString = JSON.stringify(active);
                    // document.cookie = "'.$cookie_name.'=" + activeString + "; path=/";
                    // location.reload();
                }
                handlePress.call(this);
            ')
        ]
    ),
    'toggleButton' => ['label'=>Html::tag('i','',['class'=>'fa fa-gear']), 'class'=>'btn'],
    
]); 
?>
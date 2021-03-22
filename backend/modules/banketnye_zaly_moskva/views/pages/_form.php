<?php

use yii\bootstrap\Tabs;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Pages */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pages-form">

    
    <?php $this->beginBlock('Main');

    $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput() ?>
    <?= $form->field($model, 'type')->textInput() ?>

    <br/>

    <?= /*$form->field($model, 'title')->textInput() ?>
    <?= $form->field($model, 'title_pag')->textInput() ?>

    <br/>

    <?= $form->field($model, 'description')->textInput() ?>
    <?= $form->field($model, 'description_pag')->textInput() ?>

    <br/>

    <?= $form->field($model, 'keywords')->textInput() ?>
    <?= $form->field($model, 'keywords_pag')->textInput() ?>

    <br/>

    <?= $form->field($model, 'h1')->textInput() ?>
    <?= $form->field($model, 'h1_pag')->textInput() ?>

    <br/>

    <?= $form->field($model, 'img_alt')->textInput() ?>

    <?= $form->field($model, 'text_top')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'text_bottom')->textarea(['rows' => 6]) */ ''?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); 
    
    $this->endBlock();?>
    

    <?php $this->beginBlock('Seo');

    echo $this->render('/seo_tab_form.php', ['model' => $model]);

    $this->endBlock() ?>
    
    <?php $this->beginBlock('Media');

    echo $this->render('/media_tab_form.php', ['model' => $model]);

    $this->endBlock() ?>
    <?php echo
        Tabs::widget(
            [
                'encodeLabels' => false,
                'items' => [
                    [
                        'content' => $this->blocks['Main'],
                        'label'   => '<small>Pages<span class="badge badge-default"></span></small>',
                        'active'  => true,
                    ],
                    [
                        'content' => $this->blocks['Seo'],
                        'label'   => '<small>SEO<span class="badge badge-default"></span></small>',
                        'active'  => false,
                    ],
                    [
                        'content' => $this->blocks['Media'],
                        'label'   => '<small>Файлы <span class="badge badge-default">' . $model->getMediaTargets()->count() . '</span></small>',
                        'active'  => false,
                    ],
                    
                ]
            ]
        );
    ?>

</div>

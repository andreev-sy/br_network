<?php

use backend\widgets\HtmlEditor;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\Url;

$seoObject = $model->seoObject;
$textOnly = isset($textOnly) && $textOnly ? true : false;
if(!$seoObject) {
    echo '<div class="callout callout-warning mt-20">Сначала нужно сохранить объект!</div>'; return;
}
?>

<div class="site-object-seo-form">
    
    <?php $form = ActiveForm::begin(
        [
            'id' => 'SiteObjectSeo',
            'action' => '/site-object-seo/' . $seoObject->id . '/update/',
            'method' => 'post',
            'layout' => 'horizontal',
            'enableClientValidation' => true,
            'errorSummaryCssClass' => 'error-summary alert alert-danger',
            'fieldConfig' => [
                'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
                'horizontalCssClasses' => [
                    'label' => 'col-sm-2',
                    //'offset' => 'col-sm-offset-4',
                    'wrapper' => 'col-sm-8',
                    'error' => '',
                    'hint' => '',
                ],
            ],
        ]
    );
    ?>

    <div class="">
        <p>
            <?php if(!$textOnly) : ?>
            <!-- attribute heading -->
            <?php echo $form->field($seoObject, 'heading')->textInput(['maxlength' => true]) ?>

            <!-- attribute title -->
            <?php echo $form->field($seoObject, 'title')->textInput() ?>

            <!-- attribute description -->
            <?php echo $form->field($seoObject, 'description')->textarea(['rows' => 6]) ?>

            <!-- attribute keywords -->
            <?php echo $form->field($seoObject, 'keywords')->textInput(['maxlength' => true]) ?>
            <?php endif; ?>    
            <!-- attribute text1 -->
            <?php echo $form->field($seoObject, 'text1')->widget(HtmlEditor::class) ?>

            <!-- attribute text2 -->
            <?php echo $form->field($seoObject, 'text2')->widget(HtmlEditor::class) ?>

            <?php echo $form->field($seoObject, 'text3')->widget(HtmlEditor::class) ?>

            <?php if(!$textOnly) : ?>
            <?php echo $form->field($seoObject, 'pagination_heading')->textInput(['maxlength' => true]) ?>

            <?php echo $form->field($seoObject, 'pagination_title')->textInput() ?>

            <?php echo $form->field($seoObject, 'pagination_description')->textarea(['rows' => 6]) ?>

            <?php echo $form->field($seoObject, 'pagination_keywords')->textInput(['maxlength' => true]) ?>

            <?php echo $form->field($seoObject, 'img_alt')->textInput(['maxlength' => true]) ?>

            <?= isset($seoObject->active) ? $form->field($seoObject, 'active')->checkbox() : '' ?>
            <?php endif; ?>    


            <?= yii\helpers\Html::hiddenInput('back',Url::current()) ?>
        </p>

        <?php echo $form->errorSummary($seoObject); ?>

        <?php echo Html::submitButton(
            '<span class="glyphicon glyphicon-check"></span> ' .
                ($seoObject->isNewRecord ? Yii::t('cruds', 'Create') : Yii::t('cruds', 'Save')) . ' SEO',
            [
                'id' => 'save-' . $seoObject->formName(),
                'class' => 'btn btn-warning'
            ]
        );
        ?>

        <?php ActiveForm::end(); ?>

    </div>

</div>
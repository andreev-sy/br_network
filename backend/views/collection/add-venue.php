<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use backend\models\Venues;

/* @var $this yii\web\View */
/* @var $model app\models\Collection */

$this->title = Yii::t('app', 'Изменение элемента') .': #'. $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Подборки'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => '#'.$model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Изменение');
?> 
<div class="collection-update">

    <div class="collection-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'collection_venue_via')->widget(Select2::classname(), [
            'data' => Venues::getMapForCollection(),
            //'maintainOrder' => true,
            'options' => ['placeholder' => Yii::t('app', 'Выберите заведение')],
            'pluginOptions' => [
                'tags' => true,
                'multiple' => true,
            ],
        ]);	?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Добавить'), ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

</div>

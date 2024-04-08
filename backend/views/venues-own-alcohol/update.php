<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\VenuesOwnAlcohol */

$this->title = Yii::t('app', 'Изменение элемента') .': #'. $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Можно свой алкоголь'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => '#'.$model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Изменение');
?>
<div class="venues-own-alcohol-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

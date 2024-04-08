<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Images */

$this->title = Yii::t('app', 'Изменение {modelClass}: ', [
    'modelClass' => 'Images',
]) . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Images'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Изменение');
?>
<div class="images-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\FormRequest */

$this->title = Yii::t('app', 'Изменение {modelClass}: ', [
    'modelClass' => 'Form Request',
]) . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Form Requests'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Изменение');
?>
<div class="form-request-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

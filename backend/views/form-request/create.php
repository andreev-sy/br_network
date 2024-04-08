<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\FormRequest */

$this->title = Yii::t('app', 'Создание Form Request');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Form Requests'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="form-request-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>

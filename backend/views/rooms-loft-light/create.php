<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\RoomsLoftLight */

$this->title = Yii::t('app', 'Добавление элемента');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Освещение'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rooms-loft-light-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>

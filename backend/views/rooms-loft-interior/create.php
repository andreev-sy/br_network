<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\RoomsLoftInterior */

$this->title = Yii::t('app', 'Добавление элемента');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Особенности интерьера'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rooms-loft-interior-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>

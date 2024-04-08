<?php

/* @var $this yii\web\View */
/* @var $model backend\models\RoomsPaymentModel */

$this->title = Yii::t('app', 'Добавление элемента');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Схемы оплаты'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rooms-payment-model-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
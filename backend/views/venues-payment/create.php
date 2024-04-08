<?php

/* @var $this yii\web\View */
/* @var $model backend\models\VenuesPayment */

$this->title = Yii::t('app', 'Добавление элемента');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Способы оплаты'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="venues-payment-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
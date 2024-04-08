<?php

/* @var $this yii\web\View */
/* @var $model backend\models\VenuesExtraServices */

$this->title = Yii::t('app', 'Изменение элемента') .': #'. $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Сервисы за отдельную плату'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => '#'.$model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Изменение');
?>
<div class="venues-extra-services-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

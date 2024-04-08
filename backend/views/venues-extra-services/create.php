<?php


/* @var $this yii\web\View */
/* @var $model backend\models\VenuesExtraServices */

$this->title = Yii::t('app', 'Добавление элемента');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Сервисы за отдельную плату'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="venues-extra-services-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
<?php

/* @var $this yii\web\View */
/* @var $model backend\models\CollectionGuest */

$this->title = Yii::t('app', 'Добавление элемента');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Количества гостей'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="collection-guest-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
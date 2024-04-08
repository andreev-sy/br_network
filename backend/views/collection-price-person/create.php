<?php

/* @var $this yii\web\View */
/* @var $model backend\models\CollectionPricePerson */

$this->title = Yii::t('app', 'Добавление элемента');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Цены на человека'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="collection-price-person-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
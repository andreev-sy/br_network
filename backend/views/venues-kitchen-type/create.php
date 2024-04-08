<?php


/* @var $this yii\web\View */
/* @var $model backend\models\VenuesKitchenType */

$this->title = Yii::t('app', 'Добавление элемента');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Кухня'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="venues-kitchen-type-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
<?php

/* @var $this yii\web\View */
/* @var $model backend\models\VenuesSpecial */

$this->title = Yii::t('app', 'Добавление элемента');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Особенности заведения'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="venues-special-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
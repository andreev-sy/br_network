<?php

/* @var $this yii\web\View */
/* @var $model backend\models\VenuesType */

$this->title = Yii::t('app', 'Добавление элемента');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Типы заведения'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="venues-type-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
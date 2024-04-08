<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\VenuesType */

$this->title = Yii::t('app', 'Изменение элемента') .': #'. $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Типы заведения'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Изменение');
?>
<div class="venues-type-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

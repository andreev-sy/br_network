<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\VenuesStatus */

$this->title = Yii::t('app', 'Создание Venues Status');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Venues Statuses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="venues-status-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>

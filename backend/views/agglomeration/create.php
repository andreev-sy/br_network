<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Agglomeration */

$this->title = Yii::t('app', 'Добавление элемента');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Агломерации'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="agglomeration-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
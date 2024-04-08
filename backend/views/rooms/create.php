<?php


/* @var $this yii\web\View */
/* @var $model backend\models\Rooms */

$this->title = Yii::t('app', 'Добавление элемента');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Залы'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rooms-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
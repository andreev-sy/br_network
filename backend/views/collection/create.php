<?php


/* @var $this yii\web\View */
/* @var $model backend\models\Collection */

$this->title = Yii::t('app', 'Добавление элемента');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Подборки'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="collection-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
<?php


/* @var $this yii\web\View */
/* @var $model backend\models\Venues */

$this->title = Yii::t('app', 'Добавление элемента');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Заведения'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="venues-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
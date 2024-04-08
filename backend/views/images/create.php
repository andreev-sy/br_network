<?php


/* @var $this yii\web\View */
/* @var $model backend\models\Images */

$this->title = Yii::t('app', 'Добавление изображения');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Изображения'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="images-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
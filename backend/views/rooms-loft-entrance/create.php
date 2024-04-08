<?php



/* @var $this yii\web\View */
/* @var $model backend\models\RoomsLoftEntrance */

$this->title = Yii::t('app', 'Добавление элемента');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Входы и выходы'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rooms-loft-entrance-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
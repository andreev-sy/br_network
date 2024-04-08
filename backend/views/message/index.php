<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use kartik\grid\GridView as GridViewKartik;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\MessageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Переводы');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="message-index box box-primary">
    <?php // Pjax::begin();  ?>
    <div class="box-header with-border">
            <?//= Html::a(Yii::t('app', 'Добавить Message'), ['create'], ['class' => 'btn btn-success btn-flat'])  ?>
            <?= Html::a(Yii::t('app', 'Экспорт'), ['export'], ['class' => 'btn btn-primary btn-flat']) ?>
            <?= Html::a(Yii::t('app', 'Импорт'), ['import'], ['class' => 'btn btn-success btn-flat']) ?>
    </div>

    <div class="box-header">
        <?php // echo $this->render('_search', ['model' => $searchModel]);  ?>
    </div>
    <div class="box-body">
        <?= GridViewKartik::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                //['class' => 'yii\grid\SerialColumn'],
                'id',
                'language',
                'message:ntext',
                [
                    'class' => 'kartik\grid\EditableColumn',
                    'attribute' => 'translation',
                ],
            ],
        ]); ?>
    </div>
    <?php // Pjax::end();  ?>
</div>
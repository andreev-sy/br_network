<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use backend\models\Rooms;
use backend\models\ViaHelper;
use kartik\grid\GridView;
use yii\web\JsExpression;
use backend\widgets\GridViewSort;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\RoomsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Статистика');
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="kommo-stat-index box box-primary">
    <div class="box-header with-border">
        <?php if (Yii::$app->user->can('/kommo-stat/refresh')): ?>
            <?= Html::a(Yii::t('app', 'Обновить'), ['refresh'], ['class' => 'btn btn-success btn-flat']) ?>
        <?php endif; ?>
    </div>

    <div class="box-header"> 
        <?php echo $this->render('_search', ['model' => $searchModel]); ?>
    </div>
    <div class="box-body">
        <h4><?= Yii::t('app', 'Время ответа') ?></h4>
        <?= GridView::widget([
            'dataProvider' => $arrayDataProvider,
            'showPageSummary' => true,
            'pageSummaryRowOptions' => [ 'class'=>'kv-page-summary light' ],
            'columns' => [
                ['attribute' => 'response_time_range', 'label' => 'время ответа', 'pageSummary' => 'Итого:', 'width' => '10%'],
                ['attribute' => 'total_verified', 'label' => 'верифицированных лидов', 'pageSummary' => true, 'width' => '15%'],
                ['attribute' => 'total_sent_collections', 'label' => 'отправленных подборок', 'pageSummary' => true, 'width' => '15%'],   
                ['attribute' => 'total_selected_our_collection', 'label' => 'выбрали заведение из нашей подборки', 'pageSummary' => true, 'width' => '15%'],
                ['attribute' => 'total_meetings_venues', 'label' => 'встреч с ресторанами', 'pageSummary' => true, 'width' => '15%'],
                ['attribute' => 'total_rented_room_from_our_collection', 'label' => 'арендовало помещение из нашей подборки', 'pageSummary' => true, 'width' => '15%'],
                ['attribute' => 'total_events', 'label' => 'мероприятий успешно проведено', 'pageSummary' => true, 'width' => '15%'],
            ],
        ]); ?>
        <h4><?= Yii::t('app', 'Отвал/отмена') ?></h4>
        <?= GridView::widget([
            'dataProvider' => $arrayDataProvider,
            'showHeader' => false,
            'showPageSummary' => true,
            'pageSummaryRowOptions' => [ 'class'=>'kv-page-summary light' ],
            'columns' => [
                ['attribute' => 'response_time_range', 'label' => 'время ответа', 'pageSummary' => 'Итого:', 'width' => '10%'],
                ['attribute' => 'reject_verification_stage', 'label' => 'на этапе верификации', 'pageSummary' => true, 'width' => '15%'],
                ['attribute' => 'reject_request_sent_stage', 'label' => 'после отправки запроса', 'pageSummary' => true, 'width' => '15%'],
                ['attribute' => 'reject_selected_our_collection_stage', 'label' => 'с этапа выбрали заведение из нашей подборки', 'pageSummary' => true, 'width' => '15%'],
                ['attribute' => 'reject_meetings_venues_stage', 'label' => 'с этапа встречи', 'pageSummary' => true, 'width' => '15%'],
                ['attribute' => 'reject_rented_room_from_our_collection', 'label' => 'с этапа аренды помещения', 'pageSummary' => true, 'width' => '15%'],
                ['attribute' => 'reject_events', 'label' => 'с этапа успешно проведено', 'pageSummary' => true, 'width' => '15%'],
            ],
        ]); ?>
    </div>
</div>
<?php

use backend\models\CollectionVenueVia;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use backend\models\ViaHelper;
use himiklab\sortablegrid\SortableGridView;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model backend\models\Collection */

$this->title = '#' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Подборки'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="collection-view box box-primary">
    <?php if (Yii::$app->user->can('/collection/update') or Yii::$app->user->can('/collection/delete')): ?>
        <div class="box-header">
            <?php if (Yii::$app->user->can('/collection/update')): ?>
                <?= Html::a(Yii::t('app', 'Изменить'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-flat']) ?>
            <?php endif; ?>
            <?php if (Yii::$app->user->can('/collection/delete')): ?>
                <?= Html::a(Yii::t('app', 'Удалить'), ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger btn-flat',
                    'data' => [
                        'confirm' => Yii::t('app', 'Вы уверены, что хотите удалить элемент?'),
                        'method' => 'post',
                    ],
                ]) ?>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    <div class="box-body table-responsive">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'name',
                'date',
                'phone',
                [
                    'attribute' => 'spec_id',
                    'value' => ViaHelper::getText($model->spec),
                ],
                [
                    'attribute' => 'guest_id',
                    'value' => ViaHelper::getText($model->guest),
                ],
                [
                    'attribute' => 'price_person_id',
                    'value' => ViaHelper::getText($model->pricePerson),
                ],
                [
                    'attribute' => 'contact_type_id',
                    'value' => ViaHelper::getText($model->contactType),
                ],
                [
                    'attribute' => 'agglomeration_id',
                    'value' => $model->agglomeration->name ?? null,
                ],
                [
                    'attribute' => 'city_id',
                    'value' => $model->city->name ?? null,
                ],
                [
                    'attribute' => 'region_ids',
                    'value' => implode(', ', ArrayHelper::getColumn($model->collectionRegionVias, 'region.name'))
                ],
                [
                    'attribute' => 'district_ids',
                    'value' => implode(', ', ArrayHelper::getColumn($model->collectionDistrictVias, 'district.name'))
                ],
                'desire:ntext',
                [
                    'attribute' => 'form_request_id',
                    'format' => 'raw',
                    'value' => $model->form_request_id ? Html::a($model->formRequest->name, ['form-request/view', 'id' => $model->form_request_id], ['target' => '_blank']) : null,
                ],
                [
                    'attribute' => 'manager_user_id',
                    'format' => 'raw',
                    'value' => $model->manager_user_id ? Html::a($model->managerUser->fullname, ['user/view', 'id' => $model->manager_user_id], ['target' => '_blank']) : null,
                ],
                [
                    'attribute' => 'pool',
                    'format' => 'raw',
                    'value' => ViaHelper::getIntIcon($model->pool),
                ],
                [
                    'attribute' => 'place_barbecue',
                    'format' => 'raw',
                    'value' => ViaHelper::getIntIcon($model->place_barbecue),
                ],
                [
                    'attribute' => 'open_area',
                    'format' => 'raw',
                    'value' => ViaHelper::getIntIcon($model->open_area),
                ],
                [
                    'attribute' => 'hash',
                    'format' => 'raw',
                    'value' => Html::a($model->hash, Yii::$app->params['4u_domain'] . $model->hash . '/', ['target' => '_blank'])
                ],
                'created_at:datetime',
                'updated_at:datetime',
            ],
        ]) ?>
    </div>

    <div class="box-body">
        <p>
            <?php if (Yii::$app->user->can('/collection/add-venue')): ?>
                <?= Html::a(Yii::t('app', 'Добавить зал'), ['add-venue', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?php endif; ?>

            <?php if (Yii::$app->user->can('/collection/refresh-venues')): ?>
                <?= Html::a(Yii::t('app', 'Обновить'), ['refresh-venues', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
            <?php endif; ?>
        </p>
        <?= SortableGridView::widget([
            'dataProvider' => $dataProvider,
            'sortableAction' => ['sort'],
            'columns' => [
                [
                    'attribute' => 'venue_id',
                    'format' => 'html',
                    'value' => function ($data) {
                        if (empty ($data->venue))
                            return null;
                        return Html::a($data->venue->name, ['venues/view', 'id' => $data->venue_id], ['target' => '_blank']);
                    },
                ],
                'sort',
                [
                    'class' => 'yii\grid\CheckboxColumn',
                    'header' => Html::checkBox('selection_all', false, [
                        'class' => 'select-on-check-all',
                        'label' => Yii::t('app', 'Активно'),
                        'checked' => CollectionVenueVia::findOne(['active'=>0]) ? false : true,
                        'onclick' => new JsExpression('
                            function handleCheckboxAllChange(){
                                let set = $(this).prop("checked");
                                $.post("' . Url::to(['collection/ajax-set-all-venue']) . '", { set: set, collection_id: ' . $model->id . ' }, function(data){
                                    console.log(data);
                                });
                            }
                            handleCheckboxAllChange.call(this);
                        ')
                    ]),
                    'checkboxOptions' => function ($model, $key, $index, $column) {
                        $options['onclick'] = new JsExpression('
                            function handleCheckboxChange(){
                                $.post("'. Url::to(['collection/ajax-set-venue']) .'", { id: '.$model->id.' }, function(data){
                                    console.log(data);
                                });
                            }
                            handleCheckboxChange.call(this);
                        ');
                        $options['checked'] = $model->active ? true : false;
                        return $options;
                    }
                ],
            ],
        ]);
        ?>
    </div>
</div>
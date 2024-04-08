<?php 
// use himiklab\sortablegrid\SortableGridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\grid\GridView;
/**
 * @var $this yii\web\View
 * @var $model backend\models\Venues
 * @var $dataProvider yii\data\ActiveDataProvider
*/

?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    // 'sortableAction' => ['sort'],
    'columns' => [
        [
            'label' => Yii::t('app', 'Изображение'),
            'format' => 'raw',
            'value' => function ($data) {
                return Html::a(
                    Html::img( $data->subpath, ['class' => 'thumbnail']), 
                    $data->subpath, 
                    ['data-lightbox'=>'roadtrip', 'class'=>'room-image-block']
                );
            }
        ],
        [
            'label' => Yii::t('app', 'Зал'),
            'format' => 'raw',
            'value' => function ($data) {
                return Html::a($data->room->param_name_alt, ['rooms/view', 'id' => $data->room->id], ['class' => 'profile-link', 'target'=>'_blank']);
            }
        ],
        'sort',
        [
            'label' => Yii::t('app', 'Выводить в заведение'),
            'format' => 'raw',
            'value' => function ($data) use($model) {
                return Html::checkbox('agree', ($data->venue_id === $model->id), [
                    'onchange' => new JsExpression('
                        function handleCheckboxChange(){
                            let set = $(this).prop("checked");
                            $.post("'.Url::to(['images/ajax-set-venue/']).'", { set: set, id: '.$data->id.', venue_id: '.$model->id.' }, function(data){
                                console.log(data);
                            });
                        }
                        handleCheckboxChange.call(this);
                    ')
                ]);
            }
        ],
    ],
]);


?>

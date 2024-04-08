<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Пользователи');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index box box-primary">
    <?php Pjax::begin(); ?>
    <div class="box-header with-border">
        <?= Html::a(Yii::t('app', 'Добавить элемент'), ['create'], ['class' => 'btn btn-success btn-flat']) ?>
    </div>

    <div class="box-header">
        <?php // echo $this->render('_search', ['model' => $searchModel]);  ?>
    </div>
    <div class="box-body">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                //['class' => 'yii\grid\SerialColumn'],
        
                [
                    'attribute' => 'photo_path',
                    'format' => 'raw',
                    'options' => ['class' => 'img-circle'],
                    'value' => function ($data) {
                                if (file_exists($data->photo)) {
                                    return Html::tag('div', Html::img($data->photo_path), ['class' => 'user_avatar']);
                                }
                                return null;
                            },
                ],
                'username',
                // 'auth_key',
                // 'password_hash',
                // 'password_reset_token',
                'email:email',
                // 'status',
                // 'photo:ntext',
                // 'photo_path:ntext',
                // 'role',
                'fullname',
                'phone',
                // 'verification_token',
                // 'created_at',
                // 'updated_at',
                'id',

                ['class' => 'yii\grid\ActionColumn', 'template' => '{view} {update} {delete}'],
            ],
        ]); ?>
    </div>
    <?php Pjax::end(); ?>
</div>
<?php
use backend\models\RestaurantsOld;
use yii\widgets\DetailView;

$rest = RestaurantsOld::findOne(['article' => $model->id]) ?? new RestaurantsOld();

$attributes = [
    'options',
    'cuisines',
    'specials',
    'extra',
    'type',
    'subtypes',
    'category',
    'phone_g',
    'street',
    'working_hours',
    'popular_times',
    'about',
    'range',
    'menu_link',
];
$attr_result = [];
foreach ($attributes as $key => $attr) {
    if (!empty ($rest->$attr)) {
        $attr_result[] = [
            'attribute' => $attr,
            'format' => 'raw',
            'value' => implode(', ', explode('|', $rest->$attr))
        ];
    }
}

?>
<?= DetailView::widget([
    'model' => $rest,
    'attributes' => $attr_result,
]); ?>

<?php if (empty ($attr_result)): ?>
    <h4>
        <?= Yii::t('app', 'Нет спаршенных данных') ?>
    </h4>
<?php endif; ?>
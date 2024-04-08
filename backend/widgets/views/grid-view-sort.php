<?php
use kartik\popover\PopoverX;
use yii\helpers\Html;
use yii\helpers\Url;

$sort_content = [];
$sort = $_GET['sort'] ?? '';

$class['asc'] = 'kv-sort-link asc';
$class['desc'] = 'kv-sort-link desc';

$icon['asc'] = Html::tag('span', '', ['class'=>'glyphicon glyphicon-sort-by-attributes']);
$icon['desc'] = Html::tag('span', '', ['class'=>'glyphicon glyphicon-sort-by-attributes-alt']);

$text_btn = Yii::t('app', 'Сортировка');
foreach($model->attributes as $attr => $value){
    $label = $model->getAttributeLabel($attr);

    $active = ltrim($sort, '-') === $attr;
    $type = substr($sort, 0, 1) === '-' ? 'desc' : 'asc';
    $add = substr($sort, 0, 1) === '-' ? '' : '-';

    if($active){
        $text_btn = $label. ' '. $icon[$type];
        $sort_content[] = Html::a(
            $label. ' '. $icon[$type],
            Url::current(['sort' => $add.$attr]),
            [
                'class'=>$class[$type],
                'data-sort'=>$add.$attr,
                'data-pjax' => 1
            ]
        );
    }else{
        $sort_content[] = Html::a(
            $label,
            Url::current(['sort' => $attr]),
            [
                'class'=>$class['asc'],
                'data-sort'=>$attr,
                'data-pjax' => 1
            ]
        );
    }
}

echo PopoverX::widget([
    'header' => Yii::t('app', 'Сортировка'),
    'placement' => PopoverX::ALIGN_BOTTOM,
    'size' => PopoverX::SIZE_LARGE,
    'content' => Html::tag('div', implode('<br>', $sort_content), ['style' => 'max-height: 300px; overflow: auto']),
    'toggleButton' => ['label' => $text_btn, 'class' => 'btn btn-default btn-outline-secondary', 'data-toggle'=>'popover'],
]);

?>
<script>
    $("#venues-visit").on("pjax:end", function() {
        $.pjax.reload({container:".box-header.with-border"}); 
    });
</script>

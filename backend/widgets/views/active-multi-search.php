<?php


?>
<div class="row">
    <div class="col-md-3">
        <?= $form->field($model, $search."[field]")->dropdownList( $fields_list  ); ?>
    </div>
    <div class="col-md-3">
        <?= $form->field($model, $search."[type]")->dropdownList(
            [
                'exactly' => 'Точно',
                'range' => 'Диапазон',
                'more_than' => 'Больше чем',
                'less_than' => 'Меньше чем',
            ],
            ['prompt' => 'Выберите тип', 'data-type'=>'']
        ); ?>
    </div>
    <div class="col-md-6">
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, $search."[value][range][1]") ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, $search."[value][range][2]") ?>
            </div>
        </div>
        <?= $form->field($model, $search."[value][exactly]") ?>
        <?= $form->field($model, $search."[value][more_than]") ?>
        <?= $form->field($model, $search."[value][less_than]") ?>
    </div>
</div>


<script>
    $('[data-type]').on('change', function(){
        let type = $(this).val();
        $('[name="<?=$search?>['+type+']"]').addClass('active');
    })
</script>
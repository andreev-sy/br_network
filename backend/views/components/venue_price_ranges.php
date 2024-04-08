<?php 
use \yii\helpers\StringHelper;

$price = !empty($model->price_day_ranges) ? json_decode($model->price_day_ranges, true) : []; 
$className = StringHelper::basename(get_class($model));

$min_capacity = !empty($model->min_capacity) ? $model->min_capacity : 0;
$max_capacity = !empty($model->max_capacity) ? $model->max_capacity : 9999;

?>
 
<hr>
<div 
    class="price_day_wrap" 
    data-min="<?= $min_capacity ?>" 
    data-max="<?= $max_capacity ?>" 
    data-class-name="<?= $className ?>"
    data-mincapacity-label="<?= $model->getAttributeLabel('min_capacity') ?>"
    data-maxcapacity-label="<?= $model->getAttributeLabel('max_capacity') ?>"
    data-price-label="<?= $model->getAttributeLabel('price_day') ?>"
>
    <?php if (is_array($price) and !empty($price)): ?>
        <?php foreach ($price as $key => $row): ?>
            <?php $row_id = $key + 1; ?>
            <div class="price_day_row" data-row="<?= $key ?>">
                <div class="form-group">
                    <label class="control-label" for="min_capacity<?= $key ?>"><?= $model->getAttributeLabel('min_capacity') ?> <?= $row_id ?></label>
                    <input type="number" class="form-control" name="<?= $className ?>[price_day_ranges_arr][<?= $key ?>][mincapacity]"
                        value="<?= $row['mincapacity'] ?>"
                        id="min_capacity<?= $key ?>">
                </div>
                <div class="form-group">
                    <br>
                    <div class="control-label">-</div>
                </div>
                <div class="form-group">
                    <label class="control-label" for="max_capacity<?= $key ?>"><?= $model->getAttributeLabel('max_capacity') ?> <?= $row_id ?></label>
                    <input type="number" class="form-control" name="<?= $className ?>[price_day_ranges_arr][<?= $key ?>][maxcapacity]"
                        value="<?= $row['maxcapacity'] ?>" id="max_capacity<?= $key ?>">
                </div>
                <div class="form-group">
                    <br>
                    <div class="control-label">=</div>
                </div>
                <div class="form-group">
                    <label class="control-label" for="price<?= $key ?>"><?= $model->getAttributeLabel('price_day') ?> <?= $row_id ?></label>
                    <input type="number" class="form-control" name="<?= $className ?>[price_day_ranges_arr][<?= $key ?>][price]"
                        value="<?= $row['price'] ?>" id="price<?= $key ?>">
                </div>
                <div class="form-group" data-btn-wrap>
                    <br>
                    <?php if ($row_id > 1): ?>
                        <span class="btn btn-sm btn-danger" data-price-del><i class="fa fa-trash"></i></span>
                    <?php endif; ?>
                    <?php if ($row_id == count($price)): ?>
                        <span class="btn btn-sm btn-primary" data-price-add><i class="fa fa-plus"></i></span>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="price_day_row" data-row="0">
            <div class="form-group">
                <label class="control-label" for="min_capacity0">Мин. вместимость 1</label>
                <input type="number" class="form-control" name="<?= $className ?>[price_day_ranges_arr][0][mincapacity]"
                    value="<?= !empty($model->min_capacity) ? $model->min_capacity : 0 ?>" id="min_capacity0">
            </div>
            <div class="form-group">
                <br>
                <div class="control-label">-</div>
            </div>
            <div class="form-group">
                <label class="control-label" for="max_capacity0">Макс. вместимость 1</label>
                <input type="number" class="form-control" name="<?= $className ?>[price_day_ranges_arr][0][maxcapacity]"
                   value="<?= !empty($model->max_capacity) ? $model->max_capacity : (int)$model->min_capacity+1 ?>" id="max_capacity0">
            </div>
            <div class="form-group">
                <br>
                <div class="control-label">=</div>
            </div>
            <div class="form-group">
                <label class="control-label" for="price0">Стоимость аренды за день 1</label>
                <input type="number" class="form-control" name="<?= $className ?>[price_day_ranges_arr][0][price]" value="" id="price0">
            </div>
            <div class="form-group" data-btn-wrap>
                <br>
                <span class="btn btn-sm btn-primary" data-price-add><i class="fa fa-plus"></i></span>
            </div>
        </div>
    <?php endif; ?>
</div>
<hr>

<?php
$js = <<<JS
	$('document').ready(function(){
		let min = parseInt($('.price_day_wrap').data('min'));
		let max = parseInt($('.price_day_wrap').data('max'));
		let className = $('.price_day_wrap').data('class-name');
		let min_capacity_label = $('.price_day_wrap').data('mincapacity-label');
		let max_capacity_label = $('.price_day_wrap').data('maxcapacity-label');
		let price_label = $('.price_day_wrap').data('price-label');
		let wrap = $('.price_day_wrap');

		$('body').on('click', '[data-price-del]', function(){
			$(this).closest('.price_day_row').remove();
			if(wrap.find('.price_day_row:last [data-price-add]').length == 0)
				wrap.find('.price_day_row:last [data-btn-wrap]').append(renderBtnAdd());
		})
		$('body').on('click', '[data-price-add]', function(){
			let key = $(this).closest('.price_day_row').data('row');
			let min_capacity = $(this).closest('.price_day_row').find('[name="'+className+'[price_day_ranges_arr]['+key+'][mincapacity]"]').val();
			let max_capacity = $(this).closest('.price_day_row').find('[name="'+className+'[price_day_ranges_arr]['+key+'][maxcapacity]"]').val();
			let price = $(this).closest('.price_day_row').find('[name="'+className+'[price_day_ranges_arr]['+key+'][price]"]').val();
			wrap.append(
				renderRow(
					(key+1), 
					(key+2), 
					parseInt(min_capacity), 
					parseInt(max_capacity),
					parseInt(price),
				)
			);
			$(this).remove();
		})

		function renderBtnAdd(){
			return `<span class="btn btn-sm btn-primary" data-price-add><i class="fa fa-plus"></i></span>`;
		}

		function renderRow(key, row_id, min_capacity, max_capacity, price){
			return `<div class="price_day_row" data-row="`+key+`">
						<div class="form-group">
							<label class="control-label" for="min_capacity`+key+`">`+min_capacity_label+` `+row_id+`</label>
							<input type="number" class="form-control" name="`+className+`[price_day_ranges_arr][`+key+`][mincapacity]" value="`+(max_capacity+1)+`" id="min_capacity`+key+`">
						</div>
						<div class="form-group">
							<br><div class="control-label">-</div>
						</div>
						<div class="form-group">
							<label class="control-label" for="max_capacity`+key+`">`+max_capacity_label+` `+row_id+`</label>
							<input type="number" class="form-control" name="`+className+`[price_day_ranges_arr][`+key+`][maxcapacity]" value="" id="max_capacity`+key+`">
						</div>
						<div class="form-group">
							<br><div class="control-label">=</div>
						</div>
						<div class="form-group">
							<label class="control-label" for="price`+key+`">`+price_label+` `+row_id+`</label>
							<input type="text" class="form-control" name="`+className+`[price_day_ranges_arr][`+key+`][price]" value="" id="price`+key+`">
						</div>
						<div class="form-group" data-btn-wrap>
							<br>
							<span class="btn btn-sm btn-danger" data-price-del><i class="fa fa-trash"></i></span>
							<span class="btn btn-sm btn-primary" data-price-add><i class="fa fa-plus"></i></span>
						</div>
				</div>`;
		}
	});
JS;
$this->registerJs($js);
?>
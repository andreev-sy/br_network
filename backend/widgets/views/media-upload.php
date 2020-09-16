<br>
<div class="form-group" id="<?= $model->id ?>">
		<div class="bg-light-blue disabled color-palette box-header with-border"><span class="label" style="font-size:14px"><?= $label ?></span></div>
	<?php if ($hidden) : ?>
		<button class='activate-form'>Показать форму</button>
	<?php endif; ?>
	<input type="file" class="media_upload" name="" value="" <?= $multiple ? 'multiple' : '' ?> data-media_target_id="<?= $model->id ?>">
	<?php if (!empty($lastMedia)) : ?>
		<div class="box box-default collapsed-box" style="margin:10px 0">
			<div class="box-header with-border" style="padding:10px 15px">
				<div class="box-tools pull-left">
					<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
						<h3 class="box-title" style="font-size:14px;padding-left:15px">Добавить из недавно загруженных</h3>
					</button>
				</div>
				<!-- /.box-tools -->
			</div>
			<!-- /.box-header -->
			<div class="box-body" style="display: none;">
				<?php
				/** @var \common\models\siteobject\Media */
				foreach ($lastMedia as $media) : ?>
					<a href="#<?= $model->id ?>" data-last-media=<?= $media->id ?> data-attach=<?= $model->id ?>>
						<p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
							<? if ($media->getFileTypeForPreview() == 'image') : ?>
								<img width='40px' height='40px' src="<?= $media->getWebFileLink() ?>" class="img-rounded" alt="Image">
							<? else : ?>
								<span width='40px' height='40px' class="glyphicon glyphicon-list-alt"></span>
							<? endif; ?>
							<?= $media->file ?>
						</p>
					</a>
				<?php endforeach; ?>
			</div>
			<!-- /.box-body -->
		</div>
	<?php endif; ?>
</div>
<br>
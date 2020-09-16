<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use \yii\bootstrap\Tabs;

/**
 *
 * @var yii\web\View $this
 * @var common\models\blog\BlogTag $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="blog-tag-form">

	<?php $this->beginBlock('main'); ?>
	<?php $form = ActiveForm::begin([
		'id' => 'BlogTag',
		'layout' => 'horizontal',
		'enableClientValidation' => true,
		'errorSummaryCssClass' => 'error-summary alert alert-danger',
		'fieldConfig' => [
			'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
			'horizontalCssClasses' => [
				'label' => 'col-sm-2',
				//'offset' => 'col-sm-offset-4',
				'wrapper' => 'col-sm-8',
				'error' => '',
				'hint' => '',
			],
		],
	]);
	?>

	<div class="">

		<p>
			<!-- attribute name -->
			<?php echo $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

			<!-- attribute alias -->
			<?php echo $form->field($model, 'alias')->textInput(['maxlength' => true]) ?>

		</p>

		<hr />

		<?php echo $form->errorSummary($model); ?>

		<?php echo Html::submitButton(
			'<span class="glyphicon glyphicon-check"></span> ' .
				($model->isNewRecord ? Yii::t('cruds', 'Create') : Yii::t('cruds', 'Save')),
			[
				'id' => 'save-' . $model->formName(),
				'class' => 'btn btn-success'
			]
		);
		?>

		<?php ActiveForm::end(); ?>

	</div>
	<?php $this->endBlock(); ?>

	<?php $this->beginBlock('Media');

	echo $this->render('/media_tab_form.php', ['model' => $model]);

	$this->endBlock() ?>

	<?php $this->beginBlock('Seo');

	echo $this->render('/seo_tab_form.php', ['model' => $model]);

	$this->endBlock() ?>

	<?php echo
		Tabs::widget(
			[
				'encodeLabels' => false,
				'items' => [
					[
						'label'   => Yii::t('models', 'BlogTag'),
						'content' => $this->blocks['main'],
						'active'  => true,
					],
					[
						'content' => $this->blocks['Media'],
						'label'   => '<small>Файлы <span class="badge badge-default">' . $model->getMediaTargets()->count() . '</span></small>',
						'active'  => false,
					],
					[
						'content' => $this->blocks['Seo'],
						'label'   => '<small>SEO<span class="badge badge-default"></span></small>',
						'active'  => false,
					],
				]
			]
		);
	?>

</div>
<?php


use yii\bootstrap\Tabs;

/* @var $this yii\web\View */
/* @var $model backend\models\Venues */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="venues-form box box-primary">
    <?php 
		$this->beginBlock('main');
		echo $this->render('_form_main.php', ['model'=>$model]);
		$this->endBlock(); 
	?>

    <?php 
		$this->beginBlock('other');
		echo $this->render('_form_other.php', ['model'=>$model]);
		$this->endBlock(); 
	?>

    <?php 
		$this->beginBlock('vendor');
		echo $this->render('_form_venue_visit.php', ['model'=>$model]);
		$this->endBlock(); 
	?>

    <?= Tabs::widget([
		'encodeLabels' => false,
		'items' => [
			[
				'content' => $this->blocks['main'],
				'label'   => '<div>'.Yii::t('app','Поля заведения').'<span class="badge badge-default"></span></div>',
				'active'  => true,
			],
			[
				'content' => $this->blocks['other'],
				'label'   => '<div>'.Yii::t('app','Спаршенные данные').'<span class="badge badge-default"></span></div>',
			],
            [
				'content' => $this->blocks['vendor'],
				'label'   => '<div>'.Yii::t('app', 'Для менеджеров по продажам').'<span class="badge badge-default"></span></div>',
			],
		]
	]); ?>

</div>

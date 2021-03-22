<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Pages */

$this->title = 'SEO для страницы: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Pages', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pages-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="subdomen_list">
    	<?php /* foreach ($subdomen_pages as $subdomen_page): ?>
    		<a class="update_subdomen_page" href="/subdomen-pages/update/?id=<?=$subdomen_page->id?>"><?=$subdomen_page->subdomen->name?></a>
    	<?php endforeach; ?>
    	<div class="create_subdomen_page btn btn-success" data-page-id="<?=$model->id?>">Создать SEO для поддомена</div>
    	<div class="page_subdomen_list" id="page_subdomen_list">
    		<?php foreach ($subdomens as $subdomen): ?>
	    		<a href="/subdomen-pages/create/?page_id=<?=$model->id?>&subdomen_id=<?=$subdomen->id?>"><?=$subdomen->name?></a>
	    	<?php endforeach; */?>
    	</div>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

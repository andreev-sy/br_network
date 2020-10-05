<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use yii\bootstrap\Tabs;

/**
 *
 * @var yii\web\View $this
 * @var common\models\blog\BlogTag $model
 */
$copyParams = $model->attributes;

$this->title = Yii::t('models', 'Blog Tag');
$this->params['breadcrumbs'][] = ['label' => Yii::t('models', 'Blog Tags'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string) $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('cruds', 'View');
?>
<div class="giiant-crud blog-tag-view">

	<!-- flash message -->
	<?php if (\Yii::$app->session->getFlash('deleteError') !== null) : ?>
		<span class="alert alert-info alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span></button>
			<?php echo \Yii::$app->session->getFlash('deleteError') ?>
		</span>
	<?php endif; ?>

	<h1>
		<?php echo Yii::t('models', 'Blog Tag') ?>
		<small>
			<?php echo Html::encode($model->name) ?>
		</small>
	</h1>


	<div class="clearfix crud-navigation">

		<!-- menu buttons -->
		<div class='pull-left'>
			<?php echo Html::a(
				'<span class="glyphicon glyphicon-pencil"></span> ' . Yii::t('cruds', 'Edit'),
				['update', 'id' => $model->id],
				['class' => 'btn btn-info']
			) ?>

			<?php echo Html::a(
				'<span class="glyphicon glyphicon-copy"></span> ' . Yii::t('cruds', 'Copy'),
				['create', 'id' => $model->id, 'BlogTag' => $copyParams],
				['class' => 'btn btn-success']
			) ?>

			<?php echo Html::a(
				'<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('cruds', 'New'),
				['create'],
				['class' => 'btn btn-success']
			) ?>
		</div>

		<div class="pull-right">
			<?php echo Html::a('<span class="glyphicon glyphicon-list"></span> '
				. Yii::t('cruds', 'Full list'), ['index'], ['class' => 'btn btn-default']) ?>
		</div>

	</div>

	<hr />

	<?php $this->beginBlock('common\models\blog\BlogTag'); ?>


	<?php echo DetailView::widget([
		'model' => $model,
		'attributes' => [
			'name',
			'alias',
			//'parent_id',
			//'sort',
			// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::attributeFormat
			[
				'format' => 'html',
				'attribute' => 'created_by',
				'value' => ($model->createdBy ?
					Html::a('<i class="glyphicon glyphicon-list"></i>', ['/user/index']) . ' ' .
					Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> ' . $model->createdBy->id, ['/user/view', 'id' => $model->createdBy->id,]) . ' ' .
					Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'BlogTag' => ['created_by' => $model->created_by]])
					:
					'<span class="label label-warning">?</span>'),
			],
			// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::attributeFormat
			[
				'format' => 'html',
				'attribute' => 'updated_by',
				'value' => ($model->updatedBy ?
					Html::a('<i class="glyphicon glyphicon-list"></i>', ['/user/index']) . ' ' .
					Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> ' . $model->updatedBy->id, ['/user/view', 'id' => $model->updatedBy->id,]) . ' ' .
					Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'BlogTag' => ['updated_by' => $model->updated_by]])
					:
					'<span class="label label-warning">?</span>'),
			],
		],
	]); ?>


	<hr />

	<?php echo Html::a(
		'<span class="glyphicon glyphicon-trash"></span> ' . Yii::t('cruds', 'Delete'),
		['delete', 'id' => $model->id],
		[
			'class' => 'btn btn-danger',
			'data-confirm' => '' . Yii::t('cruds', 'Are you sure to delete this item?') . '',
			'data-method' => 'post',
		]
	); ?>
	<?php $this->endBlock(); ?>



	<?php $this->beginBlock('BlogPostTags'); ?>
	<div style='position: relative'>
		<div style='position:absolute; right: 0px; top: 0px;'>
			<?php echo Html::a(
				'<span class="glyphicon glyphicon-list"></span> ' . Yii::t('cruds', 'List All') . ' Blog Post Tags',
				['/blog-post-tag/index'],
				['class' => 'btn text-muted btn-xs']
			) ?>
			<?php echo Html::a(
				'<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('cruds', 'New') . ' Blog Post Tag',
				['/blog-post-tag/create', 'BlogPostTag' => ['blog_tag_id' => $model->id]],
				['class' => 'btn btn-success btn-xs']
			); ?>
		</div>
	</div>
	<?php Pjax::begin(['id' => 'pjax-BlogPostTags', 'enableReplaceState' => false, 'linkSelector' => '#pjax-BlogPostTags ul.pagination a, th a']) ?>
	<?php echo
		'<div class="table-responsive">'
			. \yii\grid\GridView::widget([
				'layout' => '{summary}<div class="text-center">{pager}</div>{items}<div class="text-center">{pager}</div>',
				'dataProvider' => new \yii\data\ActiveDataProvider([
					'query' => $model->getBlogPostTags(),
					'pagination' => [
						'pageSize' => 20,
						'pageParam' => 'page-blogposttags',
					]
				]),
				'pager'        => [
					'class'          => yii\widgets\LinkPager::className(),
					'firstPageLabel' => Yii::t('cruds', 'First'),
					'lastPageLabel'  => Yii::t('cruds', 'Last')
				],
				'columns' => [
					// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
					[
						'class' => yii\grid\DataColumn::className(),
						'attribute' => 'blog_post_id',
						'value' => function ($model) {
							if ($rel = $model->blogPost) {
								return Html::a($rel->name, ['/blog-post/view', 'id' => $rel->id,], ['data-pjax' => 0]);
							} else {
								return '';
							}
						},
						'format' => 'raw',
					],
					'sort',
					[
						'class'      => 'yii\grid\ActionColumn',
						'template'   => '{view} {update}',
						'contentOptions' => ['nowrap' => 'nowrap'],
						'urlCreator' => function ($action, $model, $key, $index) {
							// using the column name as key, not mapping to 'id' like the standard generator
							$params = is_array($key) ? $key : [$model->primaryKey()[0] => (string) $key];
							$params[0] = '/blog-post-tag' . '/' . $action;
							$params['BlogPostTag'] = ['blog_tag_id' => $model->primaryKey()[0]];
							return $params;
						},
						'buttons'    => [],
						'controller' => '/blog-post-tag'
					],
				]
			])
			. '</div>'
	?>
	<?php Pjax::end() ?>
	<?php $this->endBlock() ?>


	<?php $this->beginBlock('BlogPosts'); ?>
	<div style='position: relative'>
		<div style='position:absolute; right: 0px; top: 0px;'>
			<?php echo Html::a(
				'<span class="glyphicon glyphicon-list"></span> ' . Yii::t('cruds', 'List All') . ' Blog Posts',
				['/blog-post/index'],
				['class' => 'btn text-muted btn-xs']
			) ?>
			<?php echo Html::a(
				'<span class="glyphicon glyphicon-plus"></span> ' . Yii::t('cruds', 'New') . ' Blog Post',
				['/blog-post/create', 'BlogPost' => ['id' => $model->id]],
				['class' => 'btn btn-success btn-xs']
			); ?>
			<?php echo Html::a(
				'<span class="glyphicon glyphicon-link"></span> ' . Yii::t('cruds', 'Attach') . ' Blog Post',
				['/blog-post-tag/create', 'BlogPostTag' => ['blog_tag_id' => $model->id]],
				['class' => 'btn btn-info btn-xs']
			) ?>
		</div>
	</div>
	<?php Pjax::begin(['id' => 'pjax-BlogPosts', 'enableReplaceState' => false, 'linkSelector' => '#pjax-BlogPosts ul.pagination a, th a']) ?>
	<?php echo
		'<div class="table-responsive">'
			. \yii\grid\GridView::widget([
				'layout' => '{summary}<div class="text-center">{pager}</div>{items}<div class="text-center">{pager}</div>',
				'dataProvider' => new \yii\data\ActiveDataProvider([
					'query' => $model->getBlogPostTags(),
					'pagination' => [
						'pageSize' => 20,
						'pageParam' => 'page-blogposttags',
					]
				]),
				'pager'        => [
					'class'          => yii\widgets\LinkPager::className(),
					'firstPageLabel' => Yii::t('cruds', 'First'),
					'lastPageLabel'  => Yii::t('cruds', 'Last')
				],
				'columns' => [
					// generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::columnFormat
					[
						'class' => yii\grid\DataColumn::className(),
						'attribute' => 'blog_post_id',
						'value' => function ($model) {
							if ($rel = $model->blogPost) {
								return Html::a($rel->name, ['/blog-post/view', 'id' => $rel->id,], ['data-pjax' => 0]);
							} else {
								return '';
							}
						},
						'format' => 'raw',
					],
					'sort',
					[
						'class'      => 'yii\grid\ActionColumn',
						'template'   => '{view} {update}',
						'contentOptions' => ['nowrap' => 'nowrap'],
						'urlCreator' => function ($action, $model, $key, $index) {
							// using the column name as key, not mapping to 'id' like the standard generator
							$params = is_array($key) ? $key : [$model->primaryKey()[0] => (string) $key];
							$params[0] = '/blog-post-tag' . '/' . $action;
							$params['BlogPostTag'] = ['blog_tag_id' => $model->primaryKey()[0]];
							return $params;
						},
						'buttons'    => [],
						'controller' => '/blog-post-tag'
					],
				]
			])
			. '</div>'
	?>
	<?php Pjax::end() ?>
	<?php $this->endBlock() ?>

	<?php $this->beginBlock('Media');

	echo $this->render('/media_tab.php', ['model' => $model]);

	$this->endBlock() ?>

	<?php $this->beginBlock('Seo');

	echo $this->render('/seo_tab.php', ['model' => $model]);

	$this->endBlock() ?>

	<?php echo Tabs::widget(
		[
			'id' => 'relation-tabs',
			'encodeLabels' => false,
			'items' => [
				[
					'label'   => '<b class=""># ' . Html::encode($model->id) . '</b>',
					'content' => $this->blocks['common\models\blog\BlogTag'],
					'active'  => true,
				],
				[
					'content' => $this->blocks['BlogPosts'],
					'label'   => '<small>Blog Posts <span class="badge badge-default">' . $model->getBlogPosts()->count() . '</span></small>',
					'active'  => false,
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
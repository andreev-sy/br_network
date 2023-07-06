<?php

namespace backend\controllers\api\gdedr;

/**
 * This is the class for REST controller "BlogPostBlockController".
 */

use backend\modules\gdedr\models\blog\BlogPostBlock;
use backend\modules\gdedr\models\blog\BlogPostBlockSearch;

use common\utility\SortableTrait;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;
use yii\web\BadRequestHttpException;

class BlogPostBlockController extends \yii\rest\ActiveController
{
	use SortableTrait;

	public $modelClass = BlogPostBlock::class;
	public $serializer = [
		'class' => 'yii\rest\Serializer',
		'collectionEnvelope' => 'items',
	];

	public function actions()
	{
		echo 2;
		die;
		$actions = parent::actions();
		$actions['index']['dataFilter'] = [
            'class' => 'yii\data\ActiveDataFilter',
            'searchModel' => BlogPostBlockSearch::class
		];
		$actions['index']['prepareDataProvider'] = function ($action, $filter) {
			$model = new $this->modelClass;
			$query = $model::find()->orderBy(['sort' => SORT_ASC]);
			if (!empty($filter)) {
				$query->andWhere($filter);
			}
			$dataProvider = new ActiveDataProvider([
				'query' => $query,
				'pagination' => false,

			]);

			return $dataProvider;
		};

		return $actions;
	}

	public function actionSort()
	{
		if (!$items = \Yii::$app->request->post('items')) {
			throw new BadRequestHttpException('Don\'t received POST param `items`.');
		}
		/** @var \yii\db\ActiveRecord $model */
		$model = new $this->modelClass;
		$items = Json::decode($items);
		foreach ($items as $id => $sort) {
			$models[$id] = $model::findOne($id);
			$newOrder[$id] = $sort;
		}
		return $model::getDb()->transaction(function () use ($models, $newOrder) {
			$rowsUpdated = 0;
			foreach ($newOrder as $modelId => $newSort) {
				/** @var ActiveRecord[] $models */
				$rowsUpdated += $models[$modelId]->updateAttributes(['sort' => $newSort]);
			}
			return $rowsUpdated;
		});
	}
}

<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\Rooms;
use frontend\components\Breadcrumbs;
use frontend\models\ApiItem;
use frontend\models\ApiMain;

class ItemController extends Controller
{

	public function actionIndex($id)
	{


		$item = Rooms::find()
			->with('restaurants')
			->where(['id' => $id])
			->one();

		//$item = ApiItem::getData($item->restaurants->gorko_id);

		$seo['h1'] = $item->name;
		$seo['breadcrumbs'] = Breadcrumbs::get_breadcrumbs(2);
		$seo['desc'] = $item->restaurants->name;
		$seo['address'] = $item->restaurants->address;

		$apiMain = new ApiMain;
		$other_rooms = $apiMain->getOther($item->restaurants->id, $id);

		return $this->render('index.twig', array(
			'item' => $item,
			'queue_id' => $id,
			'seo' => $seo,
			'other_rooms' => $other_rooms
		));
	}

}
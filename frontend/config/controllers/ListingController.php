<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use common\models\GorkoApi;

use common\pmnetwork\controllers\ListingController as BaseListingController;

class ListingController extends BaseListingController
{

}

//class ListingController extends Controller
//{
//	public function actionIndex(){
//		GorkoApi::renewAllData();
//		return 1;
//	}	
//}
<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\ApiListing;
use frontend\models\ApiItem;

/**
 * Site controller
 */
class UpdateController extends Controller
{

	public function actionUpdate()
    {
    	$apiResult = ApiItem::getData('438769');
    	//$apiResult['rooms'][0]['media'] = [];
		echo '<pre>';
		print_r($apiResult);
		echo '</pre>';
		//$apiResult = ApiItem::getData('3563');
		//echo '<pre>';
		//print_r($apiResult['params']['param_own_alcohol']);
		//echo '</pre>';
		//$apiResult = ApiItem::getData('29103');
		//echo '<pre>';
		//print_r($apiResult['params']['param_own_alcohol']);
		//echo '</pre>';
		//$apiResult = ApiItem::getData('145259');
		//echo '<pre>';
		//print_r($apiResult['params']['param_own_alcohol']);
		//echo '</pre>';
		//$apiResult = ApiItem::getData('201149');
		//echo '<pre>';
		//print_r($apiResult['params']['param_own_alcohol']);
		//echo '</pre>';
		exit;

        return $this->render('update.twig');
    }

}
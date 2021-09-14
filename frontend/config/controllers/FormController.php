<?php
namespace frontend\controllers;

use Yii;
use frontend\models\FormHelp;
use yii\web\Controller;

class FormController extends Controller
{

	public function actionValidate()
	{
		echo 1;
		exit;
		$model = new FormHelp();
		if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())){
			Yii::$app->response->format = Response::FORMAT_JSON;
			return ActiveForm::validate($model);
		}
	}
}
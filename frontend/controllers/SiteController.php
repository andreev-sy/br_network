<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use frontend\widgets\FilterWidget;
use frontend\models\ApiMain;
use backend\models\WidgetMain;
use backend\models\Pages;
use backend\models\Filter;
use backend\models\Slices;

class SiteController extends Controller
{
    public function actionIndex()
    {
        $filter_model = Filter::find()->with('items')->all();
        $slices_model = Slices::find()->all();
        $widgets_model = WidgetMain::find()->with('slice')->all();

        $apiMain = new ApiMain;
        $apiMain = $apiMain->getMain($widgets_model, $filter_model, $slices_model);

        $seo = Pages::find()->where(['name' => 'index'])->one();
        $this->setSeo($seo);

        $filter = FilterWidget::widget([
            'filter_active' => [],
            'filter_model' => $filter_model
        ]);

        return $this->render('index.twig', [
            'filter' => $filter,
            'widgets' => $apiMain['widgets'],
            'count' => $apiMain['total'],
            'seo' => $seo,
        ]);
    }

    private function setSeo($seo){
        $this->view->title = $seo['title'];
        $this->view->params['desc'] = $seo['description'];
        $this->view->params['kw'] = $seo['keywords'];
    }
}

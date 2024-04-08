<?php

namespace backend\controllers;

use Yii;
use backend\models\Venues;
use backend\models\VenuesSearch;
use backend\models\VenuesVisit;
use backend\models\VenuesVisitItem;
use backend\models\Images;
use backend\models\Rooms;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\data\ActiveDataProvider;

/**
 * VenuesController implements the CRUD actions for Venues model.
 */
class VenuesController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Venues models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new VenuesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Venues models.
     * @return mixed
     */
    public function actionProccessed()
    {
        $searchModel = new VenuesSearch();
        $searchModel->is_processed = 1;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Venues models.
     * @return mixed
     */
    public function actionEmpty()
    {
        $searchModel = new VenuesSearch();
        $searchModel->is_empty = 1;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Venues model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) 
    {
        $post = Yii::$app->request->post();
        
        // добавление данных для визитов
        if (Yii::$app->request->isAjax && isset($post['VenuesVisit'])){
            $visit = VenuesVisit::findOne(['venue_id'=>$post['VenuesVisit']['venue_id']]) ?? new VenuesVisit();
            $visit->load($post);
            $visit->save();
            unset($post);
        }

        // добавление визита
        if (Yii::$app->request->isAjax && isset($post['VenuesVisitItem'])){
            if(!empty($post['VenuesVisitItem']['id'])){
                $visit = VenuesVisitItem::findOne($post['VenuesVisitItem']['id']);
                if(!empty($post['del'])){
                    $visit->delete();
                } else {
                    if ($visit->load(Yii::$app->request->post())) 
                        $visit->save();
                }
            }else{
                $visit = new VenuesVisitItem();
                if ($visit->load(Yii::$app->request->post())) $visit->save();
            }
            unset($post);
        }

        
        $rooms = Rooms::findAll(['venue_id'=>$id]);
        $rooms_ids = ArrayHelper::getColumn($rooms, 'id');
        
        $dataProviderImg = new ActiveDataProvider([
            'query' => Images::find()->where(['room_id'=>$rooms_ids])->orderBy(['room_id' => SORT_ASC, 'sort' => SORT_ASC]),
            'pagination' => false,
            'sort' => false,
        ]);

        // ini_set('memory_limit', '1024M');
        // $query = Venues::find()->where(['venues.id'=>$id]);
        // $query->joinWith(['site']);
        // $query->joinWith(['status']);
        // $query->joinWith(['city']);
        // $query->joinWith(['region']);
        // $query->joinWith(['district']);
        // $query->joinWith(['managerUser']);
        // $query->joinWith(['vendorUser']);
        // $query->joinWith(['paramOwnAlcohol']);
        // $query->joinWith(['paramDecorPolicy']);
        // $query->joinWith(['venuesExtraServicesVias.venuesExtraServices']);
        // $query->joinWith(['venuesKitchenTypeVias.venuesKitchenType']);
        // $query->joinWith(['venuesLocationVias.venuesLocation']);
        // $query->joinWith(['venuesParkingTypeVias.venuesParkingType']);
        // $query->joinWith(['venuesPaymentVias.venuesPayment']);
        // $query->joinWith(['venuesSeatingArrangementVias.venuesSeatingArrangement']);
        // $query->joinWith(['venuesSpecVias.venuesSpec']);
        // $query->joinWith(['venuesSpecialVias.venuesSpecial']);
        // $query->joinWith(['venuesTypeVias.venuesType']);

  
        return $this->render('view', [
            'model' => $this->findModel($id),
            'dataProviderImg' => $dataProviderImg,
        ]);
    }

    /**
     * Creates a new Venues model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Venues();
        
        // if(!empty($id)){
        //     $source_model = $this->findModel($id);
        //     $model->attributes = $source_model->attributes;
        // }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('view', [
                'model' => $model,
            ]);
        }
    }


    /**
     * Updates an existing Venues model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $post = Yii::$app->request->post();

        // сохранение модели
        if(!empty($post)){
            $model = $this->findModel($id);
            if ($model->load($post)) {
                if(!$model->save())
                    Yii::$app->session->setFlash('kv-detail-warning', Yii::t('app', 'Не удалсоь сохранить элемент, проверьте правильность заполнения полей'));
            }
        }

        return $this->redirect(['view', 'id' => $id]);
        
        // $model = $this->findModel($id);

        // if ($model->load(Yii::$app->request->post()) && $model->save()) {
        //     return $this->redirect(['view', 'id' => $model->id]);
        // } else {
        //     return $this->render('update', [
        //         'model' => $model,
        //     ]);
        // }
    }

    // /**
    //  * Deletes an existing Venues model.
    //  * If deletion is successful, the browser will be redirected to the 'index' page.
    //  * @param integer $id
    //  * @return mixed
    //  */
    // public function actionDelete($id)
    // {
    //     $this->findModel($id)->delete();
    //     return $this->redirect(['index']);
    // }

    public function actionAjaxDelete($id)
    {
        $post = Yii::$app->request->post();

        if (Yii::$app->request->isAjax && isset($post['kvdelete'])) {
            if($this->findModel($id)->delete()){
                echo json_encode([
                    'success' => true,
                    'messages' => [
                        'kv-detail-info' => Yii::t('app', 'Элемент успешно удален').' '.Html::a('вернуться на главную', ['/venues'])
                    ]
                ]);
            }
        }
    }

    /**
     * Finds the Venues model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Venues the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Venues::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionAjaxGetDetails()
    {
        $post = Yii::$app->request->post();
        $model = Venues::find()
                    ->joinWith(['city'])
                    ->joinWith(['region'])
                    ->joinWith(['district'])
                    ->where(['venues.id'=>$post['expandRowKey']])
                    ->one();

        return $this->renderPartial('_expand-row-details', ['model' => $model]);
    }

    
}

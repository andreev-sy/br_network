<?php

namespace backend\controllers;

use Yii;
use yii\helpers\Html;
use backend\models\Rooms;
use backend\models\RoomsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * RoomsController implements the CRUD actions for Rooms model.
 */
class RoomsController extends Controller
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
     * Lists all Rooms models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RoomsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Rooms models.
     * @return mixed
     */
    public function actionSetVenue()
    {
        $searchModel = new RoomsSearch();
        $searchModel->similar_address = 1;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    // /**
    //  * Displays a single Rooms model.
    //  * @param integer $id
    //  * @return mixed
    //  */
    // public function actionView($id)
    // {
    //     return $this->render('view', [
    //         'model' => $this->findModel($id),
    //     ]);
    // }


    public function actionView($id) 
    {

        
        // ini_set('memory_limit', '1024M');
        // $query = Rooms::find()->where(['rooms.id'=>$id]);
        // $query->joinWith(['images']);
        // $query->joinWith(['venue']);
        // $query->joinWith(['paramPaymentModel']);
        // $query->joinWith(['roomsFeaturesVias.roomsFeatures']);
        // $query->joinWith(['roomsLocationVias.roomsLocation']);
        // $query->joinWith(['roomsLoftColorVias.roomsLoftColor']);
        // $query->joinWith(['roomsLoftEntranceVias.roomsLoftEntrance']);
        // $query->joinWith(['roomsLoftEquipment1Vias.roomsLoftEquipment1']);
        // $query->joinWith(['roomsLoftEquipment2Vias.roomsLoftEquipment2']);
        // $query->joinWith(['roomsLoftEquipment3Vias.roomsLoftEquipment3']);
        // $query->joinWith(['roomsLoftEquipmentFurnitureVias.roomsLoftEquipmentFurniture']);
        // $query->joinWith(['roomsLoftEquipmentGamesVias.roomsLoftEquipmentGames']);
        // $query->joinWith(['roomsLoftEquipmentInteriorVias.roomsLoftEquipmentInterior']);
        // $query->joinWith(['roomsLoftInteriorVias.roomsLoftInterior']);
        // $query->joinWith(['roomsLoftLightVias.roomsLoftLight']);
        // $query->joinWith(['roomsLoftStaffVias.roomsLoftStaff']);
        // $query->joinWith(['roomsLoftStyleVias.roomsLoftStyle']);
        // $query->joinWith(['roomsVenuesSpecVias.venuesSpec']);
        // $query->joinWith(['roomsZonesVias.roomsZones']);

        return $this->render('view', ['model'=> $this->findModel($id)]);
    }
 
    /**
     * Creates a new Rooms model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Rooms();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('view', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Rooms model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionImageUpload($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->rooms_images = UploadedFile::getInstances($model, 'rooms_images');
			$uploadFlag = $model->uploadImages();
			if ($uploadFlag and $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
			}
        }
        
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $post = Yii::$app->request->post();
   
        if(!empty($post)){
            $model = $this->findModel($id);
            if ($model->load($post)) {
                if(!$model->save())
                    Yii::$app->session->setFlash('kv-detail-warning', Yii::t('app', 'Не удалось сохранить элемент, проверьте правильность заполнения полей'));
            }
        }
        return $this->redirect(['view', 'id' => $id]);
    }

    public function actionAjaxDelete($id)
    {
        $post = Yii::$app->request->post();

        if (Yii::$app->request->isAjax && isset($post['kvdelete'])) {
            if($this->findModel($id)->delete()){
                echo json_encode([
                    'success' => true,
                    'messages' => [
                        'kv-detail-info' => Yii::t('app', 'Элемент успешно удален') . ' ' . Html::a('вернуться на главную', ['/rooms'])
                    ]
                ]);
            }
        }
    }

    /**
     * Deletes an existing Rooms model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Rooms model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Rooms the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Rooms::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionAjaxGetDetails()
    {
        $post = Yii::$app->request->post();
        $model = Rooms::find()
                    ->joinWith(['venue'])
                    ->joinWith(['venue.city'])
                    ->joinWith(['venue.region'])
                    ->joinWith(['venue.district'])
                    ->where(['rooms.id'=>$post['expandRowKey']])
                    ->one();

        return $this->renderPartial('_expand-row-details', ['model' => $model]);
    }
}

<?php

namespace backend\controllers;

use Yii;
use backend\models\Restaurants;
use backend\models\RestaurantsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RestaurantsController implements the CRUD actions for Restaurants model.
 */
class RestaurantsController extends Controller
{
    /**
     * {@inheritdoc}
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
     * Lists all Restaurants models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RestaurantsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Restaurants model.
     * @param integer $id
     * @param integer $gorko_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $gorko_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id, $gorko_id),
        ]);
    }

    /**
     * Creates a new Restaurants model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Restaurants();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id, 'gorko_id' => $model->gorko_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Restaurants model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @param integer $gorko_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id, $gorko_id)
    {
        $model = $this->findModel($id, $gorko_id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id, 'gorko_id' => $model->gorko_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Restaurants model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @param integer $gorko_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id, $gorko_id)
    {
        $this->findModel($id, $gorko_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Restaurants model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @param integer $gorko_id
     * @return Restaurants the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $gorko_id)
    {
        if (($model = Restaurants::findOne(['id' => $id, 'gorko_id' => $gorko_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

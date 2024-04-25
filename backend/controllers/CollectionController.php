<?php

namespace backend\controllers;

use Yii;
use backend\models\FormRequest;
use backend\models\Collection;
use backend\models\CollectionSearch;
use backend\models\CollectionVenueVia;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use himiklab\sortablegrid\SortableGridView;
use himiklab\sortablegrid\SortableGridAction;
use yii\data\ActiveDataProvider;

/**
 * CollectionController implements the CRUD actions for Collection model.
 */
class CollectionController extends Controller
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

    public function actions()
    {
        return [
            'sort' => [
                'class' => SortableGridAction::className(),
                'modelName' => CollectionVenueVia::className(),
            ],
        ];
    }

    /**
     * Lists all Collection models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CollectionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Collection model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $dataProvider = new ActiveDataProvider([
            'query' => CollectionVenueVia::find()->where(['collection_id'=>$id])->orderBy(['active' => SORT_DESC, 'sort' => SORT_ASC]),
            'pagination' => false,
            'sort' => false,
        ]);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Collection model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Collection();

        if ($model->load(Yii::$app->request->post())) {
            if(!$model->save()){
                echo '<pre>';
                print_r($model->errors);
                die;
            }
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Collection model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Collection model.
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
     * Finds the Collection model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Collection the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Collection::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }



    public function actionCreateFromRequest($id)
    {
        $request = FormRequest::findOne($id);
        $model = new Collection();
        $model->name = $request->nameRequest;
        $model->date = $request->dateRequest;
        $model->phone = $request->phoneRequest;
        $model->spec_id = $request->specRequest;
        $model->region_ids = [ 0 => $request->regionRequest ];
        $model->guest_id = $request->guestRequest;
        $model->price_person_id = $request->pricePersonRequest;
        $model->contact_type_id = $request->contactTypeRequest;
        $model->form_request_id = $id;

        if ($model->load(Yii::$app->request->post()) and $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionAjaxGetData()
    {
		// Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $post = Yii::$app->request->post();
        if(!Yii::$app->request->isAjax or empty($post))
            return false;

        $request = FormRequest::findOne($post['form_request_id']);
        
        if(empty($request))
            return false;

        $model = [];
        $model['name'] = $request->nameRequest;
        $model['date'] = $request->dateRequest;
        $model['phone'] = $request->phoneRequest;
        $model['spec_id'] = $request->specRequest;
        $model['region_ids'] = [ 0 => $request->regionRequest ];
        $model['guest_id'] = $request->guestRequest;
        $model['price_person_id'] = $request->pricePersonRequest;
        $model['contact_type_id'] = $request->contactTypeRequest;

        return json_encode($model);
    }



    public function actionSort()
    {
        if (Yii::$app->request->isAjax) {
            $items = json_decode(Yii::$app->request->post('CollectionVenueVia')['items'], true);
            foreach ($items as $index => $item) {
                $replace =  CollectionVenueVia::findOne($index);
                $model = CollectionVenueVia::findOne($item);
                $model->sort = $replace->sort;
                $model->save(false);
            }
            
            return true;
        }
        
        return false;
    }


    public function actionAjaxSetVenue()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        $post = Yii::$app->request->post();
		$model = CollectionVenueVia::findOne($post['id']);
        $model->active = ($model->active === 1) ? 0 : 1;
        $model->save();

		return true;
    }

    public function actionAjaxSetAllVenue()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        $post = Yii::$app->request->post();
        $active = $post['set'] == 'true' ? 1 : 0;
        CollectionVenueVia::updateAll(['active' => $active], ['collection_id' => $post['collection_id']]);

		return true;
    }

    public function actionRefreshVenues($id)
    {
        $model = Collection::findOne($id);
        $model->save();

        return $this->redirect(['view', 'id'=>$id]);
    }

    
    public function actionAddVenue($id)
    {
        $model = $this->findModel($id);
     
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post('Collection');
            if($post['collection_venue_via']){
                $find = CollectionVenueVia::find()->where(['collection_id'=>$id])->max('sort');
                $sort = (int)$find + 1;
                foreach($post['collection_venue_via'] as $venue){
                    if(!CollectionVenueVia::find()->where(['collection_id'=>$id, 'venue_id'=>$venue])->one()){
                        $collection_via = new CollectionVenueVia();
                        $collection_via->collection_id = $id;
                        $collection_via->venue_id = $venue;
                        $collection_via->sort = $sort;
                        $collection_via->save();
                        $sort++;
                    }
                }
            }

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('add-venue', [
            'model' => $model,
        ]);
    }


}

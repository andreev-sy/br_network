<?php

namespace backend\controllers;

use Yii;
use backend\models\Rooms;
use backend\models\Images;
use backend\models\ImagesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ImagesController implements the CRUD actions for Images model.
 */
class ImagesController extends Controller
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
     * Lists all Images models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ImagesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Images models.
     * @return mixed
     */
    public function actionVenues()
    {
        $searchModel = new ImagesSearch();
        $searchModel->venues_images = 1;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Images models.
     * @return mixed
     */
    public function actionRooms()
    {
        $searchModel = new ImagesSearch();
        $searchModel->rooms_images = 1;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Images model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Images model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Images();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Images model.
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
     * Deletes an existing Images model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    
	public function actionAjaxSetVenue()
	{
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        $post = Yii::$app->request->post();
		$image = Images::findOne($post['id']);
        $image->venue_id = $post['set'] == 'true' ? $post['venue_id'] : null;
        if(!$image->save()){
            return $image->errors;
        }

		return true;
	}

	public function actionAjaxDelete()
	{
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $post = Yii::$app->request->post();

		$image = Images::findOne($post['key']);

		Images::updateSortIndex($image);

		// $webp = str_replace($image->subpath, $image->webppath, $image->realpath);

		// if( file_exists($webp) ) unlink($webp);
        // if( file_exists($image->realpath) ) unlink($image->realpath);
			
		if($image->delete()) return ['success' => 'Удалено'];
 
		return ['error' => 'Ошибка загрузки'];
	}

    public function actionAjaxDragfile()
	{
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $post = Yii::$app->request->post();
		$currentImageID = $post['previewId'];
		$newIndex = $post['newIndex']+1;
		$oldIndex = $post['oldIndex']+1;
		$stack = $post['stack'];

        $image = Images::findOne($currentImageID);
		$roomId = $image->room_id;
        
		$room = Rooms::findOne($roomId);

		$log = '';

		$images = Images::find()->where(['room_id' => $room->id])->all();

		if ($newIndex > $oldIndex) {
			$log .= 'moving forward; ';

			foreach ($images as $image) {
				$log .= '$image->sort = ' . $image->sort . '; ';
				if ($image->sort > $oldIndex && $image->sort <= $newIndex) {
					$log .= 'notCurrentChange; ';
					$image->sort = $image->sort - 1;
					$image->save();
				} elseif ($image->sort == $oldIndex) {
					$log .= 'currentImage change sort index; ';
					$image->sort = $newIndex;
					$image->save();
				}
			}
		} elseif ($newIndex < $oldIndex) {

			$log .= 'moving back; ';

			foreach ($images as $image) {
				$log .= '$image->sort = ' . $image->sort . '; ';
				if ($image->sort >= $newIndex && $image->sort < $oldIndex) {
					$log .= 'notCurrentChange; ';
					$image->sort = $image->sort + 1;
					$image->save();
				} elseif ($image->sort == $oldIndex) {
					$log .= 'currentImage change sort index; ';
					$image->sort = $newIndex;
					$image->save();
				}
			}
		}

		return [
			'currentImageID' => $currentImageID,
			'imagePansionID' => $roomId,
			'log' => $log,
		];
	}

    /**
     * Finds the Images model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Images the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Images::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

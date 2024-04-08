<?php

namespace backend\controllers;

use Yii;
use backend\models\Message;
use backend\models\SourceMessage;
use backend\models\MessageSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MessageController implements the CRUD actions for Message model.
 */
class MessageController extends Controller
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
     * Lists all Message models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(Yii::$app->request->isAjax){
            $post = Yii::$app->request->post();
            if(!empty($post['hasEditable'])){
                $index = $post['editableIndex'];
                $data = json_decode($post['editableKey'], true);
                $model = $this->findModel($data['id'], $data['language']);
                $model->translation = (string)$post['Message'][$index]['translation'];
                $model->save();
                return true;
            }
        }

        $searchModel = new MessageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionImport()
    {
        // Message::updateAll(['translation' => null]);
        // die;
        $post = Yii::$app->request->post();
        
        if (!empty($post['data'])) {
            $data = explode("\n", $post['data']);
            foreach($data as $item){
                $explode = explode(' => ', $item);
                $message = trim($explode[0]);
                $translation = trim($explode[1]);

                $model = SourceMessage::findOne(['message'=>$message]);
                if(!empty($model)){
                    $message = Message::findOne(['id' => $model->id, 'language'=>'pt-BR']);
                    $message->translation = $translation;
                    $message->save();
                }
            }

            return $this->redirect(['index']);
        } 
        
        return $this->render('import');
    }

    public function actionExport()
    {
        $model = Message::find()->where(['translation'=>null])->with('id0')->all();

        $data = '';
        foreach ($model as $row) {
            $data .= "{$row->id0->message}\n";
        }

        $tempFilePath = tempnam(sys_get_temp_dir(), 'messages_');
        file_put_contents($tempFilePath, $data);

        return Yii::$app->response->sendFile($tempFilePath, 'messages.txt')->send();
    }

    /**
     * Displays a single Message model.
     * @param integer $id
     * @param string $language
     * @return mixed
     */
    // public function actionView($id, $language)
    // {
    //     return $this->render('view', [
    //         'model' => $this->findModel($id, $language),
    //     ]);
    // }

    /**
     * Creates a new Message model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Message();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            // return $this->redirect(['view', 'id' => $model->id, 'language' => $model->language]);
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Message model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @param string $language
     * @return mixed
     */
    public function actionUpdate($id, $language)
    {
        $model = $this->findModel($id, $language);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            // return $this->redirect(['view', 'id' => $model->id, 'language' => $model->language]);
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Message model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @param string $language
     * @return mixed
     */
    public function actionDelete($id, $language)
    {
        $this->findModel($id, $language)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Message model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @param string $language
     * @return Message the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $language)
    {
        if (($model = Message::findOne(['id' => $id, 'language' => $language])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

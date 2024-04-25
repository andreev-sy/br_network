<?php

namespace backend\controllers;

use backend\models\Venues;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\filters\VerbFilter;
use backend\models\KommoLeads;
use backend\models\KommoLeadsResponseTime;
use backend\models\KommoLeadsSearch;
use yii\data\ArrayDataProvider;
use common\components\KommoCrmAPI;

/**
 * StatController 
 */
class KommoStatController extends Controller
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

   
    public function actionIndex()
    {
        $searchModel = new KommoLeadsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination = false;
        $leads = $dataProvider->getModels();
        
        $data = [];
        $time_ranges = KommoLeadsResponseTime::find()->asArray()->all();
     
        foreach($time_ranges as $range){
            $data[$range['id']] = [
                'response_time_range' => $range['text_ru'],
                'total_verified' => 0,
                'reject_verification_stage' => 0,
                'total_sent_collections' => 0,
                'reject_request_sent_stage' => 0,
                'total_selected_our_collection' => 0,
                'reject_selected_our_collection_stage' => 0,
                'total_meetings_venues' => 0,
                'reject_meetings_venues_stage' => 0,
                'total_rented_room_from_our_collection' => 0,
                'reject_rented_room_from_our_collection' => 0,
                'total_events' => 0,
                'reject_events' => 0,
            ];
        }
       
        if(!empty($leads)){
            foreach($leads as $lead){
                if(empty($lead->response_time_id)) continue;
                $index = $lead->response_time_id;
                $data[$index]['total_verified'] += $lead->isVerify();
                $data[$index]['reject_verification_stage'] += $lead->isRejectVerificationStage();
                $data[$index]['total_sent_collections'] += $lead->isSentCollections();
                $data[$index]['reject_request_sent_stage'] += $lead->isRejectRequestSentStage();
                $data[$index]['total_selected_our_collection'] += $lead->isSelectedOurCollection();
                $data[$index]['reject_selected_our_collection_stage'] += $lead->isRejectSelectedOurCollectionStage();
                $data[$index]['total_meetings_venues'] += $lead->isMeetingsVenues();
                $data[$index]['reject_meetings_venues_stage'] += $lead->isRejectMeetingsVenuesStage();
                $data[$index]['total_rented_room_from_our_collection'] += $lead->isRentedRoom();
                $data[$index]['reject_rented_room_from_our_collection'] += $lead->isRejectRentedRoom();
                $data[$index]['total_events'] += $lead->isTotalEvents();
                $data[$index]['reject_events'] += $lead->isRejectEvents();
            }
        }

        $arrayDataProvider = new ArrayDataProvider([
            'allModels' => $data,
        ]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'arrayDataProvider' => $arrayDataProvider,
        ]);
    }
   
    public function actionRefresh()
    {
        if(KommoLeads::refreshStat()){
            Yii::$app->session->setFlash('success', Yii::t('app', 'Статистика обновлена'));
        }else{
            Yii::$app->session->setFlash('danger', Yii::t('app', 'Не удалось обновить статистику'));
        }

        return $this->redirect('index');
    }

    public function actionAuth()
    {
    }

}

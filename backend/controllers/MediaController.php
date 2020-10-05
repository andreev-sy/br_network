<?php

namespace backend\controllers;

use common\models\siteobject\Media;
use common\models\siteobject\SiteObjectMedia;
use common\models\siteobject\SiteObjectMediaTarget;
use Yii;
use yii\web\Controller;
use yii\web\HttpException;

/**
 * This is the class for controller "MediaController".
 */
class MediaController extends Controller
{
    public $enableCsrfValidation = false;
    public function actionUpload()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $mediaTarget = SiteObjectMediaTarget::findOne((Yii::$app->request->post('media_target_id', null)));

        if ($mediaTarget) {
            try {
                reset($_FILES);
                $temp = current($_FILES);
                $media = Media::upload($temp, $mediaTarget);
                $attach = $mediaTarget->attachMedia($media);
            } catch (\Exception $e) {
                return ['error' => $e->getMessage()];
            }

            if (!empty($attach)) {
                $initialPreview[] = $media->getWebFileLink();
                $initialPreviewThumbTags[] = [
                    '{desc}' => '',
                    '{upd}' => '/media/' . $attach->id . '/title/', //TODO
                ];
                return [
                    'initialPreview' => $initialPreview,
                    'initialPreviewConfig' => [[
                        'caption' => $media->getSystemPath(),
                        'url' => '/media/' . $attach->id . '/delete/',
                        'res_id' => $attach->id,
                        'key' => $attach->id,
                        'type' => $media->getFileTypeForPreview(),
                        'previewAsData' => true
                    ]],
                    'initialPreviewThumbTags' => $initialPreviewThumbTags,
                    'append' => true
                ];
            } else return ['error' => 'Ошибка загрузки'];
        } else return ['error' => 'Mediatarget missing'];
    }

    public function actionAttach()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $mediaTarget = SiteObjectMediaTarget::findOne((Yii::$app->request->post('media_target_id', null)));
        if ($mediaTarget) {
            try {
                $media = Media::findOne((Yii::$app->request->post('media_id', null)));
                $attach = $mediaTarget->attachMedia($media);
            } catch (\Exception $e) {
                return ['error' => $e->getMessage()];
            }
            return ['error' => ''];
        }
        return ['error' => 'mediaTarget not found'];
    }

    public function actionFastView()
    {

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $initialPreview = [];
        $initialPreviewThumbTags = [];
        $initialPreviewConfig = [];

        $mediaTarget = SiteObjectMediaTarget::findOne((Yii::$app->request->post('media_target_id', null)));

        foreach ($mediaTarget->siteObjectMedia as $res) {
            $initialPreview[] = $res->media->getWebFileLink();
            $initialPreviewThumbTags[] = [
                '{desc}' => (string) $res->description,
                '{upd}' => '/media/' . $res->id . '/title/', //TODO
            ];
            $initialPreviewConfig[] = [
                'caption' => $res->media->getSystemPath(),
                'width' => '120px',
                'url' => '/media/' . $res->id . '/delete/',
                'res_id' => $res->id,
                'key' => $res->id,
                'type' => $res->media->getFileTypeForPreview(),
                'previewAsData' => true
            ];
        };

        $response =
            [
                'media_target_id' => $mediaTarget->id,
                'initialPreview' => $initialPreview,
                'initialPreviewConfig' => $initialPreviewConfig,
                'initialPreviewThumbTags' => $initialPreviewThumbTags,
                'append' => true,
            ];
        return $response;
    }

    public function actionDelete($id)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $siteObjectMedia = SiteObjectMedia::findOne(['id' => $id]);
        /** @var Media */

        if (!$siteObjectMedia->delete()) {
            return ['error' => 'Ошибка удаления объекта'];
        };

        if ($siteObjectMedia->getMedia()->one()) {
            return ['success' => 'Удалено для этого объекта, но у файла остались связи'];
        }
        return ['success' => 'Удалено'];
    }

    public function actionTitle($id)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $siteObjectMedia = SiteObjectMedia::findOne(['id' => $id]);
        $siteObjectMedia->description = \Yii::$app->request->post('text', '');
        $siteObjectMedia->save();
    }

    public function actionResort()
    {
        $newSort = \Yii::$app->request->post('newSort', []);

        foreach ($newSort as $sortObj) {
            $siteObjectMedia = SiteObjectMedia::findOne($sortObj['res_id']);
            if ($siteObjectMedia && $siteObjectMedia->sort != $sortObj['new_sort']) {
                $siteObjectMedia->sort = $sortObj['new_sort'];
                $siteObjectMedia->save();
            }
        }
    }

    /**
     * Finds the Media model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @throws HttpException if the model cannot be found
     * @param integer $id
     * @return Media the loaded model
     */
    protected function findModel($id)
    {
        if (($model = Media::findOne($id)) !== null) {
            return $model;
        } else {
            throw new HttpException(404, 'The requested page does not exist.');
        }
    }
}

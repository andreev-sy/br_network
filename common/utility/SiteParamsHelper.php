<?php

namespace common\utility;

class SiteParamsHelper
{
    public static function getParamsForModule($moduleName)
    {
        $uploadFolder = 'upload';
        $uploadSystemPath = \yii\helpers\Url::to(
            implode(
                DIRECTORY_SEPARATOR,
                ['@frontend', 'modules', $moduleName, 'web', $uploadFolder]
            )
        );
        $mediaEnumClass = class_exists("frontend\modules\\$moduleName\models\MediaEnum") ?
            "frontend\modules\\$moduleName\models\MediaEnum" :
            null;
        return  [
            'mediaEnumClass' =>  $mediaEnumClass,
            'uploadFolder' => $uploadFolder,
            'uploadSystemPath' => $uploadSystemPath,
        ];
    }

    public static function getGlobalSiteParams()
    {
        if(empty($_SERVER['HTTP_HOST'])) {
            return [];
        }
        $hostParts = explode('.', $_SERVER['HTTP_HOST']);
        $hostParts = array_reverse($hostParts);
        $siteAddress = $hostParts[1] . '.' . $hostParts[0];
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
        return [
            'siteAddress' => $siteAddress,
            'siteProtocol' => $protocol
        ];
    }
}

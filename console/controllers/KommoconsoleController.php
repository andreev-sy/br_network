<?php

namespace console\controllers;

use yii\console\Controller;
use backend\models\KommoLeads;

class KommoconsoleController extends Controller
{
	public function actionLeadsRefreshStat()
	{
		KommoLeads::refreshStat();
	}
}

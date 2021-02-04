<?php

namespace frontend\components;

use Yii;

class Breadcrumbs {
	public static function get_breadcrumbs($level) {
		switch ($level) {
			case 1:	
				$breadcrumbs=[
					'/' => 'Свадьба на природе',
				];
				break;
			case 2:
				$breadcrumbs=[
					'/' => 'Свадьба на природе',
					'/catalog/' => 'Банкетные залы',
				];
				break;
		}
		return $breadcrumbs;
	}
}
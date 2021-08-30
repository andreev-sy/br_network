<?php

namespace frontend\components;

use Yii;

class Slicer {

	public static function banketvsamare($num) {
		$capacity_link = '';
		
		switch(true) {
			case in_array($num, range(0, 10)):
				$capacity_link = '10-chelovek';
			break;
			case in_array($num, range(10,30)):
				$capacity_link = '30-chelovek';
			break;
			case in_array($num, range(30,50)):
				$capacity_link = '50-chelovek';
			break;
			case in_array($num, range(50,70)):
				$capacity_link = '70-chelovek';
			break;
			case in_array($num, range(70,100)):
				$capacity_link = '100-chelovek';
			break;
			case $num > 100:
				$capacity_link = 'bolee-100-chelovek';
			break;
		}

		return '/'.$capacity_link.'/';
	}

}
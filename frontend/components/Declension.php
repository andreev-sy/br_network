<?php

namespace frontend\components;

use Yii;

class Declension {
	public static function end_restaurants($num) {
		$ost=$num%10;
		$ost100 = $num%100;
		if (($ost100<10 || $ost100>20) && $ost!=0) {
			switch ($ost) {
				case 1:	$end='';	break;
				case 2:
				case 3:
				case 4: $end='а'; break;
				case 5:
				case 6:
				case 7:
				case 8:
				case 9:	$end='ов'; break;
			}
		} else $end='ов';
		return $end;
	}

	public static function end_rooms($num) {
		$ost=$num%10;
		$ost100 = $num%100;
		if (($ost100<10 || $ost100>20) && $ost!=0) {
			switch ($ost) {
				case 1:	$end='';	break;
				case 2:
				case 3:
				case 4: $end='а'; break;
				case 5:
				case 6:
				case 7:
				case 8:
				case 9:	$end='ов'; break;
			}
		} else $end='ов';
		return $end;
	}
}
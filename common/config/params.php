<?php
return [
	'mysql_config'		=> [
		'username' => 'root',
		'password' => 'GxU25UseYmeVcsn5Xhzy',
		'charset'  => 'utf8mb4',
	],
	'main_api_config'	=> [
		'mysql_config' => [
			'dsn' => 'mysql:host=localhost;dbname=pmn'
		]		
	],
    'module_api_config' => [
    	'graduation' => [
			'mysql_config' => [
				'dsn' 			=> 'mysql:host=localhost;dbname=pmn_graduation',
			],
			'params' 	=> [
				'watermark' 	=> '/var/www/pmnetwork/frontend/web/img/watermark-graduation.png',
				'imageHash' 	=> 'graduation',
				'subdomens' 	=> true,
				'subdomen' 		=> null,
				'module_path' 	=> 'frontend\modules\graduation',
				'only_comm'		=> true,
				'gorko_api'		=> [
					'phone_key'		=> 'graduation',
					'channel_key'	=> 'graduation',
					'city' 			=> false,
				],
			]
		],
    	'korporativ' => [
			'mysql_config' => [
				'dsn' 			=> 'mysql:host=localhost;dbname=pmn_gorko_ny',
			],
			'params' 	=> [
				'watermark' 	=> '/var/www/pmnetwork/frontend/web/img/ny_ball.png',
				'imageHash' 	=> 'newyearpmn',
				'subdomens' 	=> true,
				'subdomen' 		=> null,
				'module_path' 	=> 'frontend\modules\gorko_ny',
				'only_comm'		=> true,
				'gorko_api'		=> [
					'phone_key'		=> 'korporativ-ng',
					'channel_key'	=> 'korporativ-ng',
					'city' 			=> false,
				],
			]
		],
    	'drnaprirode' => [
			'mysql_config' => [
				'dsn' 			=> 'mysql:host=localhost;dbname=pmn_priroda_dr',
			],
			'params' 	=> [
				'watermark' 	=> '/var/www/pmnetwork/frontend/web/img/watermark-drnaprirode.png',
				'imageHash' 	=> 'drnaprirode',
				'subdomens' 	=> true,
				'subdomen' 		=> null,
				'module_path' 	=> 'frontend\modules\priroda_dr',
				'only_comm'		=> false,
				'gorko_api'		=> [
					'phone_key'		=> 'drnaprirode',
					'channel_key'	=> 'drnaprirode',
					'city' 			=> false,
				],
			]
		],
		'arenda' => [
			'mysql_config' => [
				'dsn' 			=> 'mysql:host=localhost;dbname=pmn_arenda',
			],
			'params' 	=> [
				'watermark' 	=> '/var/www/pmnetwork/frontend/web/img/watermark-arenda.png',
				'imageHash' 	=> 'arenda',
				'subdomens' 	=> true,
				'subdomen' 		=> null,
				'module_path' 	=> 'frontend\modules\arenda',
				'only_comm'		=> false,
				'gorko_api'		=> [
					'phone_key'		=> 'arendazala',
					'channel_key'	=> 'arenda',
					'city' 			=> false,
				],
			]
		],
		'svadbanaprirode' => [
			'mysql_config' => [
				'dsn' 			=> 'mysql:host=localhost;dbname=pmn_svadbanaprirode',
			],
			'params' 	=> [
				'watermark' 	=> '/var/www/pmnetwork/frontend/web/img/watermark-svadbanaprirode.png',
				'imageHash' 	=> 'svadbanaprirode',
				'subdomens' 	=> false,
				'subdomen' 		=> null,
				'module_path' 	=> 'frontend\modules\svadbanaprirode',
				'only_comm'		=> true,
				'gorko_api'		=> [
					'phone_key'		=> 'svadbanaprirode',
					'channel_key'	=> 'svadbanaprirode',
					'city' 			=> 4400,
				],
			]
		]
	],
];

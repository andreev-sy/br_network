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
    	'banketvsamare' => [
    		'elastic' 	   => [
    			'index' 		=> 'pmn_banketvsamare_restaurants',
    			'type'			=> 'items'
    		],
			'mysql_config' => [
				'dsn' 			=> 'mysql:host=localhost;dbname=pmn_banketvsamare',
			],
			'params' 	   => [
				'watermark' 	=> '/var/www/pmnetwork/frontend/web/img/watermark_banketvsamare.png',
				'watermark_pos' => 8,
				'imageHash' 	=> 'banketvsamare',
				'slug_city'		=>[
					4917 =>[
						'slug' => '-v-samare',
						'yandex_id' => 'data-yandex-id="84074572"'
					],
					4400 =>[
						'slug' => '-v-moskve',
						'yandex_id' => 'data-yandex-id="86984317"'
					],
					5269 =>[
						'slug' => '-v-kazani',
						'yandex_id' => 'data-yandex-id="86418943"'
					],
					3612 =>[
						'slug' => '-v-nn',
						'yandex_id' => 'data-yandex-id="86350805"'
					],
					4962 =>[
						'slug' => '-v-spb',
						'yandex_id' => 'data-yandex-id="87018223"'
					],
				],
				'watermark_city'=>[
					4917 =>[
						'watermark' 	=> '/var/www/pmnetwork/frontend/web/img/watermark_banketvsamare.png',
						'watermark_pos' => 8,
						'imageHash' 	=> 'banketvsamare',
					],
					4400 =>[
						'watermark' 	=> '/var/www/pmnetwork/frontend/web/img/watermark_banketvmoskve.png',
						'watermark_pos' => 8,
						'imageHash' 	=> 'banketvmoskve',
					],
					5269 =>[
						'watermark' 	=> '/var/www/pmnetwork/frontend/web/img/watermark_banketyvkazani.png',
						'watermark_pos' => 8,
						'imageHash' 	=> 'banketyvkazani',
					],
					3612 =>[
						'watermark' 	=> '/var/www/pmnetwork/frontend/web/img/watermark_banketvnn.png',
						'watermark_pos' => 8,
						'imageHash' 	=> 'banketvnn',
					],
					4962 =>[
						'watermark' 	=> '/var/www/pmnetwork/frontend/web/img/watermark_banketyvspb.png',
						'watermark_pos' => 8,
						'imageHash' 	=> 'banketyvspb',
					],
				],
				'subdomens' 	=> false,
				'subdomen' 		=> null,
				'module_path' 	=> 'frontend\modules\banketvsamare',
				'only_comm'		=> true,
				'gorko_api'		=> [
					'phone_key'		=> 'banketvsamare',
					'channel_key'	=> 'banketvsamare',
					'city' 			=> 4917,
				],
			]
		],
    	'birthday' => [
			'mysql_config' => [
				'dsn' 			=> 'mysql:host=localhost;dbname=pmn_bd',
			],
			'params' 	=> [
				'watermark' 	=> '/var/www/pmnetwork/frontend/web/img/watermark.png',
				'watermark_pos' => 9,
				'imageHash' 	=> 'birthdaypmn',
				'subdomens' 	=> true,
				'subdomen' 		=> null,
				'module_path' 	=> 'frontend\modules\pmnbd',
				'only_comm'		=> true,
				'gorko_api'		=> [
					'phone_key'		=> 'birthday-place',
					'channel_key'	=> 'birthday-place',
					'city' 			=> false,
				],
			]
		],
		'kidsbd' => [
			'mysql_config' => [
				'dsn' 			=> 'mysql:host=localhost;dbname=pmn_kidsbd',
			],
			'params' 	=> [
				'watermark' 	=> '/var/www/pmnetwork/frontend/web/img/watermark-kidsbd.png',
				'watermark_pos' => 9,
				'imageHash' 	=> 'kidsbdpmn',
				'subdomens' 	=> true,
				'subdomen' 		=> null,
				'module_path' 	=> 'frontend\modules\kidsbd',
				'only_comm'		=> true,
				'gorko_api'		=> [
					'phone_key'		=> 'kidsbd',
					'channel_key'	=> 'kidsbd',
					'city' 			=> false,
				],
			]
		],
    	'graduation' => [
			'mysql_config' => [
				'dsn' 			=> 'mysql:host=localhost;dbname=pmn_graduation',
			],
			'params' 	=> [
				'watermark' 	=> '/var/www/pmnetwork/frontend/web/img/watermark-graduation.png',
				'watermark_pos' => 9,
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
    		'elastic' 	   => [
    			'index' 		=> 'pmn_ny_restaurants',
    			'type'			=> 'items'
    		],
    		'url' => 'korporativ-ng',
			'mysql_config' => [
				'dsn' 			=> 'mysql:host=localhost;dbname=pmn_gorko_ny',
			],
			'params' 	=> [
				'watermark' 	=> '/var/www/pmnetwork_dev/frontend/web/img/ny_ball.png',
				'watermark_pos' => 9,
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
				'watermark_pos' => 9,
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
				'watermark_pos' => 9,
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
				'watermark_pos' => 9,
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
		],
		'banketnye-zaly-moskva' => [
			'mysql_config' => [
				'dsn' 			=> 'mysql:host=localhost;dbname=pmn_bzm',
			],
			'params' 	=> [
				'watermark' 	=> '/var/www/pmnetwork/frontend/web/img/watermark-bzm.png',
				'watermark_pos' => 1,
				'imageHash' 	=> 'banketmoscow',
				'subdomens' 	=> false,
				'subdomen' 		=> null,
				'module_path' 	=> 'frontend\modules\banketnye_zaly_moskva',
				'only_comm'		=> true,
				'gorko_api'		=> [
					'phone_key'		=> 'banketnye-zaly-moskva',
					'channel_key'	=> 'banketnye-zaly-moskva',
					'city' 			=> 4400,
				],
			]
		],
		'so_svoim' => [
			'mysql_config' => [
				'dsn' 			=> 'mysql:host=localhost;dbname=pmn_so_svoim',
			],
			'params' 	=> [
				'watermark' 	=> false,
				'watermark_pos' => 9,
				'imageHash' 	=> 'so_svoim',
				'subdomens' 	=> true,
				'subdomen' 		=> null,
				'module_path' 	=> 'frontend\modules\so_svoim',
				'only_comm'		=> true,
				'gorko_api'		=> [
					'phone_key'		=> 'so_svoim',
					'channel_key'	=> 'so_svoim',
					'city' 			=> false,
				],
			]
		],
		'top_banket' => [
			'elastic' 	   => [
    			'index' 		=> 'pmn_top_banket',
    			'type'			=> 'items'
    		],
			'mysql_config' => [
				'dsn' 			=> 'mysql:host=localhost;dbname=pmn_top_banket',
			],
			'params' 	=> [
				'watermark' 	=> false,
				'watermark_pos' => 9,
				'imageHash' 	=> 'top_banket',
				'subdomens' 	=> true,
				'subdomen' 		=> null,
				'module_path' 	=> 'frontend\modules\top_banket',
				'only_comm'		=> true,
				'gorko_api'		=> [
					'phone_key'		=> 'top_banket',
					'channel_key'	=> 'top_banket',
					'city' 			=> false,
				],
			]
		]
	],
];

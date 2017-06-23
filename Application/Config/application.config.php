<?php
//配置文件
return [
    'db'=>[//保存数据库信息
        'host'=>'127.0.0.1',
        'username'=>'root',
        'password'=>'root',
        'dbname'=>'myshop',
        'port'=>3306,
        'charset'=>'utf8',
        'prefix'=>''
    ],
    'default'=>[//url默认参数
        'default_platform'=>'Admin',
        'default_controller'=>'Login',
        'default_action'=>'login',
    ]
];
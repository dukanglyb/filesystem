<?php

return new \Phalcon\Config(
  array(
    'server' => array(
        'tfs'=>array(
          'nginx-tfs'=>
              array(
                'ip'=>'tfs.com',
                'port'=>'8080',
              ),
          'appkey'=>'tappkey00001',
          'appid'=>1,
        ),
    ),
    'application'       => array(
    'modelsDir'         => __DIR__ . '/../../app/models/', //用于指示代码生成器models目录在哪里
    'controllersDir'   => __DIR__ . '/../../app/controllers/',//用于指示代码生成器controllersDir目录在哪里
    'baseUri'           => '/',
    'cryptKey'          => '#ldjB$=dp?.ak//j1V$a!d#d',// 只支持16, 24 或 32位
    'registerDir'      =>array(
        'default'          => array(
            'modelsDir'        => __DIR__ . '/../../app/models/',
            'controllersDir'  => __DIR__ . '/../../app/controllers/',
            'pluginsDir'       => __DIR__ . '/../../app/plugins/',
            'libraryDir'       => __DIR__ . '/../../app/library/',
            'viewsDir'          => __DIR__ . '/../../app/views/',
            ),
      ),
      //是否debug模式
      'debug'          => true,
      //高级调试模式开关。
      'SeniorDebug' => false,
    ),
));

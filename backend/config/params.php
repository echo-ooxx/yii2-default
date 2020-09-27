<?php
return [
    'adminEmail' => 'admin@example.com',
    /* 上传文件 */
    'upload' => [
//        'url'  => Yii::getAlias('@storageUrl/image/'),
        'url' => Yii::getAlias('@url').'/upload/',
        'kvurl' => Yii::getAlias('@url'),
        //'path' => Yii::getAlias('@base/web/storage/image/'), // 服务器解析到/web/目录时，上传到这里
        'path' => 'upload/image/{yyyy}{mm}{dd}/input/{time}{rand:6}',
        'rootPath' => Yii::getAlias('@frontend'),
        'fileMaxSize' => 20 * 1024 * 1024 * 1024,
        'fileAllowExt' => [
            '.png',
            '.jpg',
            '.jpeg',
            '.gif'
        ]
    ],
    'ueditorConfig' => [
        /* 图片上传配置 */
        'imageRoot'            => Yii::getAlias("@frontend/web/upload/"), //图片path前缀
        'imageUrlPrefix'       => Yii::getAlias('@url'),
        'imagePathFormat'      => 'upload/image/{yyyy}{mm}{dd}/editor/{time}{rand:6}',

        /* 文件上传配置 */
        'fileRoot'             => Yii::getAlias("@frontend/web/upload/"), //文件path前缀
        'fileUrlPrefix'        => Yii::getAlias('@url'),
        'filePathFormat'       => 'upload/file/{yyyy}{mm}{dd}/editor/{rand:4}_{filename}',

        /* 上传视频配置 */
        'videoRoot'            => Yii::getAlias("@frontend/web/upload/"),
        "videoUrlPrefix"       => Yii::getAlias('@url'),
        'videoPathFormat'      => 'upload/video/{yyyy}{mm}{dd}/editor/{time}{rand:6}',

        /* 涂鸦图片上传配置项 */
        'scrawlRoot'           => Yii::getAlias("@frontend/web/upload/"),
        "scrawlUrlPrefix"      => Yii::getAlias('@url'),
        'scrawlPathFormat'     => 'upload/image/{yyyy}{mm}{dd}/editor/{time}{rand:6}',
    ],
];

<?php

define('DEFAULT_LANG', 'en_US');
define('API_ADDRESS', Yaconf::get('kit.api.remote'));
define('API_VERSION', Yaconf::get('kit.api.version', '1.0.0'));

//Rewrite the helper tool to support multiple level response data.
Yii::$classMap['yii\base\ArrayableTrait'] = '@api/rest/polyfill/base/ArrayableTrait.php';
Yii::$classMap['yii\helpers\ArrayHelper'] = '@api/rest/polyfill/helpers/ArrayHelper.php';

//disable page validate, because the app will call as normal,
//we should not return the last page data.
Yii::$container->set('yii\data\Pagination', [
    'validatePage' => false,
]);
Yii::$container->set(yii\data\ActiveDataProvider::class, common\components\data\ActiveDataProvider::class);

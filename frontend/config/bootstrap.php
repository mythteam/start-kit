<?php
defined('YII_DEBUG') or define('YII_DEBUG', Yaconf::get('kit.api.debug', 'false') === 'true');
defined('YII_ENV') or define('YII_ENV', Yaconf::get('kit.api.env', 'prod'));

Yii::setAlias('@cdn', FRONT_URL);

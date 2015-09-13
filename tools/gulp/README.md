### 依赖

* NodeJS
* Gulp
* Bower

### 初始化

1. cd tools/gulp
2. npm i

### 执行

```
yii asset/template tools/gulp/frontend-assets-config.php

yii asset tools/gulp/assets-config.php frontend/config/assets-bundles.php
```

配置 AssetManager

```
'assetManager' => [
    'bundles' => YII_DEBUG ? [] : require 'assets-bundles.php',
]
```
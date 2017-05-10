<?php

namespace common\models\queries;

use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[\common\models\Webmaster]].
 *
 * @see \common\models\Webmaster
 */
class WebmasterQuery extends ActiveQuery
{
    /**
     * description.
     *
     * @return \yii\db\ActiveQuery
     */
    public function active()
    {
        $this->andWhere('[[status]]=1');

        return $this;
    }
}

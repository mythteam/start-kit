<?php

namespace common\components\querys;

/**
 * This is the ActiveQuery class for [[\common\models\Webmaster]].
 *
 * @see \common\models\Webmaster
 */
class WebmasterQuery extends \yii\db\ActiveQuery
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

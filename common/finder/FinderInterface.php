<?php

namespace common\finder;

interface FinderInterface
{
    /**
     * Find the model by primary key.
     *
     * @param int|array $id
     *
     * @return mixed
     */
    public function one($id);

    /**
     * Get the model activeQuery object.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getQuery();
}

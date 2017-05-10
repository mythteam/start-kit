<?php

namespace api\rest;

class Serializer extends \yii\rest\Serializer
{
    /**
     * Serialize model validation errors.
     *
     * @param \yii\base\Model $model
     *
     * @return string
     */
    protected function serializeModelErrors($model)
    {
        $result = [];
        $error = [];
        foreach ($model->getFirstErrors() as $name => $message) {
            $result[] = [
                'field' => $name,
                'message' => $message,
            ];
            $error[] = $message;
        }
        $this->response->setStatusCode(422, implode(';', $error));

        return $result;
    }

    /**
     * {@inheritdoc}
     *
     * We remove the `_link` element to decrease to data size
     * and the `_meta`, because no use now.
     */
    protected function serializePagination($pagination)
    {
        return [
//            $this->metaEnvelope => [
//                'totalCount' => $pagination->totalCount,
//                'pageCount' => $pagination->getPageCount(),
//                'currentPage' => $pagination->getPage() + 1,
//                'perPage' => $pagination->getPageSize(),
//            ]
        ];
    }

    /**
     * {@inheritdoc}
     *
     * We remove the header set here, because not used now.
     */
    protected function addPaginationHeaders($pagination)
    {
    }
}

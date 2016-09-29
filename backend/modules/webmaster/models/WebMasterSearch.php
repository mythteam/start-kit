<?php

namespace backend\modules\webmaster\models;

use common\models\WebMaster;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * WebMasterSearch represents the model behind the search form about `common\models\WebMaster`.
 */
class WebMasterSearch extends Model
{
    public $status;
    public $is_super;
    public $register_at;
    public $query;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'is_super'], 'integer'],
            [['query', 'register_at'], 'safe'],
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    public function formName()
    {
        return '';
    }

    /**
     * Creates data provider instance with search query applied.
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = WebMaster::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['registed_at' => SORT_DESC],
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'status' => $this->status,
            'is_super' => $this->is_super,
        ]);
    
        $query->andFilterWhere(['like', 'nickname', $this->query]);
    
        if ($this->register_at) {
            $range = explode(' - ', $this->register_at);
            $range = array_map(function ($value) {
                return strtotime($value);
            }, $range);
            $query->andWhere(['between', 'registed_at', $range[0], $range[1] + 86400]);
        }

        return $dataProvider;
    }
}

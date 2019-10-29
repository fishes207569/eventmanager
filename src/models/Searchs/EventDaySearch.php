<?php

namespace ccheng\eventmanager\models\Searchs;

use ccheng\eventmanager\helpers\ConfigHelper;
use ccheng\eventmanager\helpers\DateHelper;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ccheng\eventmanager\models\BizEvent;
use yii\helpers\Url;

/**
 * EventSearch represents the model behind the search form about `backend\models\BizEvent`.
 */
class EventDaySearch extends BizEvent
{
    public $event_date;

    public $event_level;
    public $event_system;
    public $event_tag;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'event_date',
                    'event_level',
                    'event_system'
                ],
                'safe',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'event_tag'        => '事件标签',
        ];
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = BizEvent::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['event_time' => SORT_DESC]],
            'pagination'=>false
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->where([
            'event_date'        => $this->event_date
        ]);
        // grid filtering conditions
        $query->andFilterWhere([
            'event_level'      => $this->event_level,
            'event_from_system'      => $this->event_system,
        ]);
        $query->andFilterWhere(['like','event_tags',$this->event_tag]);
        return [$this->event_date=>$dataProvider->getModels()];
    }
}

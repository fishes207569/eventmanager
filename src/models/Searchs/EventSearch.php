<?php

namespace EventManager\models\Searchs;

use EventManager\helpers\DateHelper;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use EventManager\models\BizEvent;

/**
 * EventSearch represents the model behind the search form about `backend\models\BizEvent`.
 */
class EventSearch extends BizEvent
{
    public $start_date;
    public $end_date;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['event_id', 'event_year'], 'integer'],
            [
                [
                    'event_name',
                    'event_content',
                    'event_month',
                    'event_date',
                    'event_create_at',
                    'event_update_at',
                    'event_from_system',
                    'event_author',
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
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'event_id'        => $this->event_id,
            'event_year'      => $this->event_year,
            'event_date'      => $this->event_date,
            'event_create_at' => $this->event_create_at,
            'event_update_at' => $this->event_update_at,
        ]);

        $query->andFilterWhere(['like', 'event_name', $this->event_name])
            ->andFilterWhere(['like', 'event_content', $this->event_content])
            ->andFilterWhere(['like', 'event_month', $this->event_month])
            ->andFilterWhere(['like', 'event_from_system', $this->event_from_system])
            ->andFilterWhere(['like', 'event_author', $this->event_author]);

        return $dataProvider;
    }

    public function searchHistory($params)
    {
        $query = BizEvent::find();
        $this->load($params);

        $query->where(['event_date' => $this->event_date]);
        $query->select(['event_name','event_date','event_from_system','event_author','event_content','event_image']);
        $data=$query->asArray()->all();
        $group_data=[];
        $event_week=DateHelper::getNowWeeks('','Y-m-d');
        foreach ($event_week as $week){
            $group_data[$week]=[];
            foreach ($data as $val){
                if($week==$val['event_date']){
                    $group_data[$week][]=$val;
                }
            }
        }
        return $group_data;
    }
}

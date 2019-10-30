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
                    'start_date',
                    'end_date',
                    'event_level',
                    'event_tags'
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
        $query->andFilterWhere(['between','event_date',$this->start_date,$this->end_date]);
        $query->andFilterWhere(['like', 'event_name', $this->event_name])
            ->andFilterWhere(['like', 'event_content', $this->event_content])
            ->andFilterWhere(['like', 'event_tags', $this->event_tags])
            ->andFilterWhere(['=', 'event_month', $this->event_month])
            ->andFilterWhere(['=', 'event_from_system', $this->event_from_system])
            ->andFilterWhere(['like', 'event_author', $this->event_author])
            ->andFilterWhere(['=', 'event_level', $this->event_level]);

        return $dataProvider;
    }

    public function searchHistory($params)
    {
        $query = BizEvent::find();
        $this->load($params);

        $query->where(['DATE(event_date)' => $this->event_date]);
        $query->orderBy('event_date');
        $sql=$query->createCommand()->getRawSql();
        $data=$query->asArray()->all();
        $group_data=[];
        foreach ($this->event_date as $week){
            $group_data[$week]=[];
            foreach ($data as $val){
                if($week==$val['event_date']){
                    $group_data[$week][]=$val;
                }
            }
        }
        return $group_data;
    }
    public function searchList($params){
        /** @var  $dataProvider ActiveDataProvider */
        $dataProvider=$this->search($params);
        $dataProvider->pagination=false;
        /** @var  $models \yii\db\ActiveRecord */
        $models=$dataProvider->getModels();

        $Events=[];
        $level_colors=ConfigHelper::getEventLevelConfig('color');
        foreach ($models as $item){
            /** @var  $item BizEvent */
            $Event = new \yii2fullcalendar\models\Event([
                'id'=>$item->event_id,
                'title'=>$item->event_name,
                'start'=>$item->event_date.' '.$item->event_time,
                'backgroundColor'=>$level_colors[$item->event_level],
                'borderColor'=>$level_colors[$item->event_level],
                'url'=>Url::to(['/event/event/detail','EventDaySearch[event_date]'=>$item->event_date])
            ]);
            $Events[]=$Event;
        }
        return $Events;
    }
}

<?php

namespace ccheng\eventmanager\models;

use ccheng\eventmanager\helpers\ImageHelper;
use Yii;
use yii\base\DynamicModel;
use yii\base\UserException;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\validators\ImageValidator;
use yii\web\UploadedFile;

/**
 * This is the model class for table "biz_event".
 *
 * @property integer $event_id
 * @property string  $event_name
 * @property string  $event_content
 * @property string  $event_image
 * @property integer $event_year
 * @property string  $event_month
 * @property string  $event_date
 * @property string  $event_create_at
 * @property string  $event_update_at
 * @property string  $event_from_system
 * @property string  $event_author
 */
class BizEvent extends \yii\db\ActiveRecord
{
    const SYSTEM_BIZ     = 'biz';
    const SYSTEM_RBIZ    = 'rbiz';
    const SYSTEM_GBIZ    = 'gbiz';
    const SYSTEM_CAPITAL = 'capital';
    const SYSTEM_PAYSVR  = 'paysvr';
    const SYSTEM_MAP     = [
        self::SYSTEM_BIZ     => 'BIZ',
        self::SYSTEM_RBIZ    => '还款系统',
        self::SYSTEM_GBIZ    => '放款系统',
        self::SYSTEM_CAPITAL => '清结算',
        self::SYSTEM_PAYSVR  => '支付系统',
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'biz_event';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['event_name', 'event_content', 'event_date'], 'required'],
            [['event_content'], 'string'],
            [['event_year'], 'integer'],
            [['event_date', 'event_create_at', 'event_update_at'], 'safe'],
            [['event_name'], 'string', 'max' => 200],
            [['event_month'], 'string', 'max' => 7],
            [['event_from_system'], 'string', 'max' => 16],
            [['event_author'], 'string', 'max' => 32],
            [
                'event_year',
                'default',
                'value' => function ($model, $attribute) {
                    return $model->$attribute = date('Y', strtotime($model->event_date));
                },
            ],
            [
                'event_month',
                'default',
                'value' => function ($model, $attribute) {
                    return $model->$attribute = date('Y-m', strtotime($model->event_date));
                },
            ],
            ['event_from_system', 'in', 'range' => array_keys(self::SYSTEM_MAP)],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'event_id'          => '事件 ID',
            'event_name'        => '事件名称',
            'event_content'     => '事件内容',
            'event_year'        => '事件所属年',
            'event_image'       => '事件图片',
            'event_month'       => '事件所属月',
            'event_date'        => '事件所属日',
            'event_create_at'   => '添加时间',
            'event_update_at'   => '更新时间',
            'event_from_system' => '来源系统',
            'event_author'      => '添加人员',
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class'      => TimestampBehavior::className(),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => [
                        'event_create_at',
                        'event_update_at',
                    ],
                    \yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => ['event_update_at'],
                ],
                'value'      => new Expression('NOW()'),
            ],
        ];
    }

    public function beforeValidate()
    {
        $upload_img = UploadedFile::getInstanceByName('event_image');
        if ($upload_img) {
            $uploadModel = new DynamicModel(['event_image' => $upload_img]);
            $uploadModel->addRule(['event_image'], ImageValidator::class, [
                'maxWidth'       => 150,
                'maxHeight'      => 150,
                'extensions'     => 'png,jpg,jpeg',
                'uploadRequired' => false,
                'skipOnEmpty'    => true,
            ]);
            if ($uploadModel->validate()) {
                $temp_path = Yii::getAlias('@runtime') . '/event_file_temp/';
                if (!is_dir($temp_path)) {
                    if (!mkdir($temp_path, 0777, true)) {
                        throw new UserException('服务器存储失败，请联系管理员！');
                    }
                }
                $save_path = $temp_path . $upload_img->name;
                if (!$upload_img->saveAs($save_path)) {
                    throw new UserException('服务器存储失败，请联系管理员！');
                }

                $event_image=ImageHelper::img_base64($save_path);
                if ($event_image) {
                        $this->event_image = $event_image;
                } else {
                    $this->addError('event_image', '图像读取异常');
                    return false;
                }
            }else{
                $this->addError('event_image', $uploadModel->getFirstError('event_image'));
                return false;
            }
        }
        return parent::beforeValidate(); // TODO: Change the autogenerated stub
    }
}

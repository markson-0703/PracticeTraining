<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "activity_details".
 *
 * @property int|null $activityId Id
 * @property int|null $type 实践活动类型：1.见习；2.实习；3.微格训练
 * @property string|null $username 用户名
 * @property string|null $sno 学号
 * @property string|null $sName 学生姓名
 * @property int|null $contentId 具体的教学实践活动Id
 * @property string|null $content 实践活动内容
 * @property string|null $startTime 教学活动开始的时间
 * @property string|null $endTime 教学活动结束的时间
 * @property int|null $status 表示教学活动的状态——0：未开始；1：进行中；2：已结束
 */
class ActivityDetails extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'activity_details';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['activityId', 'type', 'contentId', 'status'], 'integer'],
            [['startTime', 'endTime'], 'safe'],
            [['username', 'content'], 'string', 'max' => 255],
            [['sno', 'sName'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'activityId' => 'Activity ID',
            'type' => 'Type',
            'username' => 'Username',
            'sno' => 'Sno',
            'sName' => 'S Name',
            'contentId' => 'Content ID',
            'content' => 'Content',
            'startTime' => 'Start Time',
            'endTime' => 'End Time',
            'status' => 'Status',
        ];
    }
}

<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "probation_videos".
 *
 * @property int|null $vId
 * @property int|null $type 实践类型
 * @property string|null $username 用户名
 * @property string|null $videoname 视频名称
 * @property string|null $path 视频路径
 * @property string|null $submitTime 提交时间
 * @property int|null $status 状态
 */
class ProbationVideos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'probation_videos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['vId', 'type', 'status'], 'integer'],
            [['submitTime'], 'safe'],
            [['username', 'videoname', 'path'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'vId' => 'V ID',
            'type' => 'Type',
            'username' => 'Username',
            'videoname' => 'Videoname',
            'path' => 'Path',
            'submitTime' => 'Submit Time',
            'status' => 'Status',
        ];
    }
}

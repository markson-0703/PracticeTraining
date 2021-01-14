<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "notice".
 *
 * @property int|null $nId
 * @property int|null $typeId 实践类型,1:见习；2：实习；3：微格；4：实践
 * @property string|null $content 消息通知的内容
 * @property int|null $ojbect 消息的发布对象，2：teacher；3：student；4：tutor
 * @property string|null $pushId 对象id，以逗号分隔，将消息推送给相应对象
 * @property string|null $finishId 已读过该消息的对象
 * @property string|null $pushTime 推送消息的时间
 * @property int|null $status 消息发布的状态，0:删除状态；1：未发布到具体对象；2：已发布但未读；3：发布且已读
 */
class Notice extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'notice';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nId', 'typeId', 'ojbect', 'status'], 'integer'],
            [['pushTime'], 'safe'],
            [['content', 'pushId', 'finishId'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'nId' => 'N ID',
            'typeId' => 'Type ID',
            'content' => 'Content',
            'ojbect' => 'Ojbect',
            'pushId' => 'Push ID',
            'finishId' => 'Finish ID',
            'pushTime' => 'Push Time',
            'status' => 'Status',
        ];
    }
}

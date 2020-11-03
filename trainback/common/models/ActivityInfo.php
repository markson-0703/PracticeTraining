<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "activity_info".
 *
 * @property int|null $id
 * @property int|null $contentId 教学实践活动的Id
 * @property string|null $content 具体的教学实践活动
 */
class ActivityInfo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'activity_info';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'contentId'], 'integer'],
            [['content'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'contentId' => 'Content ID',
            'content' => 'Content',
        ];
    }
}

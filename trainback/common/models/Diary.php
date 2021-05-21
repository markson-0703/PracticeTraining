<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "diary".
 *
 * @property int|null $dId 日记序号
 * @property string|null $username 用户名
 * @property int|null $kind 日记类型，1：教学工作日记；2：班主任工作日记
 * @property string|null $date 日期
 * @property string|null $content 记事
 * @property int|null $status 有效
 */
class Diary extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'diary';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['dId', 'kind', 'status'], 'integer'],
            [['date'], 'safe'],
            [['content'], 'string'],
            [['username'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'dId' => 'D ID',
            'username' => 'Username',
            'kind' => 'Kind',
            'date' => 'Date',
            'content' => 'Content',
            'status' => 'Status',
        ];
    }
}

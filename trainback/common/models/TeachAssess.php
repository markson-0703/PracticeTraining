<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "teach_assess".
 *
 * @property int|null $id
 * @property string|null $username
 * @property string|null $class
 * @property string|null $time
 * @property string|null $weekday 周几
 * @property string|null $section 第几节课
 * @property string|null $classform 课型
 * @property string|null $content 授课内容
 * @property string|null $selfAssess
 * @property string|null $groupAssess
 * @property string|null $tutorAssess
 * @property int|null $status 状态，0：初始状态；1：自评；2：小组评价；3：校外教师
 */
class TeachAssess extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'teach_assess';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['time'], 'safe'],
            [['selfAssess', 'groupAssess', 'tutorAssess'], 'string'],
            [['username', 'content'], 'string', 'max' => 255],
            [['class', 'weekday', 'section'], 'string', 'max' => 50],
            [['classform'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'class' => 'Class',
            'time' => 'Time',
            'weekday' => 'Weekday',
            'section' => 'Section',
            'classform' => 'Classform',
            'content' => 'Content',
            'selfAssess' => 'Self Assess',
            'groupAssess' => 'Group Assess',
            'tutorAssess' => 'Tutor Assess',
            'status' => 'Status',
        ];
    }
}

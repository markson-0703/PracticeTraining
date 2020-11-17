<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "probation_record2".
 *
 * @property int|null $id
 * @property string|null $username
 * @property string|null $type 见习形式
 * @property string|null $date 日期
 * @property string|null $weekday 周几
 * @property string|null $section 第几节
 * @property string|null $classname 见习班级
 * @property string|null $instructor 授课教师
 * @property string|null $theme 课题
 * @property string|null $classform 课型
 * @property string|null $content1 讨论记录
 * @property string|null $content2 教师指导意见记录
 * @property string|null $submitTime 记录提交时间
 * @property int|null $status 状态
 */
class ProbationRecord2 extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'probation_record2';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['date', 'submitTime'], 'safe'],
            [['username', 'type', 'weekday', 'section', 'classname', 'instructor', 'theme', 'classform', 'content1', 'content2'], 'string', 'max' => 255],
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
            'type' => 'Type',
            'date' => 'Date',
            'weekday' => 'Weekday',
            'section' => 'Section',
            'classname' => 'Classname',
            'instructor' => 'Instructor',
            'theme' => 'Theme',
            'classform' => 'Classform',
            'content1' => 'Content1',
            'content2' => 'Content2',
            'submitTime' => 'Submit Time',
            'status' => 'Status',
        ];
    }
}

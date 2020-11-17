<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "probation_record1".
 *
 * @property int|null $id
 * @property string|null $username 用户名
 * @property string|null $type 见习形式
 * @property string|null $date 写该记录的日期
 * @property string|null $weekday 周几
 * @property string|null $className 见习班级
 * @property string|null $instructor 授课老师
 * @property string|null $theme 课题
 * @property string|null $classform 课型
 * @property string|null $content1 听课记录
 * @property string|null $content2 听课反思
 * @property string|null $content3 教师指导记录
 * @property string|null $submitTime 提交时间
 * @property int|null $status 状态
 */
class ProbationRecord1 extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'probation_record1';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['date', 'submitTime'], 'safe'],
            [['username', 'type', 'weekday', 'className', 'instructor', 'theme', 'classform', 'content1', 'content2', 'content3'], 'string', 'max' => 255],
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
            'className' => 'Class Name',
            'instructor' => 'Instructor',
            'theme' => 'Theme',
            'classform' => 'Classform',
            'content1' => 'Content1',
            'content2' => 'Content2',
            'content3' => 'Content3',
            'submitTime' => 'Submit Time',
            'status' => 'Status',
        ];
    }
}

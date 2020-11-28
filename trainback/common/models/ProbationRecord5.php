<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "probation_record5".
 *
 * @property int|null $id
 * @property string|null $username
 * @property string|null $date
 * @property string|null $weekday
 * @property string|null $section 章节
 * @property string|null $grade
 * @property string|null $tutor
 * @property string|null $classform 课型
 * @property string|null $teachTime 授课时间
 * @property string|null $teachContent 授课内容
 * @property string|null $contentAnalyse 内容分析
 * @property string|null $objectAnalyse 学习者分析
 * @property string|null $objections 教学目标
 * @property string|null $difficulties 教学重难点
 * @property string|null $strategies 教学策略
 * @property string|null $process 教学策略
 * @property string|null $writing 板书设计
 * @property string|null $reflect 教学反思
 * @property string|null $review 教师审阅
 * @property string|null $submitTime
 * @property int|null $status 文件状态： 1：表示已存在，但还未被教师审阅； 2：已被教师审阅
 */
class ProbationRecord5 extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'probation_record5';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['date', 'teachTime', 'submitTime'], 'safe'],
            [['username', 'weekday', 'grade', 'tutor', 'classform', 'teachContent', 'contentAnalyse', 'objectAnalyse', 'objections', 'difficulties', 'strategies', 'process', 'writing', 'reflect', 'review'], 'string', 'max' => 255],
            [['section'], 'string', 'max' => 50],
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
            'date' => 'Date',
            'weekday' => 'Weekday',
            'section' => 'Section',
            'grade' => 'Grade',
            'tutor' => 'Tutor',
            'classform' => 'Classform',
            'teachTime' => 'Teach Time',
            'teachContent' => 'Teach Content',
            'contentAnalyse' => 'Content Analyse',
            'objectAnalyse' => 'Object Analyse',
            'objections' => 'Objections',
            'difficulties' => 'Difficulties',
            'strategies' => 'Strategies',
            'process' => 'Process',
            'writing' => 'Writing',
            'reflect' => 'Reflect',
            'review' => 'Review',
            'submitTime' => 'Submit Time',
            'status' => 'Status',
        ];
    }
}

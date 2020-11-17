<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "probation_record3".
 *
 * @property int|null $id
 * @property string|null $username
 * @property string|null $schoolname 见习学校
 * @property string $className 班级名称
 * @property string|null $date
 * @property string|null $content1 班级管理见习记录
 * @property string|null $content2 总结反思
 * @property string|null $submitTime
 * @property int|null $status
 */
class ProbationRecord3 extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'probation_record3';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['className'], 'required'],
            [['date', 'submitTime'], 'safe'],
            [['username', 'schoolname', 'className', 'content1', 'content2'], 'string', 'max' => 255],
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
            'schoolname' => 'Schoolname',
            'className' => 'Class Name',
            'date' => 'Date',
            'content1' => 'Content1',
            'content2' => 'Content2',
            'submitTime' => 'Submit Time',
            'status' => 'Status',
        ];
    }
}

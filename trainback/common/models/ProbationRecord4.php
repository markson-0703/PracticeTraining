<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "probation_record4".
 *
 * @property int|null $id
 * @property string|null $username 用户名
 * @property string|null $schoolname 学校名称
 * @property string|null $grade 年级
 * @property string|null $date
 * @property string|null $theme 课题
 * @property string|null $content1
 * @property string|null $content2
 * @property string|null $submitTime
 * @property int|null $status
 */
class ProbationRecord4 extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'probation_record4';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['date', 'submitTime'], 'safe'],
            [['username', 'schoolname', 'grade', 'theme', 'content1', 'content2'], 'string', 'max' => 255],
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
            'grade' => 'Grade',
            'date' => 'Date',
            'theme' => 'Theme',
            'content1' => 'Content1',
            'content2' => 'Content2',
            'submitTime' => 'Submit Time',
            'status' => 'Status',
        ];
    }
}

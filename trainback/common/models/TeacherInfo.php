<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "teacher_info".
 *
 * @property int $tId
 * @property string|null $username
 * @property string|null $tName
 * @property string|null $job_num
 * @property string|null $contactPhone
 * @property string|null $email
 * @property string|null $rank
 * @property int|null $status
 * @property int|null $ischoosen 判断该老师是否被选择
 */
class TeacherInfo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'teacher_info';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tId'], 'required'],
            [['tId', 'status', 'ischoosen'], 'integer'],
            [['username', 'job_num'], 'string', 'max' => 255],
            [['tName', 'contactPhone', 'email'], 'string', 'max' => 50],
            [['rank'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'tId' => 'T ID',
            'username' => 'Username',
            'tName' => 'T Name',
            'job_num' => 'Job Num',
            'contactPhone' => 'Contact Phone',
            'email' => 'Email',
            'rank' => 'Rank',
            'status' => 'Status',
            'ischoosen' => 'Ischoosen',
        ];
    }
}

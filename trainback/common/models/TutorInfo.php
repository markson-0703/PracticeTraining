<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tutor_info".
 *
 * @property int $tId
 * @property string $tName
 * @property string|null $school_name 该见习或实习老师所在学校名称
 * @property string $job_num
 * @property string|null $contactPhone
 * @property string|null $email
 * @property string|null $rank
 * @property int|null $status
 * @property int|null $ischoosen 判断该老师是否被选择
 */
class TutorInfo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tutor_info';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tId', 'tName', 'job_num'], 'required'],
            [['tId', 'status', 'ischoosen'], 'integer'],
            [['tName', 'contactPhone', 'email'], 'string', 'max' => 50],
            [['school_name', 'job_num'], 'string', 'max' => 255],
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
            'tName' => 'T Name',
            'school_name' => 'School Name',
            'job_num' => 'Job Num',
            'contactPhone' => 'Contact Phone',
            'email' => 'Email',
            'rank' => 'Rank',
            'status' => 'Status',
            'ischoosen' => 'Ischoosen',
        ];
    }
}

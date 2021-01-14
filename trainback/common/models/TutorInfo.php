<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tutor_info".
 *
 * @property int $tId
 * @property string|null $username
 * @property string|null $tName
 * @property string|null $school_name 该见习或实习老师所在学校名称
 * @property string|null $job_num
 * @property string|null $contactPhone
 * @property string|null $email
 * @property string|null $rank
 * @property int|null $status
 * @property int|null $ischoosen 判断该老师是否被选择
 * @property int|null $probation
 * @property int|null $practice
 * @property int|null $microteaching
 * @property int|null $socials
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
            [['tId'], 'required'],
            [['tId', 'status', 'ischoosen', 'probation', 'practice', 'microteaching', 'socials'], 'integer'],
            [['username', 'school_name', 'job_num'], 'string', 'max' => 255],
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
            'school_name' => 'School Name',
            'job_num' => 'Job Num',
            'contactPhone' => 'Contact Phone',
            'email' => 'Email',
            'rank' => 'Rank',
            'status' => 'Status',
            'ischoosen' => 'Ischoosen',
            'probation' => 'Probation',
            'practice' => 'Practice',
            'microteaching' => 'Microteaching',
            'socials' => 'Socials',
        ];
    }
}

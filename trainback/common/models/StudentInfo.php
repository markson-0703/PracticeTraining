<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "student_info".
 *
 * @property int $sId
 * @property string|null $username 用户名
 * @property string|null $sName
 * @property string|null $sno
 * @property string|null $sex
 * @property int|null $cId 所在班级的id
 * @property string|null $className 所在班级名称
 * @property int|null $majorId 所在专业id
 * @property string|null $majorName 所在专业名称
 * @property string|null $bornDate
 * @property string|null $phone
 * @property string|null $email
 * @property int|null $status
 * @property int|null $probation 见习
 * @property int|null $practice 实习
 * @property int|null $microteaching 微格教学
 * @property int|null $socials 社会实践
 */
class StudentInfo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'student_info';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sId'], 'required'],
            [['sId', 'cId', 'majorId', 'status', 'probation', 'practice', 'microteaching', 'socials'], 'integer'],
            [['username', 'bornDate'], 'string', 'max' => 255],
            [['sName', 'sno', 'className', 'majorName', 'phone', 'email'], 'string', 'max' => 50],
            [['sex'], 'string', 'max' => 5],
            [['sId'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'sId' => 'S ID',
            'username' => 'Username',
            'sName' => 'S Name',
            'sno' => 'Sno',
            'sex' => 'Sex',
            'cId' => 'C ID',
            'className' => 'Class Name',
            'majorId' => 'Major ID',
            'majorName' => 'Major Name',
            'bornDate' => 'Born Date',
            'phone' => 'Phone',
            'email' => 'Email',
            'status' => 'Status',
            'probation' => 'Probation',
            'practice' => 'Practice',
            'microteaching' => 'Microteaching',
            'socials' => 'Socials',
        ];
    }
}

<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "student_info".
 *
 * @property int $sId
 * @property string $sName
 * @property string $sno
 * @property string|null $sex
 * @property int $cId 所在班级的id
 * @property string|null $className 所在班级名称
 * @property int $majorId 所在专业id
 * @property string|null $majorName 所在专业名称
 * @property string|null $bornDate
 * @property string|null $phone
 * @property string|null $email
 * @property int|null $status
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
            [['sId', 'sName', 'sno', 'cId', 'majorId'], 'required'],
            [['sId', 'cId', 'majorId', 'status'], 'integer'],
            [['sName', 'sno', 'className', 'majorName', 'phone', 'email'], 'string', 'max' => 50],
            [['sex'], 'string', 'max' => 5],
            [['bornDate'], 'string', 'max' => 255],
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
        ];
    }
}

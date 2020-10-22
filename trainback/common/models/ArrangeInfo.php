<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "arrange_info".
 *
 * @property int|null $aId
 * @property string|null $username 学生的用户名
 * @property string|null $sno 学生的学号
 * @property string|null $sName 学生姓名
 * @property int|null $type 教学实践类型——1.见习；2.实习
 * @property string|null $job_num 校内导师工号
 * @property string|null $tName 校内导师姓名
 * @property string|null $tutor_name 校外导师姓名
 * @property string|null $jno 校外导师工号
 * @property string|null $school_name 实践地点
 * @property int|null $ischecked 判定校内导师是否审核，如果审核通过置1
 * @property string|null $startTime 实践开始的时间
 * @property string|null $endTime 实践结束的时间
 */
class ArrangeInfo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'arrange_info';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['aId', 'type', 'ischecked'], 'integer'],
            [['username', 'job_num', 'tName', 'tutor_name', 'jno', 'school_name', 'startTime', 'endTime'], 'string', 'max' => 255],
            [['sno', 'sName'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'aId' => 'A ID',
            'username' => 'Username',
            'sno' => 'Sno',
            'sName' => 'S Name',
            'type' => 'Type',
            'job_num' => 'Job Num',
            'tName' => 'T Name',
            'tutor_name' => 'Tutor Name',
            'jno' => 'Jno',
            'school_name' => 'School Name',
            'ischecked' => 'Ischecked',
            'startTime' => 'Start Time',
            'endTime' => 'End Time',
        ];
    }
}

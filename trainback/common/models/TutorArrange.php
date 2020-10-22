<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tutor_arrange".
 *
 * @property int|null $aId 此处对应分配信息表中的aId
 * @property int|null $typeId 实习类型：1.见习，2.实习
 * @property string|null $sno
 * @property string|null $sName
 * @property string|null $job_num 校内教师工号
 * @property string|null $tName 校内导师
 * @property string|null $tutor_name 校外导师
 * @property string|null $jno 校外导师工号
 * @property string|null $school_name
 * @property int|null $ischecked 判定校内导师是否审核，如果审核通过置1
 */
class TutorArrange extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tutor_arrange';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['aId', 'typeId', 'ischecked'], 'integer'],
            [['sno'], 'string', 'max' => 50],
            [['sName', 'job_num', 'tName', 'tutor_name', 'jno', 'school_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'aId' => 'A ID',
            'typeId' => 'Type ID',
            'sno' => 'Sno',
            'sName' => 'S Name',
            'job_num' => 'Job Num',
            'tName' => 'T Name',
            'tutor_name' => 'Tutor Name',
            'jno' => 'Jno',
            'school_name' => 'School Name',
            'ischecked' => 'Ischecked',
        ];
    }
}

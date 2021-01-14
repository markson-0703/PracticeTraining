<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "probation_assess".
 *
 * @property int|null $eId 评定Id
 * @property string|null $username 用户名
 * @property string|null $sName 见习生姓名
 * @property string|null $major 专业
 * @property string|null $school 见习学校
 * @property string|null $grade 见习年级
 * @property string|null $subject 见习科目
 * @property int|null $section 观摩节数
 * @property float|null $mark 见习成绩
 * @property string|null $startTime 见习开始时间
 * @property string|null $endTime 见习结束时间
 * @property string|null $selfevaluation 自我评价
 * @property string|null $groupevaluation 小组评价
 * @property string|null $tutorevaluation 校外导师评价
 * @property string|null $teacherevaluation 校内导师评价
 * @property string|null $finishTime 完成最终评定的时间
 * @property string|null $leader 组长
 * @property string|null $tutor 校外指导教师
 * @property string|null $teacher 校内指导教师
 * @property int|null $status 评价状态 0：初始状态 1：自评 2：小组评价 3：校外评价 4：校内评价
 */
class ProbationAssess extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'probation_assess';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['eId', 'section', 'status'], 'integer'],
            [['mark'], 'number'],
            [['startTime', 'endTime', 'finishTime'], 'safe'],
            [['username', 'sName', 'major', 'school', 'selfevaluation', 'groupevaluation', 'tutorevaluation', 'teacherevaluation'], 'string', 'max' => 255],
            [['grade', 'subject'], 'string', 'max' => 50],
            [['leader', 'tutor', 'teacher'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'eId' => 'E ID',
            'username' => 'Username',
            'sName' => 'S Name',
            'major' => 'Major',
            'school' => 'School',
            'grade' => 'Grade',
            'subject' => 'Subject',
            'section' => 'Section',
            'mark' => 'Mark',
            'startTime' => 'Start Time',
            'endTime' => 'End Time',
            'selfevaluation' => 'Selfevaluation',
            'groupevaluation' => 'Groupevaluation',
            'tutorevaluation' => 'Tutorevaluation',
            'teacherevaluation' => 'Teacherevaluation',
            'finishTime' => 'Finish Time',
            'leader' => 'Leader',
            'tutor' => 'Tutor',
            'teacher' => 'Teacher',
            'status' => 'Status',
        ];
    }
}

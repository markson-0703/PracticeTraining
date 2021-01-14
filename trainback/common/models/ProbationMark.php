<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "probation_mark".
 *
 * @property int|null $mId
 * @property string|null $uname 学生的用户名
 * @property float|null $mark1 行为表率
 * @property float|null $mark2 课堂教学见习
 * @property float|null $mark3 参与班级管理
 * @property float|null $mark4 参与教研活动
 * @property float|null $mark5 试讲
 * @property float|null $mark6 见习报告
 * @property int|null $status
 */
class ProbationMark extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'probation_mark';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mId', 'status'], 'integer'],
            [['mark1', 'mark2', 'mark3', 'mark4', 'mark5', 'mark6'], 'number'],
            [['uname'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'mId' => 'M ID',
            'uname' => 'Uname',
            'mark1' => 'Mark1',
            'mark2' => 'Mark2',
            'mark3' => 'Mark3',
            'mark4' => 'Mark4',
            'mark5' => 'Mark5',
            'mark6' => 'Mark6',
            'status' => 'Status',
        ];
    }
}

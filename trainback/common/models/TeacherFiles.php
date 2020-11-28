<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "teacher_files".
 *
 * @property int|null $rId
 * @property int|null $type 实践类型
 * @property int|null $kind 教师上传记录的类型
 * @property string|null $username 教师用户名
 * @property string|null $filename 记录文件名
 * @property string|null $path 教师上传的文件路径
 * @property string|null $submitTime 上传时间
 * @property int|null $status 1:普通记录2:总结
 */
class TeacherFiles extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'teacher_files';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['rId', 'type', 'kind', 'status'], 'integer'],
            [['submitTime'], 'safe'],
            [['username', 'filename', 'path'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'rId' => 'R ID',
            'type' => 'Type',
            'kind' => 'Kind',
            'username' => 'Username',
            'filename' => 'Filename',
            'path' => 'Path',
            'submitTime' => 'Submit Time',
            'status' => 'Status',
        ];
    }
}

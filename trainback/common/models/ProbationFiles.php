<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "probation_files".
 *
 * @property int|null $fId 标识Id
 * @property int|null $type 实践类型
 * @property string|null $username 学生用户名
 * @property string|null $filename 文件名称
 * @property string|null $path 文件路径
 * @property string|null $submitTime 提交时间
 * @property int|null $status
 */
class ProbationFiles extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'probation_files';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fId', 'type', 'status'], 'integer'],
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
            'fId' => 'F ID',
            'type' => 'Type',
            'username' => 'Username',
            'filename' => 'Filename',
            'path' => 'Path',
            'submitTime' => 'Submit Time',
            'status' => 'Status',
        ];
    }
}

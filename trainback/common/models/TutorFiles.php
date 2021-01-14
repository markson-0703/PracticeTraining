<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tutor_files".
 *
 * @property int|null $dId
 * @property int|null $type 实践类型
 * @property int|null $kind 校外导师上传记录的类型
 * @property string|null $username 用户名
 * @property string|null $filename 文件名称
 * @property string|null $path 文件路径
 * @property string|null $submitTime 上传时间
 * @property int|null $status 状态，1：普通记录；2：总结
 */
class TutorFiles extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tutor_files';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['dId', 'type', 'kind', 'status'], 'integer'],
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
            'dId' => 'D ID',
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

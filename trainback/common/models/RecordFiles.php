<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "record_files".
 *
 * @property int|null $uId id标识
 * @property int|null $type 实践类型 1:见习；2：实习；3：微格训练；4：社会实践
 * @property int|null $kind 见习记录种类
 * @property string|null $username 学生用户名
 * @property string|null $filename 记录名称
 * @property string|null $filedir 文件路径
 * @property string|null $createdTime 提交时间
 * @property int|null $status 状态
 */
class RecordFiles extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'record_files';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uId', 'type', 'kind', 'status'], 'integer'],
            [['createdTime'], 'safe'],
            [['username', 'filename', 'filedir'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'uId' => 'U ID',
            'type' => 'Type',
            'kind' => 'Kind',
            'username' => 'Username',
            'filename' => 'Filename',
            'filedir' => 'Filedir',
            'createdTime' => 'Created Time',
            'status' => 'Status',
        ];
    }
}

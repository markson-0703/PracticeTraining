<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "student_files".
 *
 * @property int|null $rId
 * @property int|null $type 实践类型
 * @property int|null $kind
 * @property string|null $username
 * @property string|null $filename
 * @property string|null $path
 * @property string|null $submitTime
 * @property string|null $suggestion 校外指导老师意见
 * @property int|null $status
 */
class StudentFiles extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'student_files';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['rId', 'type', 'kind', 'status'], 'integer'],
            [['submitTime'], 'safe'],
            [['suggestion'], 'string'],
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
            'suggestion' => 'Suggestion',
            'status' => 'Status',
        ];
    }
}

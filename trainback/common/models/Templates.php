<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "templates".
 *
 * @property int|null $temId 模板Id
 * @property string|null $publisher 发布者
 * @property int|null $type 用于的实践类型
 * @property int|null $kind 某实践类型下的某个分类
 * @property string|null $filename 文件名称
 * @property string|null $path 文件路径
 * @property string|null $publishTime 模板发布时间
 * @property int|null $status 状态
 */
class Templates extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'templates';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['temId', 'type', 'kind', 'status'], 'integer'],
            [['publishTime'], 'safe'],
            [['publisher', 'filename', 'path'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'temId' => 'Tem ID',
            'publisher' => 'Publisher',
            'type' => 'Type',
            'kind' => 'Kind',
            'filename' => 'Filename',
            'path' => 'Path',
            'publishTime' => 'Publish Time',
            'status' => 'Status',
        ];
    }
}

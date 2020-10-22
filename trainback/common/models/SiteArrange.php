<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "site_arrange".
 *
 * @property int $aId
 * @property string|null $tName 校内教师名字
 * @property string $job_num 教师工号
 * @property int $typeId 实践类型：1：见习，2：实习
 * @property string|null $site 见习或实习点
 * @property int|null $status
 */
class SiteArrange extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'site_arrange';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['aId', 'job_num', 'typeId'], 'required'],
            [['aId', 'typeId', 'status'], 'integer'],
            [['tName'], 'string', 'max' => 50],
            [['job_num', 'site'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'aId' => 'A ID',
            'tName' => 'T Name',
            'job_num' => 'Job Num',
            'typeId' => 'Type ID',
            'site' => 'Site',
            'status' => 'Status',
        ];
    }
}

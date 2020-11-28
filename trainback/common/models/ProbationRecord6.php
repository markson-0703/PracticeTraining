<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "probation_record6".
 *
 * @property int|null $id
 * @property string|null $username
 * @property string|null $date
 * @property string|null $weekday
 * @property string|null $section
 * @property string|null $directTutor
 * @property string|null $content1
 * @property string|null $content2
 * @property string|null $content3
 * @property string|null $submitTime
 * @property int|null $status
 */
class ProbationRecord6 extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'probation_record6';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['date', 'submitTime'], 'safe'],
            [['username', 'directTutor', 'content1', 'content2', 'content3'], 'string', 'max' => 255],
            [['weekday', 'section'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'date' => 'Date',
            'weekday' => 'Weekday',
            'section' => 'Section',
            'directTutor' => 'Direct Tutor',
            'content1' => 'Content1',
            'content2' => 'Content2',
            'content3' => 'Content3',
            'submitTime' => 'Submit Time',
            'status' => 'Status',
        ];
    }
}

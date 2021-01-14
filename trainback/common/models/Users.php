<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string $username
 * @property string|null $token
 * @property string|null $password
 * @property int|null $role 1：管理员 2：教师用户 3：学生用户 4:校外用户
 * @property int|null $status
 * @property int|null $probation 见习
 * @property int|null $practice 实习
 * @property int|null $microteaching 微格教学
 * @property int|null $socials 社会实践
 */
class Users extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'username'], 'required'],
            [['id', 'role', 'status', 'probation', 'practice', 'microteaching', 'socials'], 'integer'],
            [['username', 'token', 'password'], 'string', 'max' => 255],
            [['id'], 'unique'],
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
            'token' => 'Token',
            'password' => 'Password',
            'role' => 'Role',
            'status' => 'Status',
            'probation' => 'Probation',
            'practice' => 'Practice',
            'microteaching' => 'Microteaching',
            'socials' => 'Socials',
        ];
    }
}

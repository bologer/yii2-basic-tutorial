<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * Class Car
 *
 * @property string name Имя машины.
 * @property string model Название модели машины.
 * @property string description Описание машины.
 * @property integer updated_at Когда машина была обновлена.
 * @property integer created_at Когда машина была создана.
 *
 * @package app\models
 */
class Car extends ActiveRecord
{
    public static function tableName()
    {
        return 'cars';
    }

    public function rules()
    {
        return [
            [['name', 'model'], 'required'],

            [['name', 'model', 'description'], 'trim'],

            [['name', 'model'], 'string', 'max' => 255],
            ['description', 'string', 'max' => 500],
        ];
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }
}
<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "apple".
 *
 * @property int $id
 * @property string $color
 * @property int $create_date
 * @property int $fall_date
 * @property int $status
 * @property int $percent
 * @property int $state
 */
class Apple extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'apple';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['color', 'create_date', 'fall_date', 'percent', 'state'], 'required'],
            [['create_date', 'fall_date', 'status', 'percent', 'state'], 'integer'],
            [['color'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'color' => 'Color',
            'create_date' => 'Create Date',
            'fall_date' => 'Fall Date',
            'status' => 'Status',
            'percent' => 'Percent',
            'state' => 'State',
        ];
    }


    public function fallToGround($id)
    {
        
    }

    public function eat($id)
    {
        
    }


    public function remove($id)
    {
        
    }
}

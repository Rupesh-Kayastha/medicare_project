<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%HomeBanner}}".
 *
 * @property int $BannerId
 * @property string $BannerImage
 * @property string $WebLink
 * @property int $IsDelete
 * @property string $OnDate
 * @property string $UpdatedDate
 */
class HomeBanner extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%HomeBanner}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            //[['BannerImage'], 'required'],
            [['IsDelete'], 'integer'],
            [['OnDate', 'UpdatedDate'], 'safe'],
            [['BannerImage'], 'string', 'max' => 200],
            [['WebLink'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'BannerId' => Yii::t('app', 'Banner ID'),
            'BannerImage' => Yii::t('app', 'Banner Image'),
            'WebLink' => Yii::t('app', 'Web Link'),
            'IsDelete' => Yii::t('app', 'Is Delete'),
            'OnDate' => Yii::t('app', 'On Date'),
            'UpdatedDate' => Yii::t('app', 'Updated Date'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return \common\models\active\HomeBannerQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\active\HomeBannerQuery(get_called_class());
    }
    
}

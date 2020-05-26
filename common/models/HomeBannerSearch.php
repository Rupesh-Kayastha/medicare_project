<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\HomeBanner;

/**
 * HomeBannerSearch represents the model behind the search form about `common\models\HomeBanner`.
 */
class HomeBannerSearch extends HomeBanner
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['BannerId', 'IsDelete'], 'integer'],
            [['BannerImage', 'WebLink', 'OnDate', 'UpdatedDate'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = HomeBanner::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'BannerId' => $this->BannerId,
            'IsDelete' => $this->IsDelete,
            'OnDate' => $this->OnDate,
            'UpdatedDate' => $this->UpdatedDate,
        ]);

        $query->andFilterWhere(['like', 'BannerImage', $this->BannerImage])
            ->andFilterWhere(['like', 'WebLink', $this->WebLink]);

        return $dataProvider;
    }
}

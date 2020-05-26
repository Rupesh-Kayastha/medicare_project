<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\MedicineCategoryMaping;

/**
 * MedicineCategoryMapingSearch represents the model behind the search form about `common\models\MedicineCategoryMaping`.
 */
class MedicineCategoryMapingSearch extends MedicineCategoryMaping
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['MedicineCategoryMapingId', 'MedicineId', 'MedicineCategoryId'], 'integer'],
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
        $query = MedicineCategoryMaping::find();

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
            'MedicineCategoryMapingId' => $this->MedicineCategoryMapingId,
            'MedicineId' => $this->MedicineId,
            'MedicineCategoryId' => $this->MedicineCategoryId,
        ]);

        return $dataProvider;
    }
}

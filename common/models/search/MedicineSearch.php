<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Medicine;

/**
 * MedicineSearch represents the model behind the search form about `common\models\Medicine`.
 */
class MedicineSearch extends Medicine
{
	
	public $BrandName;
	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['MedicineId', 'BrandId', 'IsPrescription', 'InStock', 'BestSeller', 'IsDelete'], 'integer'],
            [['Name', 'MediceneImage', 'MedicineCategoryId','BrandName', 'OnDate', 'UpdatedDate'], 'safe'],
            [['RegularPrice', 'DiscountedPrice'], 'number'],
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
        $query = Medicine::find()->joinWith(['brand']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
		$dataProvider->sort->attributes['BrandName'] = [
			'asc' => ['Brand.Name' => SORT_ASC],
			'desc' => ['Brand.Name' => SORT_DESC],
		];
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'MedicineId' => $this->MedicineId,
            'BrandId' => $this->BrandId,
            'RegularPrice' => $this->RegularPrice,
            'DiscountedPrice' => $this->DiscountedPrice,
            'IsPrescription' => $this->IsPrescription,
            'InStock' => $this->InStock,
            'BestSeller' => $this->BestSeller,
            'Medicine.IsDelete' => $this->IsDelete,
            'OnDate' => $this->OnDate,
            'UpdatedDate' => $this->UpdatedDate,
        ]);
		$query->andFilterWhere(['like', 'Brand.Name', $this->BrandName]);
        $query->andFilterWhere(['like', 'Medicine.Name', $this->Name]);
		//$query->andWhere(['like', 'MedicineCategoryId', $this->MedicineCategoryId]);
		if($this->MedicineCategoryId)
		$query->andWhere(new \yii\db\Expression('FIND_IN_SET('.$this->MedicineCategoryId.', MedicineCategoryId)'));

        return $dataProvider;
    }
}

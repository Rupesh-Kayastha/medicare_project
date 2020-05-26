<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\EmiPlans;

/**
 * EmiPlansSearch represents the model behind the search form about `common\models\EmiPlans`.
 */
class EmiPlansSearch extends EmiPlans
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['EmiPlanId', 'EmiPlanCompanyId', 'EmiPlanPeriod', 'EmiPlanStatus', 'IsDelete'], 'integer'],
            [['EmiPlanName', 'OnDate', 'UpdatedDate'], 'safe'],
            [['EmiPlanOrderMinAmount'], 'number'],
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
        $query = EmiPlans::find();

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
            'EmiPlanId' => $this->EmiPlanId,
            'EmiPlanCompanyId' => $this->EmiPlanCompanyId,
            'EmiPlanPeriod' => $this->EmiPlanPeriod,
            'EmiPlanOrderMinAmount' => $this->EmiPlanOrderMinAmount,
            'EmiPlanStatus' => $this->EmiPlanStatus,
            'IsDelete' => $this->IsDelete,
            'OnDate' => $this->OnDate,
            'UpdatedDate' => $this->UpdatedDate,
        ]);

        $query->andFilterWhere(['like', 'EmiPlanName', $this->EmiPlanName]);

        return $dataProvider;
    }
}

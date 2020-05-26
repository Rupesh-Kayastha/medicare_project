<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\WebPrescription;

/**
 * WebPrescriptionSearch represents the model behind the search form about `common\models\WebPrescription`.
 */
class WebPrescriptionSearch extends WebPrescription
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Id', 'IsDelete'], 'integer'],
            [['Prescription', 'EmployeeId', 'UserName', 'UserContact', 'UserMail', 'UserMessage', 'UserAddress', 'OnDate', 'UpdatedDate'], 'safe'],
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
        $query = WebPrescription::find();

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
            'Id' => $this->Id,
            'IsDelete' => $this->IsDelete,
            'OnDate' => $this->OnDate,
            'UpdatedDate' => $this->UpdatedDate,
        ]);

        $query->andFilterWhere(['like', 'Prescription', $this->Prescription])
            ->andFilterWhere(['like', 'EmployeeId', $this->EmployeeId])
            ->andFilterWhere(['like', 'UserName', $this->UserName])
            ->andFilterWhere(['like', 'UserMail', $this->UserMail])
            ->andFilterWhere(['like', 'UserMessage', $this->UserMessage])
            ->andFilterWhere(['like', 'UserAddress', $this->UserAddress]);
            
        return $dataProvider;
    }
}

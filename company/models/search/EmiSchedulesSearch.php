<?php

namespace company\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\EmiSchedules;

/**
 * EmiSchedulesSearch represents the model behind the search form about `common\models\EmiSchedules`.
 */
class EmiSchedulesSearch extends EmiSchedules
{
    /**
     * @inheritdoc
     */
	 
	public $EmployeeName;
	public $EmpId;
	
    public function rules()
    {
        return [
            [['EmiSchedulesId', 'EmployeeId', 'CompanyId', 'EmiClearingStatus'], 'integer'],
            [['EmployeeName','EmpId','OrderIdentifier', 'EmiMonth', 'CreatedDate', 'UpdatedDate'], 'safe'],
            [['EmiAmount'], 'number'],
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
        $query = EmiSchedules::find()->joinWith(['employee']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
		
		$dataProvider->sort->attributes['EmployeeName'] = [
			'asc' => ['Employee.EmployeeName' => SORT_ASC],
			'desc' => ['Employee.EmployeeName' => SORT_DESC],
		];
		$dataProvider->sort->attributes['EmpId'] = [
			'asc' => ['Employee.EmpId' => SORT_ASC],
			'desc' => ['Employee.EmpId' => SORT_DESC],
		];
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'EmiSchedulesId' => $this->EmiSchedulesId,
            'EmployeeId' => $this->EmployeeId,
            'EmiSchedules.CompanyId' => $this->CompanyId,
            'EmiAmount' => $this->EmiAmount,
            'CreatedDate' => $this->CreatedDate,
            'UpdatedDate' => $this->UpdatedDate,
            'EmiClearingStatus' => $this->EmiClearingStatus,
        ]);
		$query->andFilterWhere(['like', 'Employee.EmployeeName', $this->EmployeeName]);
		$query->andFilterWhere(['like', 'Employee.EmpId', $this->EmpId]);
        $query->andFilterWhere(['like', 'OrderIdentifier', $this->OrderIdentifier])
            ->andFilterWhere(['like', 'EmiMonth', $this->EmiMonth]);

        return $dataProvider;
    }
}

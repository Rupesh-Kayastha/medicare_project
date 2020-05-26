<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Employee;

/**
 * EmployeeSearch represents the model behind the search form about `common\models\Employee`.
 */
class EmployeeSearch extends Employee
{
	
	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['EmployeeId', 'CompanyId', 'EmployeeRoleId', 'ContactNo', 'EmployeeActiveStatus', 'IsDelete',], 'integer'],
            [['EmpId', 'Password', 'OtpHash', 'EmployeeName', 'Dob', 'EmailId', 'BloodGroup', 'OnDate', 'UpdatedDate'], 'safe'],
			[['CreditLimit', 'CreditBalance', 'CreditOnHold'], 'number'], 
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
        $query = Employee::find();

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
            'EmployeeId' => $this->EmployeeId,
            'CompanyId' => $this->CompanyId,
            'EmployeeRoleId' => $this->EmployeeRoleId,
            'Dob' => $this->Dob,
            'ContactNo' => $this->ContactNo,
			'CreditLimit' => $this->CreditLimit, 
			'CreditBalance' => $this->CreditBalance, 
			'CreditOnHold' => $this->CreditOnHold, 
			'EmployeeActiveStatus' => $this->EmployeeActiveStatus,
            'IsDelete' => $this->IsDelete,
            'OnDate' => $this->OnDate,
            'UpdatedDate' => $this->UpdatedDate,
        ]);

		
        $query->andFilterWhere(['like', 'EmpId', $this->EmpId])
            ->andFilterWhere(['like', 'Password', $this->Password])
            ->andFilterWhere(['like', 'OtpHash', $this->OtpHash])
            ->andFilterWhere(['like', 'EmployeeName', $this->EmployeeName])
            ->andFilterWhere(['like', 'EmailId', $this->EmailId])
            ->andFilterWhere(['like', 'BloodGroup', $this->BloodGroup]);

        return $dataProvider;
    }
}

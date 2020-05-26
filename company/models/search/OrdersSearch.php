<?php

namespace company\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Orders;

/**
 * OrdersSearch represents the model behind the search form about `common\models\Orders`.
 */
class OrdersSearch extends Orders
{
    /**
     * @inheritdoc
     */
	public $EmployeeName;
	public $EmpId;
    public function rules()
    {
        return [
            [['OrderId', 'EmployeeId','CompanyId','PaymentType','MonthlySubscription', 'DeliveryAddressID', 'EmiPlanId', 'EmiPlanPeriod', 'OrderType', 'OrderStatus', 'EmiGenerateStatus', 'IsDelete'], 'integer'],
            [['EmployeeName','EmpId','OrderIdentifier', 'CartIdentifire', 'OrderComment', 'CreatedDate','ConfirmDate', 'UpdatedDate'], 'safe'],
            [['OrderTotalPrice', 'EmiAmount', 'CreditBalanceUsed'], 'number'],
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
		
        $query = Orders::find()->joinWith(['employee']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort'=> ['defaultOrder' => ['OrderId' => SORT_DESC]]
        ]);
		$dataProvider->sort->attributes['EmployeeName'] = [
			'asc' => ['Employee.EmployeeName' => SORT_ASC],
			'desc' => ['Employee.EmployeeName' => SORT_DESC],
		];
        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->andFilterWhere([
            'OrderId' => $this->OrderId,
            'EmployeeId' => $this->EmployeeId,
            'Orders.CompanyId' => $this->CompanyId,
            'OrderTotalPrice' => $this->OrderTotalPrice,
            //'PaymentType' => $this->PaymentType,
            'PaymentType' => [1,2],
			'MonthlySubscription'=>$this->MonthlySubscription,
            'DeliveryAddressID' => $this->DeliveryAddressID,
            'EmiPlanId' => $this->EmiPlanId,
            'EmiPlanPeriod' => $this->EmiPlanPeriod,
            'EmiAmount' => $this->EmiAmount,
            'CreditBalanceUsed' => $this->CreditBalanceUsed,
            'OrderType' => $this->OrderType,
            'OrderStatus' => $this->OrderStatus,
            'EmiGenerateStatus' => $this->EmiGenerateStatus,
            'CreatedDate' => $this->CreatedDate,
			'ConfirmDate'=> $this->ConfirmDate,
            'UpdatedDate' => $this->UpdatedDate,
            'IsDelete' => $this->IsDelete,
        ]);
		$query->andFilterWhere(['like', 'Employee.EmployeeName', $this->EmployeeName]);
		$query->andFilterWhere(['like', 'Employee.EmployeeName', $this->EmployeeName]);
        $query->andFilterWhere(['like', 'OrderIdentifier', $this->OrderIdentifier])
            ->andFilterWhere(['like', 'CartIdentifire', $this->CartIdentifire])
            ->andFilterWhere(['like', 'PaymentType', $this->PaymentType])
            ->andFilterWhere(['like', 'OrderComment', $this->OrderComment]);

        return $dataProvider;
    }
}

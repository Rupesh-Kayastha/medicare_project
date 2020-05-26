<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\EmployeeRole;

/**
 * EmployeeRoleSearch represents the model behind the search form about `common\models\EmployeeRole`.
 */
class EmployeeRoleSearch extends EmployeeRole
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['EmployeeRoleId', 'Status', 'IsDelete'], 'integer'],
            [['EmployeeRole', 'OnDate', 'UpdatedDate'], 'safe'],
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
        $query = EmployeeRole::find();

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
            'EmployeeRoleId' => $this->EmployeeRoleId,
            'Status' => $this->Status,
            'IsDelete' => $this->IsDelete,
            'OnDate' => $this->OnDate,
            'UpdatedDate' => $this->UpdatedDate,
        ]);

        $query->andFilterWhere(['like', 'EmployeeRole', $this->EmployeeRole]);

        return $dataProvider;
    }
}

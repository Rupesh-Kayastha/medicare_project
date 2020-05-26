<?php
namespace console\controllers;

use common\models\EmiSchedules;
use common\models\Orders;
use yii\console\Controller;

class EmiSchedulesController extends Controller {
	public function actionEmigenerate() {

		$current_date = date('Y-m-d'); //cron will run every night 1 AM

		$TodaysDate = date('Y-m-d', strtotime($current_date . " -1 days")); //Fetch Orders confoimrd on tthis date.
        $TodaysDate=$current_date;
		echo $current_date . "\n";
		echo $TodaysDate . "\n";

		$TodaysDateInt = date("d", strtotime($TodaysDate)) - 0;
		$MonthLastDateInt = date("t", strtotime($TodaysDate)) - 0;

		if ($TodaysDateInt <= 20) {
			$EMI_START_DATE = date("Y-m", strtotime(date("Y-m", strtotime($TodaysDate)) . "-01"." +1 month")) . "-01";
		} else {
			$EMI_START_DATE = date("Y-m", strtotime(date("Y-m", strtotime($TodaysDate)) . "-01" . " +2 month")) . "-01";
		}
		echo $EMI_START_DATE . "\n";

		$Orders = Orders::find()->where(['PaymentType' => [3, 4], 'OrderStatus' => 1, 'EmiGenerateStatus' => 0, 'ConfirmDate' => $TodaysDate])->all();

		foreach ($Orders as $order) {
			echo $order->CartIdentifire . PHP_EOL;

			if ($order->PaymentType == 3) {
				$EmiSchedules = new EmiSchedules();

				$EMI_DATE = $EMI_START_DATE;
				$EmiSchedules->EmployeeId = $order->EmployeeId;
				$EmiSchedules->CompanyId = $order->CompanyId;
				$EmiSchedules->OrderIdentifier = $order->OrderIdentifier;
				$EmiSchedules->EmiAmount = $order->EmiAmount;
				$EmiSchedules->EmiMonth = date("M Y", strtotime($EMI_DATE));
				$EmiSchedules->save();
			} else if ($order->PaymentType == 4) {

				$EMI_DATE = $EMI_START_DATE;

				for ($i = 1; $i <= $order->EmiPlanPeriod; $i++) {
					$EmiSchedules = new EmiSchedules();
					$EmiSchedules->EmployeeId = $order->EmployeeId;
					$EmiSchedules->CompanyId = $order->CompanyId;
					$EmiSchedules->OrderIdentifier = $order->OrderIdentifier;
					$EmiSchedules->EmiAmount = $order->EmiAmount;
					$EmiSchedules->EmiMonth = date("M Y", strtotime($EMI_DATE));
					$EmiSchedules->save();
					$EMI_DATE = date("Y-m-d", strtotime($EMI_DATE . " +1 month"));
				}

			}
			$order->EmiGenerateStatus = 1;
			$order->save();
		}
	}

}

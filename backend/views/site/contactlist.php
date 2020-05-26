<?php

use yii\helpers\Html;
use yiister\gentelella\widgets\Panel;


/* @var $this yii\web\View */
/* @var $searchModel common\models\HospitalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Contact List';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-12">
        <div class="contact-index">
            <table class="table table-bordered">
                <thead>
                    <th>Sl. No.</th>
                    <th>Name</th>
                    <th>Contact No</th>
                    <th>Email</th>
                    <th>Subject</th>
                    <th>Message</th>
                </thead>
           
            <tbody>


             <?php
        $i=1;
            foreach($contactdetails as $key=>$value)
              
            {
            ?>
            <tr>
                <td><?=$i++?></td>
                <td><?=$value->Name?></td>
                <td><?=$value->Mob?></td>
                <td><?=$value->Email?></td>
                <td><?=$value->Subject?></td>
                <td><?=$value->Message?></td>
            </tr>
           
             <?php
             
            }
             ?>
            </tbody>
        </table>

        </div>


       
    </div>
</div>




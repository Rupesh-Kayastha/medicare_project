<?php
namespace backend\widgets;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\base\Widget;
use yii\helpers\Json;
use common\models\Make;
use common\models\Year;

class YMMO extends Widget{
	
	public function init()
    {
		
	}
	public function run()
	{
		
		echo $this->getScript();
		
		echo Html::tag('table',
						Html::tag('colgroup',
						
						Html::tag("col","",['width'=>'16%']).
						Html::tag("col","",['width'=>'5%']).
						Html::tag("col","",['width'=>'23%']).
						Html::tag("col","",['width'=>'5%']).
						Html::tag("col","",['width'=>'23%']).
						Html::tag("col","",['width'=>'5%']).
						Html::tag("col","",['width'=>'23%'])
						
						
						).
						Html::tag('thead',
				
								Html::tag("tr",
									Html::tag("th","Year",['class'=>'action-column']).
									Html::tag("th","",['class'=>'active align-middle']).
									Html::tag("th","Make",['class'=>'action-column']).
									Html::tag("th","",['class'=>'active align-middle']).
									Html::tag("th","Model",['class'=>'action-column']).
									Html::tag("th","",['class'=>'active align-middle']).
									Html::tag("th","Options".
									Html::buttonInput('Add To New List',['class'=>'btn btn-success pull-right','onclick'=>'addToList();'])
									,['class'=>'action-column']
									)
									,
									[
									'class'=>'menu-open'
									]
								),
								[
								'class'=>'active'
								]
				
						).
						Html::tag('tbody',
				
								Html::tag("tr",
									Html::tag("td",$this->listBox("year_source")).
									Html::tag("td",">>",['class'=>'align-middle']).
									Html::tag("td",$this->listBox("make_source")).
									Html::tag("td",">>",['class'=>'align-middle']).
									Html::tag("td",$this->listBox("model_source")).
									Html::tag("td",">>",['class'=>'align-middle']).
									Html::tag("td",$this->listBox("model_oprtion_source"))
									,
									[
									'class'=>'menu-open'
									]
								),
								[
								'class'=>'active'
								]
						),
						[
						'class'=>'table table-striped table-bordered'
						]
						
			);
		
		
	}
	private function getAllYears()
	{
		
		$all_years=Year::find()->orderBy(['year' => SORT_DESC])->asArray()->all();
		
		$years=array('Select Year');
		
		foreach($all_years as $yr){
			$years[$yr['year']]=$yr['year'];
		}
		return $years;
	}
	private function getAllMakes()
	{
		
		$all_makes=Make::find()->orderBy(['name' => SORT_ASC])->asArray()->all();
		$makes=array('Select Make');
		foreach($all_makes as $mk){
			
			$makes[$mk['id']]=$mk['name'];
		}
		return $makes;
	}
	
	private function listBox($name)
	{
		
		$years=$this->getAllYears();
		$makes=$this->getAllMakes();
		$listBox="";
		if($name=="year_source"){
			
		$listBox=Html::listBox(
				'',
				 null,
				 $years,
				 [
				 'onchange'=>'setYearData(this.value)',
				 'id'=>'year_source',
				 'size'=>10
				 ]
			);
		}
		else if($name=="make_source"){
			
		$listBox=Html::listBox(
				'',
				 null,
				 $makes,
				 [
				 'onchange'=>'getMakeModels(this.value)',
				 'id'=>'make_source',
				 'size'=>10
				 ]
			 );
		}
		else if($name=="model_source"){
			
		$listBox=Html::listBox(
				'',
				 null,
				 ['Select Models'],
				 [
				 'onchange'=>"getMakeModelOptions(this.value)",
				 'id'=>'model_source',
				 'size'=>10
				 ]
			 );
		}
		else if($name=="model_oprtion_source"){
			
		$listBox=Html::listBox(
				'',
				 null,
				 ['Select Options'],
				 [
				 'onchange'=>"setMakeModelOptionsData(this.value)",
				 'id'=>'model_oprtion_source',
				 'size'=>10
				 ]
			 );
		}
		
		return $listBox;
	}
	private function getScript()
	{
		
		$make=new Make();
		$YMMO_Data=$make->getMakeModelOptionMap();
		$YMMO = Json::encode($YMMO_Data);
		
		$script = <<<JS
		<script>
		var YMMO=$YMMO;
		function getMakeModels(makeid){
			
		$("#make_source").data("value",makeid);
		$("#model_source").data('value',null);
		$("#model_oprtion_source").data('value',null);
		$("#model_source").find('option').remove().end().append('<option value="">Select Models</option>');
		$('#model_oprtion_source').find('option').remove().end().append('<option value="">Select Options</option>');
		
			if(makeid){
				$(YMMO[makeid]).each(function (index,o ) { 
				
				keys=Object.keys(o);
				
				 
				keys.forEach(function (k){
				
					var opt_model = $("<option/>").attr("value",o[k].id).text(o[k].make_model_name);
					
					$("#model_source").append(opt_model);
				});
				
				});
			
			}
		}
		function getMakeModelOptions(modelid){
		$("#model_source").data('value',modelid);
		$("#model_oprtion_source").data('value',null);
		
		makeid=$("#make_source").data('value');
		
		
		$('#model_oprtion_source').find('option').remove().end().append('<option value="">Select Options</option>');

			if(makeid && modelid){
				$(YMMO[makeid][modelid]['opts']).each(function (index, o) {  
				var opt_option = $("<option/>").attr("value", o.id).text(o.make_model_option_name);
				$("#model_oprtion_source").append(opt_option);
				});
			}
		}
		
		function setMakeModelOptionsData(optionid){
			$("#model_oprtion_source").data('value',optionid);
		}
		function setYearData(year){
			$("#year_source").data('value',year);
		}
		
		
		function removeTableRow(tbody,tr){
			
			$('tbody'+tbody+' tr'+tr).remove();
		}
		var newlist=0;
		
		function addToList(){
			
		var year=$("#year_source").data('value');
		var make=$("#make_source").data("value");
		var model=$("#model_source").data('value');
		var option=$("#model_oprtion_source").data('value');
		
		if(!year && !make){
			alert("Please Select Year");
			return false;
		}
		if(!make){
			alert("Please Select Make");
			return false;
		}
		var year_text="";
		var make_text="";
		var model_text="";
		var option_text="";
		
		if(year)
		year_text=$("#year_source option[value='"+year+"']").text();
		if(make)
		make_text=$("#make_source option[value='"+make+"']").text();
		if(model)
		model_text=$("#model_source option[value='"+model+"']").text();
		if(option)
		option_text=$("#model_oprtion_source option[value='"+option+"']").text();
	
	
		 row = $("<tr class='menu-open' id='ymmo_"+newlist+"'></tr>");
		 col1 = $("<td>"+year_text+"<input type='hidden' name='ymmo["+newlist+"][year]' value='"+year+"' /></td>");
		 col2 = $("<td>"+make_text+"<input type='hidden' name='ymmo["+newlist+"][make]'  value='"+make+"' /></td>");
		 col3 = $("<td>"+model_text+"<input type='hidden' name='ymmo["+newlist+"][model]'  value='"+model+"' /></td>");
		 col4 = $("<td>"+option_text+"<input type='hidden' name='ymmo["+newlist+"][options]'  value='"+option+"' /></td>");
		 col5 = $("<td><button type='button' class='btn btn-danger btn-xs' onclick=\"removeTableRow('#newlist','#ymmo_"+newlist+"');\"><i class='fa fa-remove'></i></button></td>");
		 
		 row.append(col1,col2,col3,col4,col5).prependTo("#newlist"); 
		 newlist++;
		
		}
		
		
		</script>
JS;
	return $script;
	}
	
	
}
?>
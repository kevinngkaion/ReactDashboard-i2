<?php
$return_value = [];
try{
	$records = $db->prepare("select * from {$account_db}.view_servicerequests where status <> 'Closed'");
	$records->execute();
	$return_value = $records->fetchAll();
}catch(Exception $e){
	$return_value[$e->getMessage()];
}
echo json_encode($return_value,JSON_NUMERIC_CHECK);
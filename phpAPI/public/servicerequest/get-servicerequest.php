<?php
$return_value = ['success'=>1,'data'=>[]];
try{
	$return_value = $db->getRecord("{$account_db}.view_servicerequests",['id'=> decryptData($data['srid'])]);
	
	$return_value['servicerequest_id'] = $return_value['id'];
	$return_value['id'] = encryptData($return_value['id']);

	$attachments = $db->prepare("select attachment_url,filename from {$account_db}.attachments where reference_table='servicerequests' and reference_id=:reference_id");
	$attachments->execute(['reference_id'=>$return_value['servicerequest_id']]);

	$return_value['attachments'] = $attachments->fetchAll();
}catch(Exception $e){
	$return_value = ['success'=>0,'description'=>$e->getMessage()];
}

echo json_encode($return_value);
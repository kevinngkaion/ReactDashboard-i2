<?php
$return_value = ['success'=>1,'data'=>[]];
try{
	$return_value = $db->getRecord("{$account_db}.documents",['id'=> decryptData($data['documentid'])]);
	
	$return_value['id'] = encryptData($return_value['id']);

}catch(Exception $e){
	$return_value = ['success'=>0,'description'=>$e->getMessage()];
}

echo json_encode($return_value);
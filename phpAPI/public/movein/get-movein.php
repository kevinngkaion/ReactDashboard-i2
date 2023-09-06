<?php
$return_value = ['success'=>1,'data'=>[]];
try{
	$return_value = $db->getRecord("{$account_db}.move_in",['id'=> decryptData($data['miid'])]);
	
	$return_value['unitrepair_id'] = $return_value['id'];
	$return_value['id'] = encryptData($return_value['id']);

}catch(Exception $e){
	$return_value = ['success'=>0,'description'=>$e->getMessage()];
}

echo json_encode($return_value);
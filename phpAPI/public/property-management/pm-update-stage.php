<?php
$return_value = ['success'=>1,'data'=>[]];
$sql_test = '';
try{
    $update_data = $data;
    
    $update_data['created_by'] = $user_token['user_id'];
    $update_data['rec_id'] = decryptData($data['reference_id']);
    $update_data['created_on'] = time();
    $update_data['type'] = 'stage';
    unset($update_data['reference_id']);
    

    //get rank
    $table = "{$account_db}.{$view}"; 
    $filter_data = [
        'stage_type'=>'pm'
    ];
    
    $and_rank_condition = "AND rank= '{$data['rank']}'";
    $sql = "select * from {$account_db}.stages WHERE stage_type=:stage_type {$and_rank_condition} ORDER by created_on DESC";
    $records_sth = $db->prepare($sql);
    $records_sth->execute($filter_data);
    $records = $records_sth->fetchAll();

    $update_data['stage'] = $records[0]['stage_name'];

    // print_r($update_data);
    $fields = array_keys($update_data);
    
    $sql = "insert {$account_db}.pm_updates (" . implode(",",$fields). ") values(:" . implode(",:",$fields) . ")";
    
    $sth = $db->prepare($sql);
    $sth->execute($update_data);

	$return_value = ['success'=>1,'description'=>'Record saved.', 'id' => encryptData($id)];
}catch(Exception $e){
	$return_value = ['success'=>0,'description'=>$e->getMessage(), 'sql_test' => $sql_test];
}
echo json_encode($return_value);


            
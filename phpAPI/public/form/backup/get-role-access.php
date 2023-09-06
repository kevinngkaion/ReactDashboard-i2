<?php
$return_value = ['success'=>1,'data'=>[]];
try{
    $table = $data['table'];
    $sql = "SELECT *
    FROM {$account_db}.vw_role_rights
    LEFT JOIN {$account_db}._user_roles AS user_roles ON user_roles.id = {$user_token['user_id']}
    WHERE {$account_db}.vw_role_rights.role_id = user_roles.role_id
      AND {$account_db}.vw_role_rights.table_name = '{$table}'";

    $record_sth = $db->prepare($sql);
    $record_sth->execute([]);
    $records = $record_sth->fetchAll();
	
    $role_access = [];
    if($records){
        foreach($records as $rec){
            $role_access[$rec['right_name']] = true;
            $role_access[$rec['role_id']] = true;
        }
    }
    $return_value = $role_access;

}catch(Exception $e){
	$return_value = ['success'=>0,'description'=>$e->getMessage()];
}
echo json_encode($return_value);


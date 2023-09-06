<?php
$return_value = ['success' => 1, 'description' => ''];

try {

    $id = $data['id'];

    $sth = $db->prepare("select * from {$account_db}.resident where id={$id}");
    $sth->execute();
    $user_info = $sth->fetch();

    //checking if old pass == inputed old pass
    if ($user_info['master_password'] == md5($data['old_master_password'])) {
        if ($data['new_master_password'] == $data['confirm_master_password']) {
            $decryptPass = md5($data['new_master_password']);
            $sth = $db->prepare("UPDATE {$account_db}.resident SET master_password = :master_password WHERE id = :id");
            $sth->bindParam(':master_password', $decryptPass);
            $sth->bindParam(':id', $id);
            $sth->execute();

            $sth->execute();
        } else {
            throw new Exception('New Password and Confirm Password not match');
        }
    } else {
        throw new Exception('Incorrect Old Password');
    }

} catch (Exception $e) {
	$return_value = ['success' => 0, 'description' => $e->getMessage()];
}
echo json_encode($return_value);
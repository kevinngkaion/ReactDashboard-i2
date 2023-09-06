<?php
$module = "occupant_registration";
$table = "occupant_reg";
$view = "vw_occupant_reg";

$id = $args[0];
$result = $ots->execute('module', 'get-record', [ 'id'=>$id, 'view'=>$view ]);
$record = json_decode($result);

$result = $ots->execute('module', 'get-ownership', []);
$ownership = json_decode($result);

$result = $ots->execute('form', 'get-role-access', [ 'table'=>$table ]);
$role_access = json_decode($result);

$result =  $ots->execute('module','get-listnew',[ 'table'=>'list_residenttype','field'=>'residenttype' ]);
$resident_types = json_decode($result);

// 23-0901 GET OWNERSHIP AND PROP TYPE FROM SYSTEM INFO
$result = $ots->execute('module','get-record',[ 'id'=>1,'view'=>'system_info' ]);
$system_info = json_decode($result);
$ownership = $system_info->ownership;
$property_type = $system_info->property_type;
?>
<style>
    .swal-wide { width: 850px !important; }
    table th, td { font-size:15px; padding:2px; }
</style>
<div class="row">
    <div class="col-9">
        <div class="py-4 ps-4 bg-gray">
            <div class="d-flex justify-content-between mb-3">
                <a onclick="location='<?=WEB_ROOT."/$module/"?>'"><label class="data-title" style="cursor:pointer;"><i class="fa-solid fa-arrow-left fa-sm"></i> <?=$record->fullname?></label></a>
                <?php if ($role_access->update==true): ?>
                    <a href='<?=WEB_ROOT."/$module/form/$id"?>'>
                        <button class="main-btn"> Edit </button>
                    </a>
                <?php endif ?>
            </div>

            <!-- DETAIL -->
            <table class="table table-bordered bg-white p-5" width="100%">
                <?php if ($property_type=="Commercial") { ?>
                <tr>
                    <td class="p-3 bold" width="30%">Company Name</td>
                    <td class="p-3" width="70%"><?=$record->company_name?></td>
                </tr>
                <?php } ?>
                <tr>
                    <td class="p-3 bold" width="30%">Type</td>
                    <td class="p-3" width="70%"><?=$record->type?></td>
                </tr>
                <tr>
                    <td class="p-3 bold">Address</td>
                    <td class="p-3"><?=$record->address?></td>
                </tr>
                <tr>
                    <td class="p-3 bold">Contact No.</td>
                    <td class="p-3"><?=$record->contact_no?></td>
                </tr>
                <tr>
                    <td class="p-3 bold">Email</td>
                    <td class="p-3"><?=$record->email?></td>
                </tr>
            </table>

            <div class="btn-group-buttons pull-right mt-5">
                <div class="d-flex flex-row-start">
                    <button type="button" class="main-btn" onclick="location='<?=WEB_ROOT."/$module/"?>'">Back</button>
                </div>
            </div>
        </div>
    </div>
    <div class="col-3">
        <div class="bg-white border-2 rounded-lg p-3 me-3 comphofilecontainer"></div>
    </div>
</div>
<script>
$(document).ready(function() {
    $(".btn-add-contract").click(function(){
        location = '<?=WEB_ROOT."/contract/form/?resident_id={$record->id}&unit_id={$record->unit_id}"?>';
    });

    $.ajax({
        url: '<?=WEB_ROOT;?>/comphofile/widget?reference=<?=$id?>&source=<?=$table?>&display=plain',
        type: 'GET',
        data: $(this).serialize(),
        dataType: 'html',
        beforeSend: function(){},
        success: function(data){
            $(".comphofilecontainer").html(data);
        },
        complete: function(){},
        error: function(jqXHR, textStatus, errorThrown){}
    });
});
</script>
<?php
    $data = [
		'id'=>$args[0],
        'view'=>'view_work_permit'
	];
	$work_permit = $ots->execute('tenant','get-record',$data);
	$work_permit = json_decode($work_permit);

	//get workers
	$data = [
		'view'=>'workers',
        'filters'=>[
            'rec_id' => $work_permit->rec_id,
			'ref_table' => "work_permit"
        ]
	];
	$workers = $ots->execute('tenant','get-records',$data);
	$workers = json_decode($workers);

	//get materials
	$data = [
		'view'=>'materials',
        'filters'=>[
            'rec_id' => $work_permit->rec_id,
			'ref_table' => "work_permit"
        ]
	];
	$materials = $ots->execute('tenant','get-records',$data);
	$materials = json_decode($materials);

	//get tools
	$data = [
		'view'=>'tools',
        'filters'=>[
            'rec_id' => $work_permit->rec_id,
			'ref_table' => "work_permit"
        ]
	];
	$tools = $ots->execute('tenant','get-records',$data);
	$tools = json_decode($tools);

	if($work_permit->nature_work == 1){
		$nature_work = 'Electrical';
	}

	elseif($work_permit->nature_work == 2){
		$nature_work = 'Mechanical';
	}

	elseif($work_permit->nature_work == 3){
		$nature_work = 'Civil Works';
	}

	elseif($work_permit->nature_work == 4){
		$nature_work = 'Plumbing';
	}

	elseif($work_permit->nature_work == 5){
		$nature_work = 'Pest Control';
	}

	else{
		$nature_work = 'Others';
	};

//PERMISSIONS
//get user role
$data = [	
	'view'=>'users'
];
$user = $ots->execute('property-management','get-record',$data);
$user = json_decode($user);

//check if has access
$data = [
	'role_id'=>$user->role_type,
	'table'=>'sr',
	'view'=>'role_rights'

];
$role_access = $ots->execute('form','get-role-access',$data);
$role_access = json_decode($role_access);
// var_dump($role_access);
?>


<div class="main-container">



	<div class="d-flex justify-content-between mb-3">
		<a onclick="window.location.href = '<?=WEB_ROOT;?>/tenant/service-request?submenuid=service_request'"><label class="data-title"  style="cursor: pointer;"><i class="fa-solid fa-arrow-left text-primary"></i> <?php echo $work_permit->tenant_name?></a>
		<?php if($role_access->update == true): ?>
			<a href='<?= WEB_ROOT ?>/tenant/form-edit-work-permit/<?= $args[0] ?>/Edit'  class='btn btn-sm btn-primary float-end btn-view-form px-5'>Edit</a>
		<?php endif; ?>
	</div>
	<table class="table table-data table-bordered tenant border-table text-capitalize" >
		<tr>
			<th>Type</th><td><?php echo $work_permit->sr_type?></td>
		</tr>
		<tr>
			<th>Nature Of Work</th><td><?php echo $nature_work?></td>
		</tr>
        <tr>
			<th>Date</th><td><?php echo $work_permit->date?></td>
		</tr>
		<tr>
			<th>Requestor Name</th><td><?php echo $work_permit->tenant_name?></td>
		</tr>
		<tr>
			<th>Resident Type</th><td><?php echo $work_permit->resident_type?></td>
		</tr>
        <tr>
			<th>Unit Number</th><td><?php echo $work_permit->unit?></td>
		</tr>
        <tr>
			<th>Contact Number</th><td><?php echo $work_permit->contact_num?></td>
		</tr>
        <tr>
			<th>Name Of Contractor</th><td><?php echo $work_permit->contractor_name?></td>
		</tr>
        <tr>
			<th>Scope Of Work</th><td><?php echo $work_permit->scope_work?></td>
		</tr>
        <tr>
			<th>Name Of Person-In-Charge</th><td><?php echo $work_permit->pic_name?></td>
		</tr>
        <tr>
			<th>Contract Number</th><td><?php echo $work_permit->pic_contact?></td>
		</tr>
        <tr>
			<th>Start Date</th><td><?php echo $work_permit->start_date?></td>
		</tr>
        <tr>
			<th>End Date</th><td><?php echo $work_permit->end_date?></td>
		</tr>
	</table>

	<br>
	<br>
	<span style='font-size:20px'>List of Workers/Personnel</span> 
	<table class="table table-data table-bordered workers border-table text-capitalize" >
		<tr>
			<th>Name</th>
			<th>Description</th>
		</tr>
		<?php 
			foreach($workers as $worker){
				?>
				<tr>
					<td><?= $worker->name?></td>
					<td><?= $worker->description?></td>
				</tr>
				<?php
			}
		?>
	</table>

	<br>
	<br>
	<span style='font-size:20px'>List of Materials</span> 
	<table class="table table-data table-bordered materials border-table text-capitalize" >
		<tr>
			<th>Quantity</th>
			<th>Description</th>
		</tr>
		<?php 
			foreach($materials as $material){
				?>
				<tr>
					<td><?= $material->qty?></td>
					<td><?= $material->description?></td>
				</tr>
				<?php
			}
		?>
	</table>

	<br>
	<br>
	<span style='font-size:20px'>List of Tools</span> 
	<table class="table table-data table-bordered tools border-table text-capitalize" >
		<tr>
			<th>Quantity</th>
			<th>Description</th>
		</tr>
		<?php 
			foreach($tools as $tool){
				?>
				<tr>
					<td><?= $tool->qty?></td>
					<td><?= $tool->description?></td>
				</tr>
				<?php
			}
		?>
	</table>

	<div class="btn-group-buttons pull-right">
		<div class="d-flex flex-row-reverse" style="padding: 5px;">
			<button type="submit" class="btn btn-dark btn-primary btn-cancel px-5">Back</button>
		</div>
	</div>
	</div>
<script>
	$(document).ready(function(){

		$('#datepicker').datepicker({
			format: 'yy-mm-d',
			timepicker: false,
			minDate: '+1D',
		});

		$('#datepicker1').datepicker({
			format: 'yy-mm-d',
			timepicker: false,
			minDate: '+1D',
		});


		$(".btn-cancel").on('click',function(){
			//loadPage('<?=WEB_ROOT;?>/location');
			window.location.href = '<?=WEB_ROOT;?>/tenant/service-request?submenuid=service_request';
		});

		$("input[id=parent_location]").autocomplete({
			autoSelect : true,
			autoFocus: true,
			search: function(event, ui) { 
				$('.spinner').show();
			},
			response: function(event, ui) {
				$('.spinner').hide();
			},
			source: function( request, response ) {
				$.ajax({
					url: '<?=WEB_ROOT;?>/location/search?display=plain',
					dataType: "json",
					type: 'post',
					data: {
						term: request.term,
					},
					success: function( data ) {
						response( data );
					}
				});
			},
			minLength: 2,
			select: function( event, ui ) {

				$(event.target).prev().val(ui.item.value);
				$(event.target).val(ui.item.label);

				return false;
			},
			change: function(event, ui){
				if(ui.item == null)
				{
					$(event.target).prev('input').val(0);
				}
			}
		});

		$("select[name=location_type]").on('change',function(){
			if($(this).val().toLowerCase() == 'property')
			{
				$(".location-container").addClass('d-none');
				$("input[name=parent_location]").val('');
				$("#parent_location_id").val(0);
			}else{
				$(".location-container").removeClass('d-none');
			}
		});
	});
</script>
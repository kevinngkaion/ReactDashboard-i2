<?php
    $data = [
		'id'=>$args[0],
        'view'=>'cm'
	];
	$cm = $ots->execute('property-management','get-record',$data);
	$cm = json_decode($cm);
    
    $data = [
		'id'=>$args[0],
        'view'=>'cm_updates',
        'filters'=>[
            'rec_id' => decryptData($args[0])
        ]
	];
	$pm_updates = $ots->execute('property-management','get-records',$data);
	$pm_updates = json_decode($pm_updates);
    
    
    $data = [
		'view'=>'view_equipments',
        'filters'=>[
            'rec_id' => $cm->equipment_id
        ]
	];
	$equipment = $ots->execute('property-management','get-records',$data);
	$equipment = json_decode($equipment)[0];
    
    $data = [
		'view'=>'service_providers_view',
        'filters'=>[
            'rec_id' => $cm->service_provider_id
        ]
	];
	$service_provider = $ots->execute('property-management','get-records',$data);
	$service_provider = json_decode($service_provider)[0];

	$data = [
		'reference_table' => 'cm',
		'reference_id' => $args['0']
	];
	$attachments = $ots->execute('files','get-attachments',$data);
	$attachments = json_decode($attachments);

	$data = [
		'id' => $args[0],
		'table' => 'cm_updates',
		'type' => 'stage',
	];
	$stages = $ots->execute('property-management','get-updates',$data);
	$stages = json_decode($stages);

	$data = ['stage_type'=>'cm'];
	$stage_dropdown = $ots->execute('property-management','get-stages',$data);
	$stage_dropdown = json_decode($stage_dropdown);
	// also for dropdown
	$stages_button = $stage_dropdown;
	$data = ['stage_type'=>'cm'];
	
	$status = $ots->execute('property-management','get-stages',$data);
	$status = json_decode($status);

	//users
	$data = [	
        'view'=>'users'
	];
	$user = $ots->execute('property-management','get-record',$data);
	$user = json_decode($user);

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
	'table'=>'cm',
	'view'=>'role_rights'

];
$role_access = $ots->execute('form','get-role-access',$data);
$role_access = json_decode($role_access);
// var_dump($role_access);
?>

<div class="rounded-sm title">

	<div class="d-flex justify-content-between mb-3">
		<a onclick="history.back()"><label class="data-title backIcon"  style="cursor: pointer;"><i class="fa-solid fa-arrow-left text-primary"></i> <?php echo $equipment->equipment_name;?></label></a>
		<?php if($cm->stage != 'closed'): ?>
			<?php if($role_access->update == true): ?>
				<a href='<?= WEB_ROOT ?>/property-management/form-edit-cm/<?= $args[0] ?>/Edit'  class='btn btn-sm btn-primary float-end btn-view-form px-5'>Edit</a>
			<?php endif; ?>
		<?php endif; ?>
	</div>

	<table class="table table-data table-bordered property-management border-table text-capitalize" >
		<tr>
			<th>Work Order</th><td>CM_<?php echo $cm->id?></td>
		</tr>
		<tr>
			<th>Equipment</th><td><?php echo $equipment->equipment_name?></td>
		</tr>
		<tr>
			<th>Category</th><td><?php echo $cm->category_id?></td>
		</tr>
        <tr>
			<th>Service Provider</th><td><?php echo $service_provider->company?></td>
		</tr>
		<tr>
			<th>Date Scheduled</th><td><?php echo explode(' ',date('Y-m-d h:i:s',$cm->created_on))[0]?></td>
		</tr>
        <tr>
			<th>Time Scheduled</th><td><?php echo explode(' ',date('Y-m-d h:i:s',$cm->created_on))[1]?></td>
		</tr>
		<tr>
			<th>Priority Level</th><td><?php echo $cm->priority_level?></td>
		</tr>
        <tr>
			<th>Critical</th><td><?php echo $cm->critical?></td>
		</tr>
        <tr>
			<th>Corrective Maintenance Start Date</th><td><?php echo explode(' ', $cm->cm_start_date)[0]; ?></td>
		</tr>
        <tr>
			<th>Corrective Maintenance End Date</th><td><?php echo explode(' ', $cm->cm_end_date)[0]; ?></td>
		</tr>
	</table>
    
	<div class="d-flex justify-content-between mt-5">
		<span style='font-size:20px'>Attachments</span> 
		<?php if($cm->stage != 'closed'): ?>
			<?php if($role_access->upload == true): ?>
			<button class='btn btn-lg btn-primary my-2 px-5' onclick="show_modal_upload(this)" update-table='cm_updates' reference-table='cm' reference-id='<?php echo $args[0]; ?>' id='<?php echo $args[0]; ?>'>Upload</button>
			<?php endif; ?>
		<?php endif; ?>
	</div>
	<table class="table table-data table-bordered property-management border-table text-capitalize" >
		<tr>
			<th>Create By</th>
			<th>Document</th>
			<th>Created By</th>
		</tr>
		<?php 
			foreach($attachments as $attachment){
				?>
				<tr>
					<td><?= $attachment->created_by_full_name?></td>
					<td><a href='<?= $attachment->attachment_url ?>' ><?= $attachment->filename ?></a></td>
					<td><?= date('Y-m-d', $attachment->created_on);?></td>
					
				</tr>
				<?php
			}
		?>
	</table>
	<?php 

	?>

	<div class="d-flex justify-content-between my-5">
		<span style='font-size:20px'>Stages</span>
		<?php if($cm->stage != 'closed'): ?>
			<?php if($role_access->update_thread == true): ?>
			<button class='btn btn-lg btn-primary px-5' onclick="show_modal_update(this)" update-table='cm_updates' reference-table='cm' reference-id='<?php echo $args[0]; ?>' id='<?php echo $args[0]; ?>'>Update</button>
			<?php endif; ?>
		<?php endif; ?>
	</div>

	<!-- <div class="d-flex align-items-center gap-4">
		<?php 
			// echo $stages[0]->rank;
			$ctr = 1;
			foreach($stages_button as $stage_button){
				?>
					<button class='btn <?= ($stage_button->rank <= $stages[0]->rank)?'current-status':'btn-outline-secondary'; ?> status-button'><label class="text-required text-capitalize"><?= ucfirst(str_replace('-',' ', $stage_button->stage_name)) ?></label></button> 
				<?php
				if($ctr < 6){
					?>
					<div>
						<i class="bi bi-arrow-right arrow-right-bigger"></i>
					</div>
					<?php
				}
				$ctr++;
			}
		?>
	</div> -->

	<div class="d-none d-lg-block">
		<div class="d-flex align-items-center gap-4">
			<?php $ctr = 1;?>
				<?php foreach($stages_button as $stage_button):?>
						<button class='btn btn-sm <?= ($stage_button->rank <= $stages[0]->rank)?'current-status':'btn-outline-secondary'; ?> status-button'><label class="text-required text-capitalize"><?= ucfirst(str_replace('-',' ', $stage_button->stage_name)) ?></label></button> 
						<?php  if($ctr < 6):?>
								<div class="pm-stage-arrow">
									<i class="bi bi-arrow-right arrow-right-bigger"></i>
								</div>
						<?php endif;?>
					<?php $ctr++;?>
				<?php endforeach;?>
		</div>
	</div>
	<!-- show for mobile -->
	<div class="d-lg-none">
		<div class="d-flex align-items-center gap-4">
			<?php $ctr = 1;?>
				<?php foreach($stages_button as $stage_button):?>
					<?php if($ctr <= 3):?>
						<button class='btn btn-sm <?= ($stage_button->rank <= $stages[0]->rank)?'current-status':'btn-outline-secondary'; ?> status-button'><label class="text-required text-capitalize"><?= ucfirst(str_replace('-',' ', $stage_button->stage_name)) ?></label></button> 
						<?php  if($ctr < 3):?>
								<div class="pm-stage-arrow">
									<i class="bi bi-arrow-right arrow-right-bigger"></i>
								</div>
						<?php endif;?>
					<?php endif;?>
		
					<?php $ctr++;?>
				<?php endforeach;?>
		</div>
		<div class="d-flex justify-content-end gap-4 mr-5">
			<?php $ctr = 1;?>
				<?php foreach($stages_button as $stage_button):?>
						<?php  if($ctr == 3):?>
								<div class="pm-stage-arrow">
									<i class="bi bi-arrow-down arrow-down-bigger"></i>
								</div>
						<?php endif;?>
					<?php $ctr++;?>
				<?php endforeach;?>
		</div>
		<div class="d-flex mt-3 flex-row-reverse align-items-center gap-4">
			<?php $ctr = 1;?>
				<?php foreach($stages_button as $stage_button):?>
					<?php if($ctr >3 && $ctr <=6):?>
						<button class='btn btn-sm <?= ($stage_button->rank <= $stages[0]->rank)?'current-status':'btn-outline-secondary'; ?> status-button'><label class="text-required text-capitalize"><?= ucfirst(str_replace('-',' ', $stage_button->stage_name)) ?></label></button> 
						<?php  if($ctr < 6):?>
								<div class="pm-stage-arrow">
									<i class="bi bi-arrow-left arrow-left-bigger"></i>
								</div>
						<?php endif;?>
					<?php endif;?>
		
					<?php $ctr++;?>
				<?php endforeach;?>
		</div>
	

	</div>

	<br>
	<span style='font-size:20px'>Comments And Updates</span>
	<table class="table table-data table-bordered property-management border-table" >
		<table class="table table-data table-bordered property-management border-table text-capitalize" >
			<tr>
				<th>Name</th>
				<th>Stage</th>
				<th>Comment</th>
				<th>Date and Time Created</th>
			</tr>
			<?php 

				foreach($stages as $stage){
					?>
					<tr>
						<td><?= $stage->created_by_full_name ?></td>
						<td><?= $stage->stage ?></td>
						<td><?= $stage->comment ?></td>
						<td><?= date('Y-m-d h:i:s', $stage->created_on) ?></td>
					</tr>
					<?php
				}
			?>
		</table>
	</table>
	<div class="btn-group-buttons pull-right">
		<div class="d-flex flex-row-reverse" style="padding: 5px;">
			<button type="submit" class="btn btn-dark btn-primary btn-back px-5">Back</button>
		</div>
	</div>		


	
	<script>
		function show_modal_update(button_data){
			$('#update').modal('show');
			reference_table = $(button_data).attr('reference-table');
			reference_id = $(button_data).attr('reference-id');
			update_table = $(button_data).attr('update-table');

			$("#upload #reference_table").val(reference_table);
			$("#upload #update_table").val(update_table);
			$("#upload #reference_id").val(reference_id);
		}
	</script>


	<!-- MODAL UPDATE STAGE -->
	<div class="modal" tabindex="-1" role="dialog" id='update'>
		<div class="modal-dialog  modal-dialog-centered" role="document">
		<div class="modal-content px-1 pb-4 pt-2">
				<div class="modal-header flex-row-reverse pb-0" style="border-bottom: 0px;">
					<button type="button" class="btn-close" data-dismiss="modal" onclick='$("#update").modal("hide")' aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body pt-0">
					<h3 class="modal-title text-primary align-center text-center mb-3">Update Stage</h3>
					<form action="<?=WEB_ROOT;?>/property-management/cm-update-stage?display=plain" method='post' id='form-update-stage' enctype="multipart/form-data">
						<input type="hidden" name='reference_id' id='reference_id' value='<?= $args[0] ?>'>
						<div class="col-12 my-4">
							<?php 
								$data = ['stage_type'=>'cm'];
								$stage_dropdown = $ots->execute('property-management','get-stages',$data);
								$stage_dropdown = json_decode($stage_dropdown);
							?>
							<label for="" class="text-required">Stage <span class="text-danger">*</span></label>
							<select class="form-control form-select" name="rank" required>
								<?php 
									foreach($stage_dropdown as $stage){
										?>
										<option value='<?= $stage->rank ?>'><?= ucfirst(str_replace('-',' ', $stage->stage_name)) ?></option>
										<?php
									}
								?>
							</select>
						</div>
						<div class="col-12 my-4">
							<label for="comments" class="text-required">Comments <span class="text-danger">*</span></label>
							<textarea name="comment" id="" class='form-control' required></textarea>
							
						</div>
						<div class="col-12 my-4">
							<label for="created_by" class="text-required">Name <span class="text-danger">*</span></label>
							<input type="text" name='created_by' id='created_by' class='form-control' value='<?= $user->full_name?>' readonly>
						</div>
						<div class="col-12 ">

						<div class="d-flex justify-content-center gap-4 w-100">	
							<button type='submit' class='btn btn-primary px-5'>Submit</button>
							<a  class='btn btn-light btn-cancel px-5' onclick='$("#update").modal("hide")'>Cancel</a>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>


<script>
	$(document).ready(function(){
		$('#form-update-stage').submit(function(e){
			e.preventDefault();
			$.ajax({
				url: $(this).prop('action'),
				type: 'POST',
				dataType: 'JSON',
				data: new FormData($(this)[0]),
				cache: false,
				contentType: false,
				processData: false,
				success: function(data){
					if(data.success == 1)
					{
						location.reload();
					}
				},
				complete: function(){
					show_success_modal();
				},
				error: function(jqXHR, textStatus, errorThrown){
					
				}
			});
			
		});

		$(".btn-back").on('click',function(){
			window.location.href = '<?=WEB_ROOT;?>/property-management/cm?submenuid=cm';
		});
	});		
</script>
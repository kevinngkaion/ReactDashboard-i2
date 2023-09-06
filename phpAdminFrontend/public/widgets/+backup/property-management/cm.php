<?php
$title = "Corrective Maintenance";
$module = "propertymanagement";
$table = "cm";
$view = "view_cm";

$fields = rawurlencode(json_encode(["ID" => "id", "Equipment" => "equipment_id", "Scope of Work" => "scope_of_work", "Amount" => "amount", "Priority" => "priority_level", "Service Provider" => "service_provider_id"]));
//PERMISSIONS
//get user role
$data = [
	'view' => 'users'
];
$user = $ots->execute('property-management', 'get-record', $data);
$user = json_decode($user);

//check if has access
$data = [
	'role_id' => $user->role_type,
	'table' => 'cm',
	'view' => 'role_rights'

];
$role_access = $ots->execute('form', 'get-role-access', $data);
$role_access = json_decode($role_access);
// var_dump($role_access);
?>

<div class="main-container">

	<?php if ($role_access->read != true) : ?>
		<div class="card mx-auto" style="max-width: 30rem;">
			<div class="card-header bg-danger">
				Unauthorized access
			</div>
			<div class="card-body text-center">
				You are not allowed to access this resource. Please check with system administrator.
			</div>
		</div>
	<?php else : ?>

		<div class="page-title"></div>

		<div class="d-flex justify-content-between mb-2">
			<div class="d-flex align-items-end">
				<label class="text-label-result px-3 mb-0" id="search-result">
				</label>
			</div>
			<div>
				<!-- <?php if ($role_access->create == true) : ?>
							<button class="btn btn-sm btn-add">+ Create New</i></button>
							<?php endif; ?> -->
			</div>
		</div>

		<!-- <div class="d-flex">
						<button class="btn tabs-table all-btn active1">All</button>
						<button class="btn tabs-table weekly-btn">Weekly</button>
						<button class="btn tabs-table monthly-btn">Monthly</button>
					</div> -->
		<div class="container-table">

			<div class="dropdown-menu-filter dropdown-menu " style="width: 22%" id="dropdownmenufilter">
				<div class="dropdown-menu-filter-fields"></div>

				<div class="btn-group-buttons mt-3">
					<div class="d-flex justify-content-between  mb-3 gap-2" style="padding: 5px;">

						<button class="btn-close-now btn btn-light btn-cancel">Close</button>
						<div>
							<button class="btn-reset-now btn-cancel btn mx-2">Reset</button>
							<button type="button" class="btn btn-dark btn-primary btn-filter-now px-5">Filter</button>
						</div>
					</div>
				</div>
			</div>


			<table id="jsdata"></table>

		</div>
</div>
<?php endif; ?>
<script>
	<?php $unique_id = $module . time(); ?>
	var t<?= $unique_id; ?>;
	$(document).ready(function() {


		t<?= $unique_id; ?> = $("#jsdata").JSDataList({
			ajax: {
				url: "<?= WEB_ROOT . "/module/get-list/{$view}?display=plain" ?>"
			},
			rowLink: {
				url: '<?= WEB_ROOT; ?>/property-management/view-cm/',
				params: [{
					"key": "id",
					"value": "encryptedid"
				}],
			},
			rowClass: 'hover:bg-gray-100',
			titleClass: 'text-rentaPageTitle',
			filterBoxID: 'dropdownmenufilter',
			buttons: [{
					icon: `<span class="material-symbols-outlined">add</span>`,
					title: "Create New",
					class: "btn-add btn-blue",
					id: "edit",
					href: "<?php echo WEB_ROOT; ?>/property-management/form-add-cm",
				},
				// <?php if ($role_access->delete == true) : ?> {
				// 		icon: `<span class="material-symbols-outlined">delete</span>`,
				// 		title: "Delete",
				// 		class: "btn-delete-filter",
				// 		id: "delete",
				// 	},
				// <?php endif; ?>
				// <?php if ($role_access->download == true) : ?> 
				{
					icon: `<span class="material-symbols-outlined">arrow_downward</span>`,
					title: "Download",
					class: "btn-download",
					href: "<?= WEB_ROOT; ?>/module/download/?display=csv&module=<?= $module ?>&table=<?= $table ?>&view=<?= $table ?>&fields=<?= $fields ?>",
					id: "download",
				},
			<?php endif; ?> {
				icon: `<span class="material-symbols-outlined">filter_list</span>`,
				title: "Filter",
				class: "filter",
			}

			],
			columns: [{
					data: "rec_id",
					label: "Work Order #",
					class: 'w-10',
					datatype: 'none',
					// render: function(data, row) {
					// 	return '<input class="" type="checkbox" id="' + row.id + '" name="check_box" table="cm" view_table="view_cm"  reload="<?= WEB_ROOT; ?>/property-management/cm?submenuid=cm">' +
					// 		'<a href="<?= WEB_ROOT; ?>/property-management/view-cm/' + row.id + '/View" target="_self">CM_' + data + '</a>';
					// }
				},
				{
					data: "created_by_full_name",
					label: "Created By",
					class: ' ',
					datatype: 'none',
				},
				{
					data: "equipment_name",
					label: "Equipment",
					class: 'w-10',
					datatype: 'none',
				},
				{
					data: "scope_of_work",
					label: "Scope of Work",
					class: 'w-10',
					datatype: 'none',
				},
				{
					data: "amount",
					label: "Amount",
					class: ' ',
					datatype: 'none',
					render: function(data, row) {
						return '₱ ' + data.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");

					}
				},
				{
					data: "priority_level",
					label: "Priority",
					class: ' text-center',
					datatype: 'select',
					list: ['1|Priority', '2|Priority', '3|Priority', '4|Priority', '5|Priority'],
					render: function(data, row) {
						return 'Priority ' + data;
					}
				},
				{
					data: "service_providers_name",
					label: "Service Provider",
					class: 'w-15',
					datatype: 'none',
				},
				{
					data: "critical",
					label: "Critical",
					class: ' ',
					datatype: 'select',
					list: ['No', 'Yes'],
					render: function (data,row){
						if (data == 'Yes'){
						return '<div class="data-box-y">' + data + '</div>';
						}
						else{
							return '<div class="data-box-n">' + data + '</div>';
						}
					}
				},
				{
					data: "cm_start_date",
					label: "Date Started",
					class: ' ',
					datatype: 'date',
					render: function(data, row) {
						var start_date = new Date(row.cm_start_date);
						return start_date.getFullYear() + "-" + start_date.getMonth() + "-" + start_date.getDate();
					}
				},
				{
					data: "rank",
					visible: false,
				},
				{
					data: "aging",
					visible: false,
					searchable: false
				},
				{
					data: "stage",
					label: "Status",
					class: ' ',
					datatype: 'select',
					list: ['Open', 'Ongoing', 'Closed'],
					render: function(data, row) {
						if (data == 'open' || data == 'acknowledge') {
							return "Open";
						} else if (data == 'work-started' || data == 'work-completed' || data == 'property-manager-verification') {
							return 'Ongoing';
						} else if (data == 'closed') {
							return 'Closed';
						}
					}
				},
				{
					data: null,
					label: "Action",
					class: '',
					render: function(data, row) {
						return '<a class="btn btn-sm text-primary btn-delete-icon" onclick="show_delete_modal(this)" title="Are you sure?" role_access="<?= $role_access->delete ?>" rec_id="' + row.rec_id + '" del_url="<?= WEB_ROOT ?>/property-management/delete-record/' + row.id + '?display=plain&table=cm&view_table=view_pm&redirect=/property-management/cm?submenuid=cm"><i class="bi bi-trash-fill"></i></a>'
					},
					orderable: false
				},
			],
		});

		$(document).ready(function() {
			$("input[name='aging']").parent().hide();
			$("input[name='rank']").parent().hide();
		});

		$(document).on("click", ".row-id", function() {
			// var data = table.row(this).data();
			var id = $(this).data("id");
			window.location.href = "<?= WEB_ROOT ?>/property-management/view-cm/" + id + "/View";
			// console.log(data);
			// alert('div clicked')
		});


		$('.all-btn').on('click', function(e) {
			$(".all-btn").addClass('active1');
			$(".weekly-btn").removeClass('active1');
			$(".monthly-btn").removeClass('active1');

		});

		$('.weekly-btn').on('click', function(e) {
			$(".weekly-btn").addClass('active1');
			$(".all-btn").removeClass('active1');
			$(".monthly-btn").removeClass('active1');
		});

		$('.monthly-btn').on('click', function(e) {
			$(".monthly-btn").addClass('active1');
			$(".weekly-btn").removeClass('active1');
			$(".all-btn").removeClass('active1');

		});

		// $(".btn-download").on('click',function(){
		// 	location = "<?= WEB_ROOT; ?>/module/download/?display=csv&module=<?= $module ?>&table=<?= $table ?>&view=<?= $table ?>&fields=<?= $fields ?>";
		// });

		$('.btn-delete-filter').on('click', function() {
			var table = $('input[name="check_box"]').attr('table');
			var view_table = $('input[name="check_box"]').attr('view_table');
			var redirect = $('input[name="check_box"]').attr('reload');

			var ids = [];
			$('input[name="check_box"]').each(function() {
				var $this = $(this);

				if ($this.is(":checked")) {
					ids.push($this.attr("id"));
				}
			});
			if (ids.length != 0) {
				var url = '<?= WEB_ROOT; ?>/property-management/delete-records?display=plain';

				table_delete_records(ids, table, view_table, redirect, url);
			}
		});



		$('.filter').on('click', function() {
			$(".dropdown-menu").toggle();
		});

		$('.btn-status').off('click').on('click', function() {
			$('#collapse-status').collapse('toggle');
		});

		$('#collapse-status').on('hidden.bs.collapse', function() {
			$('#up1').hide();
			$('#down1').show();

		});

		$('#collapse-status').on('show.bs.collapse', function() {
			$('#up1').show();
			$('#down1').hide();

		});

		$('.btn-building').off('click').on('click', function() {
			$('#collapse-building').collapse('toggle');
		});

		$('#collapse-building').on('hidden.bs.collapse', function() {
			$('#up2').hide();
			$('#down2').show();

		});

		$('#collapse-building').on('show.bs.collapse', function() {
			$('#up2').show();
			$('#down2').hide();

		});

		$('.btn-priority-level').off('click').on('click', function() {
			$('#collapse-priority-level').collapse('toggle');
		});

		$('#collapse-priority-level').on('hidden.bs.collapse', function() {
			$('#up3').hide();
			$('#down3').show();

		});

		$('#collapse-priority-level').on('show.bs.collapse', function() {
			$('#up3').show();
			$('#down3').hide();

		});

		$('.btn-stages').off('click').on('click', function() {
			$('#collapse-stages').collapse('toggle');
		});

		$('#collapse-stages').on('hidden.bs.collapse', function() {
			$('#up4').hide();
			$('#down4').show();

		});

		$('#collapse-stages').on('show.bs.collapse', function() {
			$('#up4').show();
			$('#down4').hide();

		});

		$('.bi-caret-up-fill').hide();
	});
</script>
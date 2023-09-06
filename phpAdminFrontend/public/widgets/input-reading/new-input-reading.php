<?php

$_POST['month'] = number_format($_POST['month'], 0);
$month_selected = $_POST['month'];




if ($_POST['utility_type'] == 'electrical') {
    $utility_type = 'Electricity';
    $data = [
        'view' => 'meter',
        'filters' => [
            'utility_type' => ucfirst($utility_type),
            'meter_type' => 'Submeter'
        ]
    ];
} else if ($_POST['utility_type'] == 'water') {
    $utility_type = ucfirst($_POST['utility_type']);
    $data = [
        'view' => 'meter',
        'filters' => [
            'utility_type' => ucfirst($utility_type),
            'meter_type' => 'Submeter'
        ]
    ];
} else {
    $data = [
        'view' => 'meter',
        'filters' => [
            'meter_type' => 'Submeter'
        ]
    ];
}

$meters = $ots->execute('utilities', 'get-records', $data);
$meters = json_decode($meters);
// print_r($meters);
$data = [
    'utility_type' => $utility_type,
    'month' => ($_POST['month'] < 10) ? '0' . $_POST['month'] : $_POST['month'],
    'year' => $_POST['year'],
];
// print_r($data);
$rates = $ots->execute('utilities', 'get-billing-rates', $data);
$rates = json_decode($rates)->billing_data->rates;
?>
<input type="hidden" name='table' value='meter_readings'>
<input type="hidden" name='view_table' value='view_meter_readings'>
<input type="hidden" name='month' value='<?= $_POST['month'] ?>'>
<input type="hidden" name='year' value='<?= $_POST['year'] ?>'>
<table class="table table-data water-table" style="box-shadow: 0px 3px 6px 0px rgba(0, 0, 0, 0.2);">
    <thead class="header-design">
        <tr>
            <th>Meter Name/ID</th>
            <th>Last Reading</th>
            <?php if ($_POST['utility_type'] == "all") { ?>
                <th>Type</th>
            <?php } ?>

            <th>New Reading</th>
            <th>Consumption</th>
            <th>Upload Photo</th>
            <!-- <th>Amount</th> -->
            <th>Actions</th>
        </tr>
    </thead>
    <script>

    </script>

    <tbody class="table-body">
        <?php
        // var_dump($meters);
        $ctr = 0;
        $total_con = 0;
        $total_amount = 0;
        foreach ($meters as $meter) {
            // var_dump($meter->meter_readings);
            $data = [
                'meter_id' => decryptData($meter->id),
                'month' => $_POST['month'],
                'year' => $_POST['year'],
            ];

            $last_meter_reading = $ots->execute('utilities', 'get-last-meter-readings', $data);

            $meter_id = json_decode($last_meter_reading)->meter_id;
            // var_dump($meter_id);

            $last_meter = json_decode($last_meter_reading)->reading;
            $last_consumption = json_decode($last_meter_reading)->consumption;
            $date_last_reading = json_decode($last_meter_reading)->month . '-' . json_decode($last_meter_reading)->year;


            $data = [
                'meter_id' => decryptData($meter->id),
                'month' => $_POST['month'],
                'year' => $_POST['year'],
            ];

            $current_reading = $ots->execute('utilities', 'get-current-reading', $data);
            $current_reading = json_decode($current_reading);

            // var_dump($current_reading);

            $img =  $current_reading->upload_img;
            if ($_POST['utility_type'] == 'electrical') {
                $utility_type = 'Electricity';
            } else {
                $utility_type = ucfirst($_POST['utility_type']);
            }


            $data = [
                'utility_type' => $utility_type,
                'month' => ($_POST['month'] < 10) ? '0' . $_POST['month'] : $_POST['month'],
                'year' => $_POST['year'],
            ];

            $rates = $ots->execute('utilities', 'get-billing-rates', $data);
            $rates = json_decode($rates)->billing_data->rates;


            $result =  $ots->execute('module', 'get-listnew', ['table' => 'vw_resident', 'condition' => 'unit_id="' . $meter->unit_id . '"', 'field' => 'fullname']);
            $name = json_decode($result);
            // var_dump($name[0]);


            $monthNumber = $current_reading->month . $current_reading->year;
            $currentMonthNumber = date('n') . date('Y');
        ?>

            <tr class="tr-data reading-table">

                <td>
                    <span class="id_table d-none "><?= $current_reading->id ? $current_reading->id : '' ?></span><br>
                    <input class="meter_id" type="hidden" name="meter_id" value="<?= decryptData($meter->id)  ?>">
                    <input type="hidden" class="utility_type" name="utility_type[]" value="<?= ucfirst($_POST['utility_type']) ?>">
                    <?= $meter->meter_name ?> <?= $name[0] ? '[' . $name[0] . ']' : '' ?>
                </td>
                <td class="flex-column align-items-start">
                    <input type="hidden" class="last-reading" id="meter_last_reading_<?= $ctr ?>" name="last_reading[]" value="<?= $last_meter ?>">

                    <span> Date : <b><?= $date_last_reading ? $date_last_reading : '' ?></b></span>
                    <span> Reading : <b class="last-reading"><?= $last_meter ? $last_meter : '0' ?></b></span>
                    <span>Consumption :<b class="last-consumption"> <?= $last_consumption ? $last_consumption : '0' ?></b></span>
                </td>
                <?php if ($_POST['utility_type'] == "all") { ?>
                    <td><?= $meter->utility_type ?></td>
                <?php } ?>
                <td>
                    <input value="<?= $current_reading->reading ?? '' ?>" <?= ($current_reading->reading) ? ($currentMonthNumber === $monthNumber) ? ''  : 'readonly' : "" ?> name="meter_reading[]" id="meter_reading" type="number" class="input_0  meter_reading" placeholder="-" required>
                    <span class="er"></span>
                </td>
                <td>

                    <input type="hidden" class="input-consumption consumption_<?= $ctr ?>" value="<?= $consumption ?>" name="consumption[]">
                    <span class="new-consumption span-consumption_<?= $ctr ?>"><?= $consumption ?></span>

                </td>
                <td class="">

                    <div class="gap-3 flex-row  align-items-center">
                        <div class="d-flex gap-3">
                            
                            <?php if ($img) : ?>
                                <input id="myFileInput<?= decryptData($meter->id) ?>" class="myFileInput d-none" type="file" accept="image/*" name="image">
                                <label for="myFileInput<?= decryptData($meter->id) ?>" type="button" class="upload-btn"><span class="material-icons">photo_camera</span></label>
                                <a class="view-text old-img" target="_blank" href="<?= $img ?>"><span class="material-icons">image</span></a>
                            <?php else :  ?>

                                <input id="myFileInput<?= decryptData($meter->id) ?>" class="myFileInput d-none" type="file" accept="image/*" name="image">
                                <label for="myFileInput<?= decryptData($meter->id) ?>" type="button" class="upload-btn"><span class="material-icons">photo_camera</span></label>
                                <a class="view-text now-img" href="" target="_blank"><span class="material-icons">image</span></a>
                            <?php endif; ?>
                        </div>

                    </div>
                </td>
                <!-- <td>
                    <span class="amount"><?= number_format($amount, 2) ?></span>
                </td> -->
                <td class="justify-content-center">

                    <span class="material-icons bg-save <?= $current_reading->reading ? '' : 'd-none    ' ?>  ">check_circle</span>
                    <span class="status bg-not <?= $current_reading->reading ? 'd-none ' : ' ' ?> save-indicator"></span>
                </td>
            </tr>

        <?php
            $ctr++;
        }
        ?>
        <tr class="tr-data">
            <td>Total:</td>
            <td></td>
            <?php if ($_POST['utility_type'] == "all") { ?>
                <td></td>
            <?php } ?>
            <td></td>
            <td>
                <input type="hidden" class='total_input_con total_con<?= $ctr ?>' value='<?= ($total_con) ?>' name='total_con[]'>
                <span class='total span-total_con<?= $ctr ?>'><?= $total_con ?></span>
            </td>
            <td ></td>
            <!-- <td>
                <span class='total_amount'><?= number_format($total_amount, 2) ?></span>
            </td> -->
            <td></td>

        </tr>
    </tbody>

</table>

<script>
    // const meter = <?= json_encode($meters) ?>;
    // console.log(meter[1].meter_readings[1].meter_id);


    $(document).ready(function() {
        $(".myFileInput").on("change", function(e) {
            var files = e.target.files;
            if (files && files.length > 0) {
                var file = files[0];
            }
        });

        $('.now-img').hide()

        $('.tr-data').each(function() {
            const $trData = $(this);
            const meterReadingInput = $trData.find('.meter_reading');
            const meter_id = $trData.find('.meter_id').val();
            let id = $trData.find('.id_table').text();
            const last_reading = parseFloat($trData.find('.last-reading').text());
            const upload = $trData.find('.myFileInput');


            // console.log(meter_id)

            // Function to compress the image
            function compressImage(file, maxSize, callback) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    const img = new Image();
                    img.onload = function() {
                        const canvas = document.createElement('canvas');
                        let width = img.width;
                        let height = img.height;

                        // Check if the image size exceeds the maximum size
                        if (file.size > maxSize) {
                            const aspectRatio = width / height;
                            if (width > height) {
                                width = Math.sqrt(maxSize * aspectRatio);
                                height = width / aspectRatio;
                            } else {
                                height = Math.sqrt(maxSize / aspectRatio);
                                width = height * aspectRatio;
                            }
                        }

                        canvas.width = width;
                        canvas.height = height;

                        const ctx = canvas.getContext('2d');
                        ctx.drawImage(img, 0, 0, width, height);

                        canvas.toBlob(function(blob) {
                            callback(blob);
                        }, file.type);
                    };
                    img.src = event.target.result;
                };
                reader.readAsDataURL(file);
            }
            const last_consumption = $trData.find('.last-consumption');
            const new_consumption = $trData.find('.new-consumption');
            const input_consumption = $trData.find('.input-consumption');


            if (!(meterReadingInput.val())) {

                new_consumption.text(" ")
                input_consumption.val(" ")
            } else {
                const new_consumption_number = meterReadingInput.val() - last_reading
                new_consumption.text(new_consumption_number)
                input_consumption.val(new_consumption_number)

            }

            meterReadingInput.on('input', function() {
                if ($(this).val() > last_reading) {
                    const new_consumption_number = $(this).val() - last_reading
                    new_consumption.text(new_consumption_number)
                    input_consumption.val(new_consumption_number)
                    Totalconsumption()
                } else {
                    new_consumption.text("Must Higher Then Last Reading ")
                    input_consumption.val("")

                }
            })


            const updateMeterReading = function() {
                const meter_reading = meterReadingInput.val();
                let year = $('#year').val();

                let month = parseInt($('#month').val(), 10);
                const consumption = meter_reading - last_reading;
                const image = upload.val() ? upload[0].files[0] : '';

                if (upload.val()) {
                    const file = image;
                    const maxSize = 2 * 1024 * 1024; // Maximum file size in bytes (2 MB)
                    compressImage(file, maxSize, function(compressedBlob) {
                        // Compressed image blob available here
                        const formData = new FormData();
                        // alert(id)
                        formData.append('id', id);
                        formData.append('meter_id', meter_id);
                        formData.append('year', year);
                        formData.append('month', month);
                        formData.append('new_reading', meter_reading);
                        formData.append('consumption', consumption);
                        formData.append('image', compressedBlob);

                        sendFormData(formData);
                    });
                } else {

                    const formData = new FormData();
                    formData.append('id', id);
                    formData.append('meter_id', meter_id);
                    formData.append('year', year);
                    formData.append('month', month);
                    formData.append('new_reading', meter_reading);
                    formData.append('consumption', consumption);

                    sendFormData(formData);
                }
            };

            const sendFormData = function(formData) {
                $.ajax({
                    url: '<?= WEB_ROOT; ?>/input-reading/input-new-reading?display=plain',
                    dataType: 'json',
                    type: 'post',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        console.log(data)
                        id = data.id
                        const $bgSave = $trData.find('.bg-save');
                        const img = $trData.find('.now-img');
                        const oldimg = $trData.find('.old-img');
                        const $bgNot = $trData.find('.bg-not');
                        if (data.data.new_reading > 0) {
                            $bgSave.removeClass('d-none');
                            $bgNot.addClass('d-none');
                        } else {
                            $bgSave.addClass('d-none');
                            $bgNot.removeClass('d-none');
                        }
                        if (data.attachment_url) {
                           
                            img.show();
                            img.attr('href', data.attachment_url);
                            oldimg.attr('href', data.attachment_url);

                        }

                    },
                    error: function(xhr, status, error) {
                        console.log(error, status, xhr);
                    }
                });
            };

            meterReadingInput.change(updateMeterReading);
            upload.change(updateMeterReading);

        });

        const Totalconsumption = () => {

            var consumptionArray = []; // Initialize an empty array

            $('.new-consumption').each(function() {
                consumptionArray.push($(this).text()); // Add the text to the array
            });
            const filteredArray = consumptionArray.filter(element => element.trim() !== '');
            const sum = filteredArray.reduce((accumulator, currentValue) => accumulator + currentValue, 0);
            var joinedArray = filteredArray.join('+');
            // console.log(joinedArray)
            $('.total').text(eval(joinedArray))
            // console.log(consumptionArray)
            $('.total_input_con').val(eval(joinedArray))

        }
        Totalconsumption()


        //input number
        function check(e, value) {
            var utility = $("#utility_type").val();
            if (utility == "electricity") {
                //Check Charater
                var unicode = e.charCode ? e.charCode : e.keyCode;
                if (value.indexOf(".") != -1)
                    if (unicode == 46) return false;
                if (unicode != 8)
                    if ((unicode < 48 || unicode > 57) && unicode != 46) return false;
            }
        }

        function checkLength() {
            var utility = $("#utility_type").val();
            if (utility == "electricity") {
                var fieldLength = document.getElementById('meter_reading').value.length;
                //Suppose u want 5 number of character
                if (fieldLength <= 5) {
                    return true;
                } else {
                    var str = document.getElementById('meter_reading').value;
                    str = str.substring(0, str.length - 1);
                    document.getElementById('meter_reading').value = str;
                }
            }
        }
        if ($('#month').val() + $('#year').val() != <?= date('m') . date('Y') ?>) {
            // console.log("not")
            $('.meter_reading').prop('readonly',true)
            $('.upload-btn').hide()

        } 
        if( $('#month').val() < <?= date('m') ?> && $('#year').val() == <?=  date('Y') ?> ) {
            $('.meter_reading').prop('readonly',false)
            $('.upload-btn').show()
        }
    });
</script>
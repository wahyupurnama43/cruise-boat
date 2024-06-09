<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-8">
        <h2>Setting</h2>
        <ol class="breadcrumb">
            <li>
                <a href="<?= BASEURL ?>/dashboard">Home</a>
            </li>
            <li class="active">
                Table Setting
            </li>

        </ol>
    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5> Table Setting </h5>
                    <div class="ibox-tools">
                    </div>
                </div>
                <div class="ibox-content">

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-example">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Value</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data['setting'] as $setting) :  ?>
                                <tr>
                                    <td><?= ++$i ?></td>
                                    <td><?= $setting['name'] ?></td>
                                    <td><?php if ($setting['name'] == 'HEADER_EMAIL') {
                                                echo '<img src="' . ASSETS . '/home/images/' . $setting['value'] . '" alt="" width="200px">';
                                            } else if ($setting['name'] == 'APIKEY' || $setting['name'] == 'VAKEY') {
                                                echo substr(Encripsi::encode('encrypt', $setting['value']), 0, 20) . '...';
                                            } else {
                                                echo $setting['value'];
                                            } ?>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-warning btn-xs"
                                            data-id="<?= $setting['id'] ?>" data-toggle="modal"
                                            data-target="#edit-modal"><i class="fa fa-edit"></i></button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Value</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- modal edit -->
<div class="modal fade" id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="edit-modal-label">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="<?= BASEURL ?>dashboard/edit_setting" id="form_edit" class="form-horizontal"
                class="sky-form" enctype="multipart/form-data">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="add-modal-label">Edit Setting</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="id">
                    <div class="form-group">
                        <label for="name" class="col-sm-2">Name</label>
                        <div class="col-sm-10">
                            <select name="name" class="form-control">
                                <?php foreach ($data['setting'] as $setting) :  ?>
                                <option value="<?= $setting['name'] ?>"><?= $setting['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group" id="value">
                        <label for="value" class="col-sm-2">Value</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="value">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save changes</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
        </div>
        </form>
    </div>
</div>


<script>
$(document).ready(function() {
    $('.dataTables-example').DataTable({
        dom: '<"html5buttons"B>lTfgitp',
        buttons: [{
                extend: 'copy'
            },
            {
                extend: 'csv'
            },
            {
                extend: 'excel',
                title: 'Passenger_List'
            },
            {
                extend: 'pdf',
                title: 'Passenger_List'
            },

            {
                extend: 'print',
                customize: function(win) {
                    $(win.document.body).addClass('white-bg');
                    $(win.document.body).css('font-size', '10px');

                    $(win.document.body).find('table')
                        .addClass('compact')
                        .css('font-size', 'inherit');
                }
            }
        ]

    });

    $(document).delegate("[data-target='#edit-modal']", "click", function() {
        let id = $(this).attr('data-id')

        $.ajax({
            type: "POST", //we are using GET method to get data from server side
            url: '<?= BASEURL ?>dashboard/getDataBy', // get the route value
            data: {
                model: 'M_User',
                table: 'tb_setting',
                attr: 'id',
                id: id,
            }, //set data
            success: function(
                response
            ) { //once the request successfully process to the server side it will return result here
                res = JSON.parse(response);
                $("#form_edit [name=\"name\"]").val(res.name);

                if (res.name == 'HEADER_EMAIL') {
                    $("#form_edit [name=\"value\"]").prop("type", "file");
                } else {
                    $("#form_edit [name=\"value\"]").prop("type", "text");
                }

                if (res.name !== 'APIKEY' && res.name !== 'VAKEY' && res.name !==
                    'HEADER_EMAIL') {
                    $("#form_edit [name=\"value\"]").val(res.value);
                } else if (res.name == 'APIKEY' && res.name == 'VAKEY') {
                    $("#form_edit [name=\"value\"]").val('');
                }

                $("#id").val(res.id);
            }
        });
    });


});
</script>
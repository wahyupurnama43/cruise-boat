<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-8">
        <h2>User</h2>
        <ol class="breadcrumb">
            <li>
                <a href="<?= BASEURL ?>/dashboard">Home</a>
            </li>
            <li class="active">
                Table user
            </li>

        </ol>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5> Table Hak Akses </h5>
                    <div class="ibox-tools">
                        <button type="button" class="btn-primary" data-toggle="modal" data-target="#add-grade"> Add Hak Akses</button>
                    </div>
                </div>
                <div class="ibox-content">

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-example">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Hak Akses</th>
                                    <th>Diskon Domestic</th>
                                    <th>Diskon International</th>
                                    <th>List Akses</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach ($data['getAllGrade'] as $d) : ?>
                                    <tr>
                                        <td><?= ++$s ?></td>
                                        <td><?= $d['nameGrade'] ?></td>
                                        <td><?= ($d['diskon'] <= 100) ? $d['diskon'] . '%' : 'Rp ' . $d['diskon'] ?></td>
                                        <td>
                                            <?= ($d['diskon_internasional'] <= 100) ? $d['diskon_internasional'] . '%' : 'Rp ' . $d['diskon_internasional'] ?>
                                        </td>
                                        <td><?= $d['akses'] ?></td>
                                        <td>
                                            <button type="button" class="btn btn-warning btn-xs" data-id="<?= $d['id'] ?>" data-toggle="modal" data-target="#edit-grade"><i class="fa fa-edit"></i></button>
                                            <a href="<?= BASEURL ?>dashboard/delete/<?= $d['id'] ?>/<?= Encripsi::encode('encrypt', 'tb_grade') ?>/<?= Encripsi::encode('encrypt', 'user') ?>" onclick="return confirm('Yakin Mau Dihapus?')" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>

                            <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>Hak Akses</th>
                                    <th>Diskon</th>
                                    <th>Ijin</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5> Table User </h5>
                    <div class="ibox-tools">
                        <button type="button" class="btn-primary" data-toggle="modal" data-target="#add-modal"> Add User</button>
                    </div>
                </div>
                <div class="ibox-content">

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-example">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Username</th>
                                    <th>Name</th>
                                    <th>Hak Akses</th>
                                    <th>Mitra</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data['getAllUser'] as $user) :  ?>
                                    <?php
                                    $mitra = Controller::model('M_User')->getMitra($user['mitraCompanyID']);
                                    ?>
                                    <tr>
                                        <td><?= ++$i ?></td>
                                        <td><?= $user['userID'] ?></td>
                                        <td><?= $user['userName'] ?></td>
                                        <td><?= $user['grade'] ?></td>
                                        <td><?= ($mitra['companyName'] !== NULL) ? $mitra['companyName'] : '-' ?></td>
                                        <td>
                                            <button type="button" class="btn btn-warning btn-xs" data-id="<?= $user['id'] ?>" data-toggle="modal" data-target="#edit-modal"><i class="fa fa-edit"></i></button>
                                            <a href="<?= BASEURL ?>dashboard/send_user/<?= $user['id'] ?>" onclick="return confirm('Yakin Mau Kirim Email Kembali ?')" class="btn btn-primary btn-xs"><i class="fas fa-envelope-open-text"></i></a>
                                            <a href="<?= BASEURL ?>dashboard/delete/<?= $user['id'] ?>/<?= Encripsi::encode('encrypt', 'tb_user') ?>/<?= Encripsi::encode('encrypt', 'user') ?>" onclick="return confirm('Yakin Mau Dihapus?')" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>Username</th>
                                    <th>Name</th>
                                    <th>Hak Akses</th>
                                    <th>Mitra</th>
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
            <form method="POST" action="<?= BASEURL ?>dashboard/edit_user" id="form_edit" class="form-horizontal" class="sky-form" enctype="multipart/form-data">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="add-modal-label">Edit User</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="id">
                    <div class="form-group">
                        <label for="username" class="col-sm-2">Username</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="username" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password" class="col-sm-2">Password</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" name="password">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="grade" class="col-sm-2">Hak Akses</label>
                        <div class="col-sm-10">
                            <select name="grade" id="grade2" class="form-control">
                                <option value="administrator" data-id="">Administrator</option>
                                <option value="staff" data-id="">Staff</option>
                                <?php foreach ($data['getAllGrade'] as $p) : ?>
                                    <option value="<?= $p['id'] ?>" data-id="<?= $p['grade'] ?>"><?= $p['nameGrade'] ?></option>
                                <?php endforeach; ?>
                                <!-- <option value="verified_agent">Verified Agent</option> -->
                                <option value="syahbandar" data-id="">Syahbandar</option>
                                <option value="accounting" data-id="">Accounting</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="nama" class="col-sm-2">Nama</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="nama" required>
                        </div>
                    </div>
                    <div class="form-group" id="mitra_field2" style="display: none;">
                        <label for="agent" class="col-sm-2">Agent</label>
                        <div class="col-sm-10">
                            <select name="agent" id="agent" class="form-control">
                                <?php foreach ($data['getAllMitra'] as $editM) :  ?>
                                    <option value="<?= $editM['id'] ?>"><?= $editM['companyName'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
        </div>
        </form>
    </div>
</div>


<!-- modal tambah -->
<div class="modal fade" id="add-modal" tabindex="-1" role="dialog" aria-labelledby="add-modal-label">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="<?= BASEURL ?>dashboard/create_user" class="form-horizontal" id="add-form" class="sky-form" enctype="multipart/form-data">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="add-modal-label">Add New User</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="username" class="col-sm-2">Username</label>
                        <div class="col-sm-10">
                            <input type="email" class="form-control" id="username" name="username" placeholder="email address (example@gmail.com)" required>
                            <div class="invalid-feedback" id="error-nof"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password" class="col-sm-2">Password</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" name="password" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="grade" class="col-sm-2">Hak Akses</label>
                        <div class="col-sm-10">
                            <select name="grade" id="grade" class="form-control">
                                <option value="">Pilih Hak Akses</option>
                                <option value="administrator" data-id="">Administrator</option>
                                <option value="staff" data-id="">Staff</option>
                                <?php foreach ($data['getAllGrade'] as $p) : ?>
                                    <option value="<?= $p['id'] ?>" data-id="<?= $p['grade'] ?>"><?= $p['nameGrade'] ?></option>
                                <?php endforeach; ?>
                                <option value="syahbandar" data-id="">Syahbandar</option>
                                <option value="accounting" data-id="">Accounting</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="nama" class="col-sm-2">Nama</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="nama" name="nama" required>
                        </div>
                    </div>
                    <div class="form-group" id="mitra_field" style="display: none;">
                        <label for="agent" class="col-sm-2">Agent</label>
                        <div class="col-sm-10">
                            <select name="agent" id="agent" class="form-control">
                                <option value="">Pilih Agent</option>
                                <?php foreach ($data['getAllMitra'] as $addM) :  ?>
                                    <option value="<?= $addM['id'] ?>"><?= $addM['companyName'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
        </div>
        </form>
    </div>
</div>


<!-- modal tambah Grade -->
<div class="modal fade" id="add-grade" tabindex="-1" role="dialog" aria-labelledby="add-modal-label">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="<?= BASEURL ?>dashboard/create_grade" class="form-horizontal" id="add-form" class="sky-form" enctype="multipart/form-data">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="add-modal-label">Add New Hak Akses</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="grade" class="col-sm-4">Hak Akses Agent</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="grade" name="grade" placeholder="" required>
                            <div class="invalid-feedback" id="error-nof"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="diskon" class="col-sm-4">Diskon Domestic <button type="button" class="btn btn-warning btn-xs " data-toggle="tooltip" data-placement="bottom" title="0-100% Pemberian Diskon dengan persen, 100 keatas pemberian Diskon dengan Rupiah">
                                i
                            </button></label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control mb-3" id="diskon" name="diskon">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="diskon_inter" class="col-sm-4">Diskon International <button type="button" class="btn btn-warning btn-xs " data-toggle="tooltip" data-placement="bottom" title="0-100% Pemberian Diskon dengan persen, 100 keatas pemberian Diskon dengan Rupiah">
                                i
                            </button></label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control mb-3" id="diskon_inter" name="diskon_inter">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-4">
                        </div>
                        <div class="col-sm-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="paylater" name="akses[]" id="flexCheckDefault">
                                <label class="form-check-label" for="flexCheckDefault">
                                    Paylater
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- modal edit -->
<div class="modal fade" id="edit-grade" tabindex="-1" role="dialog" aria-labelledby="edit-modal-label">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="<?= BASEURL ?>dashboard/edit_grade" id="form_edit" class="form-horizontal" class="sky-form" enctype="multipart/form-data">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="add-modal-label">Add New Hak Akses</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="id_grade">
                    <div class="form-group">
                        <label for="grade" class="col-sm-4">Hak Akses Agent</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="grade" name="grade" placeholder="" required>
                            <div class="invalid-feedback" id="error-nof"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="diskon" class="col-sm-4">Diskon <button type="button" class="btn btn-warning btn-xs " data-toggle="tooltip" data-placement="bottom" title="0-100% Pemberian Diskon dengan persen, 100 keatas pemberian Diskon dengan Rupiah">
                                i
                            </button></label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" id="diskon" name="diskon">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="diskon_inter" class="col-sm-4">Diskon International <button type="button" class="btn btn-warning btn-xs " data-toggle="tooltip" data-placement="bottom" title="0-100% Pemberian Diskon dengan persen, 100 keatas pemberian Diskon dengan Rupiah">
                                i
                            </button></label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control mb-3" id="diskon_inter" name="diskon_inter">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-4">
                        </div>
                        <div class="col-sm-4">
                            <div class="form-check">
                                <input class="form-check-input_edit" type="checkbox" value="paylater" name="akses[]" id="flexCheckDefault_edit">
                                <label class="form-check-label" for="flexCheckDefault_edit">
                                    Paylater
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
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
                    table: 'tb_user',
                    attr: 'id',
                    id: id,
                }, //set data
                success: function(response) { //once the request successfully process to the server side it will return result here
                    res = JSON.parse(response);
                    if (res.jenis_grade == "agent") {
                        $('#diskon_field2').css('display', '');
                        $('#mitra_field2').css('display', '');
                        $("#form_edit [name=\"priceNet\"]").val(res.diskon);
                    }
                    $("#form_edit [name=\"username\"]").val(res.userID);
                    $("#form_edit [name=\"grade\"]").val(res.grade);
                    $("#form_edit [name=\"nama\"]").val(res.userName);
                    $("#form_edit [name=\"email\"]").val(res.userEmail);
                    $("#form_edit [name=\"agent\"]").val(res.mitraCompanyID);
                    $("#id").val(res.id);
                }
            });
        });


        $(document).delegate("[data-target='#edit-grade']", "click", function() {
            let id = $(this).attr('data-id')
            $.ajax({
                type: "POST", //we are using GET method to get data from server side
                url: '<?= BASEURL ?>dashboard/getDataBy', // get the route value
                data: {
                    model: 'M_User',
                    table: 'tb_grade',
                    attr: 'id',
                    id: id,
                }, //set data
                success: function(response) { //once the request successfully process to the server side it will return result here
                    res = JSON.parse(response);

                    $("#form_edit [name=\"grade\"]").val(res.nameGrade);
                    $("#form_edit [name=\"diskon\"]").val(res.diskon);
                    $("#form_edit [name=\"diskon_inter\"]").val(res.diskon_internasional);
                    $("#id_grade").val(res.id);
                    $('.form-check-input_edit').each(function() {
                        if ($(this).attr('value') == res.akses) {
                            this.checked = true;
                        } else {
                            this.checked = false;
                        }
                    });

                }
            });
        });

        $('#username').on('change', (e) => {
            var id = $('#username').val();
            $.ajax({
                type: "POST", //we are using GET method to get data from server side
                url: '<?= BASEURL ?>dashboard/getDataBy', // get the route value
                data: {
                    model: 'M_User',
                    table: 'tb_user',
                    attr: 'userID',
                    id: id,
                }, //set data
                success: function(response) { //once the request successfully process to the server side it will return result here
                    res = JSON.parse(response);
                    if (res) {
                        $('#username').addClass('is-invalid')
                        $('#error-nof').css('display', 'block');
                        $('#error-nof').html('Username Not Availabel !!!');
                    } else {
                        $('#username').removeClass('is-invalid')
                        $('#error-nof').css('display', 'none');
                        $('#error-nof').html('');
                    }

                }
            });
        })

        $('#grade').change(function() {
            var grade = $(this).find(':selected').attr('data-id');
            if (grade == "agent") {
                $('#diskon_field').css('display', '');
                $('#mitra_field').css('display', '');
            } else {
                $('#diskon_field').css('display', 'none');
                $('#mitra_field').css('display', 'none');
            }
        });

        $('#grade2').change(function() {
            var grade = $(this).find(':selected').attr('data-id');

            if (grade == "agent") {
                $('#diskon_field2').css('display', '');
                $('#mitra_field2').css('display', '');
            } else {
                $('#diskon_field2').css('display', 'none');
                $('#mitra_field2').css('display', 'none');
            }
        });
    });
</script>
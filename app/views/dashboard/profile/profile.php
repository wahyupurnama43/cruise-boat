<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-8">
        <h2>Profile</h2>
        <ol class="breadcrumb">
            <li>
                <a href="<?= BASEURL ?>/dashboard">Home</a>
            </li>
            <li class="active">
                Profile
            </li>

        </ol>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    Edit Profile
                </div>
                <form action="<?= BASEURL ?>dashboard/editProfile" method="POST">
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" id="name" name="name" value="<?= $data['user']['userName'] ?>" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="userID">username</label>
                                    <input type="text" class="form-control" id="userID" name="userID" value="<?= $data['user']['userID'] ?>" disabled>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" id="password" name="password">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="status">status</label>
                                    <input type="text" class="form-control" id="status" name="status" value="<?= $data['user']['grade'] ?>" disabled>
                                </div>
                            </div>
                        </div>

                        <?php if ($data['user']['jenis_grade'] == 'agent') : ?>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="diskon">Diskon Domestic</label>
                                        <input type="text" class="form-control" id="diskon" name="diskon" value="<?= ($data['grade']['diskon'] <= 100) ? $data['grade']['diskon'] .  '%' : 'Rp  ' .  number_format($data['grade']['diskon']); ?>" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="diskon">Diskon Internasional</label>
                                        <input type="text" class="form-control" value="<?= ($data['grade']['diskon'] <= 100) ? $data['grade']['diskon'] .  '%' : 'Rp  ' .  number_format($data['grade']['diskon_internasional']); ?>" disabled>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="row">
                            <div class="col-lg-6">
                                <button type="submit" class="btn btn-primary">Save changes</button>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
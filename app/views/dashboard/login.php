<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cruise Login</title>
    <link href="<?= ASSETS ?>/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= ASSETS ?>/assets/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="<?= ASSETS ?>/assets/css/animate.css" rel="stylesheet">
    <link href="<?= ASSETS ?>/assets/css/style.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <style>
        .center {
            display: flex;
            justify-content: center;
            align-items: center;
            align-content: center;
        }
    </style>
</head>

<body class="white-bg center">
    <div class="middle-box text-center loginscreen animated fadeInDown ">
        <div class="row">
            <div class="flash-data" data-flashdata="<?= Flasher::flash(true); ?>"></div>
        </div>
        <div>
            <img src="<?= ASSETS ?>/assets/img/header.png" alt="" width="200px" style="margin-bottom: 20px;">
            <h3>Welcome to EL Rey Fast Cruise Dashboard</h3>
            <p>Login in. To see it in action.</p>
            <div class="alert " id="alert" role="alert" style="display: none;"></div>
            <form class="m-t" role="form" method="POST" action="<?= BASEURL ?>login/auth">
                <div class="form-group">
                    <input type="TEXT" class="form-control" name="username" placeholder="Username" required="">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="password" placeholder="Password" required="">
                </div>
                <button type="submit" class="btn btn-primary block full-width m-b">Login</button>
            </form>
        </div>
    </div>

    <!-- Mainly scripts -->
    <script src="<?= ASSETS ?>/assets/js/jquery-2.1.1.js"></script>
    <script src="<?= ASSETS ?>/assets/js/bootstrap.min.js"></script>
    <script>
        const flashData = $('.flash-data').data('flashdata');
        if (flashData) {
            if (flashData.tipe == 'error') {
                $('#alert').addClass('alert-danger')
                $('#alert').css('display', '')
                $('#alert').html('Please Enter Username and Password Correctly');
            } else if (flashData.tipe == 'success') {
                $('#alert').addClass('alert-success')
                $('#alert').css('display', '')
                $('#alert').html('Success Change Password');
            }
        };
    </script>
</body>

</html>
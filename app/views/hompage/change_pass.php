<!doctype html>
<html lang="en">

<head>
     <!-- Required meta tags -->
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1">

     <!-- Bootstrap CSS -->
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

     <title>Change Password</title>
</head>

<body class="container ">

     <div class="row mt-5 pt-5">
          <div class="col-lg-6 col-md-6 col-sm-12 offset-md-3">
               <div class="card card-outline-secondary">
                    <div class="card-header">
                         <h3 class="mb-0">Change Password</h3>
                    </div>
                    <div class="card-body">
                         <form class="form" action="" method="POST" role="form" autocomplete="off">
                              <div class="form-group mt-2">
                                   <label for="inputPasswordNew">New Password</label>
                                   <input type="password" class="form-control" id="inputPasswordNew" name="password" required="">
                              </div>
                              <div class="form-group mt-2">
                                   <label for="inputPasswordNewVerify">Confirm Password</label>
                                   <input type="password" class="form-control" id="inputPasswordNewVerify" name="confirm_password" required="">
                              </div>
                              <div class="form-group mt-3">
                                   <button type="submit" name="submit" class="btn btn-primary  float-right">Save</button>
                              </div>
                         </form>

                    </div>
               </div>
          </div>
     </div>


     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
     <script>
          const flashData = $('.flash-data').data('flashdata');
          if (flashData) {
               if (flashData.tipe == 'success') {
                    toastr.success(flashData.pesan)
               } else {
                    toastr.error(flashData.pesan)
               }
          };
     </script>
</body>

</html>
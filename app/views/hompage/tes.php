<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
     <meta name="viewport" content="width=device-width" />
     <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
     <title></title>
</head>

<body>
     <form action="<?= BASEURL ?>home/getSeatRegular" method="POST" id="form" enctype="multipart/form-data">
          <input type="file" name="fileSeat" id="input">
     </form>

     <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
     <script>
          $('#input').change(function() {
               $('#form').submit();
          })
     </script>
</body>

</html>
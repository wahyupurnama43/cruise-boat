<?php

class M_User
{
    private $db;
    private $mail;

    public function __construct()
    {
        $this->db = new Database();
        $this->mail = new PHPMailer\PHPMailer\PHPMailer();
    }

    public function getAllUser()
    {
        $sql = "SELECT * FROM tb_user ORDER BY dateCreate DESC";
        $this->db->query($sql);
        return $this->db->resultSet();
    }

    public function search_agent($name)
    {
        $sql = "SELECT * FROM tb_user WHERE userName LIKE '%$name%' AND mitraCompanyID != 0";
        $this->db->query($sql);
        return $this->db->resultSet();
    }

    public function getAllMitra()
    {
        $sql = "SELECT * FROM tb_mitra";
        $this->db->query($sql);
        return $this->db->resultSet();
    }

    public function getMitra($id)
    {
        $id    = htmlspecialchars(trim($id), ENT_QUOTES);
        $sql = "SELECT * FROM tb_mitra WHERE id = $id";
        $this->db->query($sql);
        return $this->db->single();
    }

    public function getUserBy($table, $attr, $id)
    {
        $table = htmlspecialchars(trim($table), ENT_QUOTES);
        $attr  = htmlspecialchars(trim($attr), ENT_QUOTES);
        $id    = htmlspecialchars(trim($id), ENT_QUOTES);
        $sql   = "SELECT * FROM $table  WHERE $attr = '$id'";
        $this->db->query($sql);
        return $this->db->single();
    }

    public function getAllGrade()
    {
        $sql   = "SELECT * FROM tb_grade ORDER BY created_at DESC";
        $this->db->query($sql);
        return $this->db->resultSet();
    }

    public function imgEmail()
    {
        $sql = "SELECT value FROM tb_setting WHERE name = 'HEADER_EMAIL'";
        $this->db->query($sql);
        return $this->db->single();
    }

    public function change_password($id, $data)
    {

        $id               = htmlspecialchars(trim($id), ENT_QUOTES);
        $check_data       = $this->getUserBy('tb_user', 'userID', $id);
        $confirm_password = htmlspecialchars(trim($data['confirm_password']), ENT_QUOTES);

        if ($check_data['changepass_status'] == '0') {
            $sql = "UPDATE tb_user SET password=:password, changepass_status=:changepass_status, lastUpdateBy=:lastUpdateBy  WHERE userID=:userID";
            $this->db->query($sql);
            $this->db->bind('userID', $id);
            $this->db->bind('password', password_hash(htmlspecialchars(trim($confirm_password), ENT_QUOTES), PASSWORD_BCRYPT, ['cost' => 10]));
            $this->db->bind('changepass_status', '1');
            $this->db->bind('lastUpdateBy', 'SISTEM');
            $this->db->execute();

            return $this->db->rowCount();
        } else {
            return 500;
        }
    }


    public function sendMail($data)
    {

        $data = $this->getUserBy('tb_user', 'id', $data);

        $imgEmail = $this->imgEmail();

        $messages = '<html xmlns="http://www.w3.org/1999/xhtml">
                            <head>
                                <meta name="viewport" content="width=device-width" />
                                <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                                <title>Payment Invoice</title>

                                <style>
                                    * {
                                        margin: 0;
                                        padding: 0;
                                        font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
                                        box-sizing: border-box;
                                        font-size: 14px;
                                    }

                                    img {
                                        max-width: 100%;
                                    }

                                    body {
                                        -webkit-font-smoothing: antialiased;
                                        -webkit-text-size-adjust: none;
                                        width: 100% !important;
                                        height: 100%;
                                        line-height: 1.6;
                                    }

                                    table td {
                                        vertical-align: top;
                                    }

                                    body {
                                        background-color: #f6f6f6;
                                    }

                                    .body-wrap {
                                        background-color: #f6f6f6;
                                        width: 100%;
                                    }
                                    .UP{
                                        text-transform: uppercase;
                                    }
                                    .container {
                                        display: block !important;
                                        max-width: 600px !important;
                                        margin: 0 auto !important;
                                        /* makes it centered */
                                        clear: both !important;
                                    }

                                    .content {
                                        max-width: 600px;
                                        margin: 0 auto;
                                        display: block;
                                        padding: 20px;
                                    }

                                    .main {
                                        background: #fff;
                                        border: 1px solid #e9e9e9;
                                        border-radius: 3px;
                                    }

                                    .content-wrap {
                                        padding: 20px;
                                    }

                                    .content-block {
                                        padding: 0 0 20px;
                                        text-align: center;
                                    }

                                    .header {
                                        width: 100%;
                                        margin-bottom: 20px;
                                    }

                                    .footer {
                                        width: 100%;
                                        clear: both;
                                        color: #999;
                                        padding: 20px;
                                    }

                                    .footer a {
                                        color: #999;
                                    }

                                    .footer p,
                                    .footer a,
                                    .footer unsubscribe,
                                    .footer td {
                                        font-size: 12px;
                                    }
                                    h1,
                                    h2,
                                    h3 {
                                        font-family: "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif;
                                        color: #000;
                                        margin: 40px 0 0;
                                        line-height: 1.2;
                                        font-weight: 400;
                                    }

                                    h1 {
                                        font-size: 32px;
                                        font-weight: 500;
                                    }

                                    h2 {
                                        font-size: 24px;
                                    }

                                    h3 {
                                        font-size: 18px;
                                    }

                                    h4 {
                                        font-size: 14px;
                                        font-weight: 600;
                                    }

                                    p,
                                    ul,
                                    ol {
                                        margin-bottom: 10px;
                                        font-weight: normal;
                                    }

                                    p li,
                                    ul li,
                                    ol li {
                                        margin-left: 5px;
                                        list-style-position: inside;
                                    }

                                    a {
                                        color: #1ab394;
                                        text-decoration: underline;
                                    }

                                    .btn-primary {
                                        text-decoration: none;
                                        color: #FFF;
                                        background-color: #1ab394;
                                        border: solid #1ab394;
                                        border-width: 5px 10px;
                                        line-height: 2;
                                        font-weight: bold;
                                        text-align: center;
                                        cursor: pointer;
                                        display: inline-block;
                                        border-radius: 5px;
                                        text-transform: capitalize;
                                    }

                                    .last {
                                        margin-bottom: 0;
                                    }

                                    .first {
                                        margin-top: 0;
                                    }

                                    .aligncenter {
                                        text-align: center;
                                    }

                                    .alignright {
                                        text-align: right;
                                    }

                                    .alignleft {
                                        text-align: left;
                                    }

                                    .clear {
                                        clear: both;
                                    }

                                    .alert {
                                        font-size: 16px;
                                        color: #fff;
                                        font-weight: 500;
                                        padding: 20px;
                                        text-align: center;
                                        border-radius: 3px 3px 0 0;
                                    }

                                    .alert a {
                                        color: #fff;
                                        text-decoration: none;
                                        font-weight: 500;
                                        font-size: 16px;
                                    }

                                    .alert.alert-warning {
                                        background: #f8ac59;
                                    }

                                    .alert.alert-bad {
                                        background: #ed5565;
                                    }

                                    .alert.alert-good {
                                        background: #1ab394;
                                    }

                                    .invoice {
                                        margin: 40px auto;
                                        text-align: left;
                                        width: 80%;
                                    }

                                    .invoice td {
                                        padding: 5px 0;
                                    }

                                    .invoice .invoice-items {
                                        width: 100%;
                                    }

                                    .invoice .invoice-items td {
                                        border-top: #eee 1px solid;
                                    }

                                    .invoice .invoice-items .total td {
                                        border-top: 2px solid #333;
                                        border-bottom: 2px solid #333;
                                        font-weight: 700;
                                    }

                                    @media only screen and (max-width: 640px) {
                                        h1,
                                        h2,
                                        h3,
                                        h4 {
                                            font-weight: 600 !important;
                                            margin: 20px 0 5px !important;
                                        }
                                        h1 {
                                            font-size: 22px !important;
                                        }
                                        h2 {
                                            font-size: 18px !important;
                                        }
                                        h3 {
                                            font-size: 16px !important;
                                        }
                                        .container {
                                            width: 100% !important;
                                        }
                                        .content,
                                        .content-wrap {
                                            padding: 10px !important;
                                        }
                                        .invoice {
                                            width: 100% !important;
                                        }
                                    }
                                </style>
                            </head>

                            <body>

                                <table class="body-wrap">
                                    <tr>
                                        <td></td>
                                        <td class="container" width="600">
                                            <div class="content">
                                                <table class="main" width="100%" cellpadding="0" cellspacing="0">
                                                    <tr>
                                                        <td class="content-wrap">
                                                            <table cellpadding="0" cellspacing="0">
                                                                <tr>
                                                                    <td>
                                                                        <img class="img-responsive" src="' . ASSETS . '/home/images/' . $imgEmail['value'] . '" />
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="content-block">
                                                                        <h3>Thank you for joining ' . COMPANY . '</h3>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="content-block">
                                                                        Name  : ' . $data['userName'] . ' <br>  
                                                                        Username :  ' . $data['userID'] . ' <br>
                                                                        Password : Please change password by clicking the link below  
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="content-block aligncenter">
                                                                    <form>
                                                                        <a href="' . BASEURL . 'change_password/' . $data['userID']  . '" class="btn-primary">Change Password</a>
                                                                    </form>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </td>
                                        <td></td>
                                    </tr>
                                </table>
                            </body>
                        </html>';

        $this->mail->isSMTP();
        $this->mail->SMTPDebug = 0;
        $this->mail->Host       = MAILHOST;
        $this->mail->SMTPAuth   = true;
        $this->mail->Username   = MAILUSERNAME;
        $this->mail->Password   = MAILPASSWORD;
        $this->mail->SMTPSecure = 'ssl';
        $this->mail->Port       = 465;
        $this->mail->setFrom(MAILFROM, COMPANY);
        $this->mail->AddAddress($data['userID'], $data['userName']);
        $this->mail->isHTML(true);
        $this->mail->Subject    = "Success Register Account ";
        $this->mail->Body       = $messages;
        return $this->mail->Send();
    }

    public function create_user($data)
    {

        $username = htmlspecialchars(trim($data['username']), ENT_QUOTES);
        $password = password_hash(htmlspecialchars(trim($data['password']), ENT_QUOTES), PASSWORD_BCRYPT, ['cost' => 10]);
        $grade    = htmlspecialchars(trim($data['grade']), ENT_QUOTES);
        $nama     = htmlspecialchars(trim($data['nama']), ENT_QUOTES);
        $agent    = (htmlspecialchars(trim($data['agent']), ENT_QUOTES) !== '') ? htmlspecialchars(trim($data['agent']), ENT_QUOTES) : 0;

        $listGrade = $this->getUserBy('tb_grade', 'id', $grade);

        if ($listGrade == false) {
            $jenis_agent = '';
            $diskon = 0;
        } else {
            $jenis_agent = 'agent';
            $diskon = $listGrade['diskon'];
            if ($agent !== '') {
                Flasher::setFlash('Agent Tidak Boleh Kosong', 'error');
                header('Location: ' . BASEURL . 'dashboard/user/');
            }
        }

        // masukkin diskon di tb_user, karena ada perubahan di alur sistem
        $sql = "INSERT INTO tb_user(userID, password, grade, jenis_grade,diskon ,userName, userEmail, mitraCompanyID,  createBy) VALUES (:userID, :password, :grade,:jenis_grade , :diskon, :userName, :userEmail, :mitraCompanyID,  :createBy)";

        $this->db->query($sql);
        $this->db->bind('userID', $username);
        $this->db->bind('password', $password);
        $this->db->bind('grade', $grade);
        $this->db->bind('jenis_grade', $jenis_agent);
        $this->db->bind('userName', $nama);
        $this->db->bind('userEmail', $username);
        $this->db->bind('diskon', $diskon);
        $this->db->bind('mitraCompanyID', $agent);
        $this->db->bind('createBy', $_SESSION['session_login_id']);
        $this->db->execute();

        $data_user = $this->getUserBy('tb_user', 'userID', $username);

        $count =  $this->sendMail($data_user['id']);

        if ($count) {
            return $this->db->rowCount();
        } else {
            return -1;
        }
    }


    public function edit_user($data)
    {

        $username = htmlspecialchars(trim($data['username']), ENT_QUOTES);
        $password = password_hash(htmlspecialchars(trim($data['password']), ENT_QUOTES), PASSWORD_BCRYPT, ['cost' => 10]);
        $grade    = htmlspecialchars(trim($data['grade']), ENT_QUOTES);
        $nama     = htmlspecialchars(trim($data['nama']), ENT_QUOTES);
        $agent    = (htmlspecialchars(trim($data['agent']), ENT_QUOTES) !== '') ? htmlspecialchars(trim($data['agent']), ENT_QUOTES) : 0;
        $id       = htmlspecialchars(trim($data['id']), ENT_QUOTES);

        $listGrade = $this->getUserBy('tb_grade', 'id', $grade);

        if ($listGrade == false) {
            $agent       = 0;
            $jenis_agent = '';
            $diskon      = 0;
        } else {
            $jenis_agent = 'agent';
            $diskon      = $listGrade['diskon'];

            if ($agent == 0) {
                Flasher::setFlash('Agent Tidak Boleh Kosong', 'error');
                header('Location: ' . BASEURL . 'dashboard/user/');
                die;
            }
        }

        if ($_POST['password'] !== "") {
            $password = password_hash(htmlspecialchars(trim($data['password']), ENT_QUOTES), PASSWORD_BCRYPT);
            $sql = "UPDATE tb_user SET userID=:userID, password=:password, grade=:grade,jenis_grade=:jenis_grade, userName=:userName, diskon=:diskon, userEmail=:userEmail, mitraCompanyID=:mitraCompanyID, lastUpdateBy=:lastUpdateBy  WHERE id='$id'";
            $this->db->query($sql);
            $this->db->bind('password', $password);
        } else {
            $sql = "UPDATE tb_user SET userID=:userID, grade=:grade, jenis_grade=:jenis_grade ,userName=:userName, diskon=:diskon, userEmail=:userEmail, mitraCompanyID=:mitraCompanyID,  lastUpdateBy=:lastUpdateBy  WHERE id='$id'";
            $this->db->query($sql);
        }

        $this->db->bind('userID', $username);
        $this->db->bind('grade', $grade);
        $this->db->bind('jenis_grade', $jenis_agent);
        $this->db->bind('userName', $nama);
        $this->db->bind('diskon', $diskon);
        $this->db->bind('userEmail', $username);
        $this->db->bind('mitraCompanyID', $agent);
        $this->db->bind('lastUpdateBy', $_SESSION['session_login_id']);
        $this->db->execute();

        return $this->db->rowCount();
    }

    public function create_grade($data)
    {
        try {

            // get grade
            $grade        = htmlspecialchars(trim($data['grade']), ENT_QUOTES);
            $diskon       = ($data['diskon'] == '') ? 0 : htmlspecialchars(trim($data['diskon']), ENT_QUOTES);
            $diskon_inter = ($data['diskon_inter'] == '') ? 0 : htmlspecialchars(trim($data['diskon_inter']), ENT_QUOTES);
            $gradeData    = $this->getUserBy('tb_grade', 'id', $grade);


            if (empty($gradeData)) {
                if ($data['akses'] !== null) {

                    foreach ($data['akses'] as $s) {
                        $akses[] = $s;
                    }
                    $stringAkses = implode(',', $akses);
                } else {
                    $stringAkses = '';
                }

                $sql = "INSERT INTO tb_grade(id, grade,nameGrade, diskon,diskon_internasional,akses) VALUES (:id, :grade, :nameGrade ,:diskon,:diskon_internasional,:akses)";

                $this->db->query($sql);
                $this->db->bind('id', str_replace(' ', '_', $grade));
                $this->db->bind('grade', 'agent');
                $this->db->bind('nameGrade', $grade);
                $this->db->bind('diskon', $diskon);
                $this->db->bind('diskon_internasional', $diskon_inter);
                $this->db->bind('akses', $stringAkses);
                $this->db->execute();
                return $this->db->rowCount();
            } else {
                Flasher::setFlash('Grade Sudah Terdaftar', 'error');
                header('Location: ' . BASEURL . 'dashboard/user/');
            }
        } catch (\Throwable $th) {
            return -500;
        }
    }

    public function edit_grade($data)
    {

        $id     = htmlspecialchars(trim($data['id']), ENT_QUOTES);
        $grade  = htmlspecialchars(trim($data['grade']), ENT_QUOTES);
        $diskon       = ($data['diskon'] == '') ? 0 : htmlspecialchars(trim($data['diskon']), ENT_QUOTES);
        $diskon_inter = ($data['diskon_inter'] == '') ? 0 : htmlspecialchars(trim($data['diskon_inter']), ENT_QUOTES);

        if (isset($data['akses'])) {
            foreach ($data['akses'] as $s) {
                $akses[] = $s;
            }
            $stringAkses = implode(',', $akses);
        } else {
            $stringAkses = '';
        }


        $sql = "UPDATE tb_grade SET nameGrade=:nameGrade, diskon=:diskon,diskon_internasional=:diskon_internasional, akses=:akses, id=:ids WHERE id=:id";

        $this->db->query($sql);
        $this->db->bind('id', $id);
        $this->db->bind('ids', str_replace(' ', '_', $grade));
        $this->db->bind('nameGrade', $grade);
        $this->db->bind('akses', $stringAkses);
        $this->db->bind('diskon', $diskon);
        $this->db->bind('diskon_internasional', $diskon_inter);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function editProfile($data, $id)
    {

        $nama       = htmlspecialchars(trim($data['name']), ENT_QUOTES);
        $email      = htmlspecialchars(trim($data['email']), ENT_QUOTES);

        if ($data['password'] !== "") {
            $password = password_hash(htmlspecialchars(trim($data['password']), ENT_QUOTES), PASSWORD_BCRYPT);
            $sql = "UPDATE tb_user SET userName=:userName, password=:password, userEmail=:userEmail, lastUpdateBy=:lastUpdateBy  WHERE id='$id'";
            $this->db->query($sql);
            $this->db->bind('password', $password);
        } else {
            $sql = "UPDATE tb_user SET userName=:userName, userEmail=:userEmail, lastUpdateBy=:lastUpdateBy  WHERE id='$id'";
            $this->db->query($sql);
        }

        $this->db->bind('userName', $nama);
        $this->db->bind('userEmail', $email);
        $this->db->bind('lastUpdateBy', $_SESSION['session_login_id']);
        $this->db->execute();

        $_SESSION['session_login'] = $nama;

        return $this->db->rowCount();
    }

    public function delete($id, $tb)
    {

        $about = $this->getUserBy($tb, 'id', $id);
        $sql = "DELETE FROM $tb WHERE id='$id'";
        $this->db->query($sql);
        $this->db->execute();
        return $this->db->rowCount();
    }
}

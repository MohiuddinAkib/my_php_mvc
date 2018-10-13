<?php


namespace App\libraries;


use PDO;


class Database {
  private $dbtype;
  private $dbhost;
  private $dbuser;
  private $dbpass;
  private $dbname;

  private $pdo;
  private $stmt;
  private $error;
  
  public function __construct() {
    // Set property values
    $this->dbtype = getenv('DB_TYPE');
    $this->dbhost = getenv('DB_HOST');
    $this->dbuser = getenv('DB_USER');
    $this->dbpass = getenv('DB_PASS');
    $this->dbname = getenv('DB_NAME');
    
    // Establish DB connection
    $this->connect();
  }
  
  /**
   * Establish connection with database.
   *
   * @return void
   */
  private function connect()
  {
    // Set DSN
    $dsn = "{$this->dbtype}:host={$this->dbhost};dbname={$this->dbname}";
    $options = [
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_EMULATE_PREPARES => false,
      PDO::ATTR_PERSISTENT => true
    ];
    
    // Create PDO instance
    try{
      $this->pdo = new PDO($dsn, $this->dbuser, $this->dbpass, $options);
    } catch(PDOException $e) {
      die('ERROR: ' . $e->getMessage());
    }
  }

  /**
   * Prepapre PDO statement.
   *
   * @param [string] $sql
   * @return void
   */
  public function prepare($sql)
  {
    $this->stmt = $this->pdo->prepare($sql);
  }

  /**
   * Bind PDO params.
   *
   * @param [string] $param
   * @param [string] / [null] $value
   * @param [string] / [null] $type
   * @return void
   */
  protected function bind($param, $value = null, $type = null)
  {
    if(is_null($type)) {
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
      }
    }
    $this->stmt->bindValue($param, $value, $type);
  }

  /**
   * Execute PDO statement.
   *
   * @return statement_execution
   */
  protected function execute()
  {
    return $this->stmt->execute();
  }

  /**
   * Get result set as an array of object.
   *
   * @return result_set
   */
  protected function result_set()
  {
    $this->execute();
    return $this->stmt->fetchAll();
  }

  /**
   * Get single record as object
   *
   * @return single_record
   */
  protected function single()
  {
    $this->execute();
    return $this->stmt->fetch();
  }

  /**
   * Count record(s).
   *
   * @return number
   */
  protected function count()
  {
    return $this->stmt->rowCount();
  }

  public function __destruct()
  {
    unset($this->pdo);
    unset($this->stmt);
  }
}
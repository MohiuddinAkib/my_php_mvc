<?php

namespace App\models;


use App\libraries\Model;
use App\libraries\Database;

class User extends Database
{
  private $table = 'users';

  /**
   * Fetch all users.
   *
   * @return users
   */
  public function all()
  {
    $sql = "SELECT * FROM {$this->table} ORDER BY created_at DESC";
    $this->prepare($sql);
    $this->execute();
    return $this->result_set();
  }
}
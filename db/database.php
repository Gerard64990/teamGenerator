<?php

  Class DataBase
  {
    public function __construct()
    {
      $this->pdo = new PDO('mysql:host=localhost;dbname=983251;charset=utf8', 'root', 'drogba64-');
    }
  }
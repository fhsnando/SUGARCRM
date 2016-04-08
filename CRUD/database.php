<?php
  class Database
  {

      private static $dbName = 'itfuture' ;
      private static $dbHost = 'localhost' ;
      private static $dbUsername = 'root';
      private static $dbUserPassword = '';

      private static $cont  = null;

      public function __construct() {
          exit('Init function is not allowed');
      }

      public static function connect()
      {
          // Uma conex�o atrav�s da aplica��o inteira
          if ( null == self::$cont )
          {
              try
              {
                  self::$cont = new PDO( "mysql:host=".self::$dbHost.";"."dbname=".self::$dbName, self::$dbUsername, self::$dbUserPassword);
              }
              catch(PDOException $e)
              {
                  die($e->getMessage());
              }
          }
          return self::$cont;
      }

      public static function disconnect()
      {
          self::$cont = null;
      }
  }
?>
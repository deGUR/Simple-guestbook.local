<?php

namespace app\database;

use PDO;
use Exception;
use PDOException;

class DatabaseConnection
{
  /**
   * @var PDO
   */
  public $connection;

  /**
   * Database configuration.
   */
  public $config;

  /**
   * @var DatabaseConnection
   */
  private static $instance;


  private function __construct()
  {
    $this->initConfig();
    $this->databaseConnection();
  }

  /**
   * @return DatabaseConnection
   */
  public static function getInstance()
  {
    if (self::$instance == null) {
      self::$instance = new self();
    }

    return self::$instance;
  }


  /**
   * Connecting to the DBMS host and creating a database.
   *
   * @throws Exception
   */
  private function databaseConnection()
  {
    try {
      $this->connection = new PDO(
        "mysql:host={$this->config['HOST']};charset={$this->config['CHARSET']};",
        $this->config['USER'],
        $this->config['PASSWORD']
      );

      $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

      $this->connection->exec(
        "CREATE DATABASE IF NOT EXISTS `{$this->config['DATABASE']}`;
                GRANT ALL ON `{$this->config['DATABASE']}`.* TO '{$this->config['USER']}'@'localhost';
                FLUSH PRIVILEGES;"
      );

      $this->createTableUsers();
      $this->createTableTopic();
      $this->createTableComments();

    } catch (PDOException $exception) {
      throw new Exception($exception->getMessage());
    }
  }

  /**
   * Creating table users.
   */
  private function createTableUsers()
  {
    $this->connection->exec(
      "CREATE TABLE IF NOT EXISTS `{$this->config['DATABASE']}`.`users` (
        `user_id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        `first_name` VARCHAR(255) NOT NULL,
        `last_name` VARCHAR(255) NOT NULL,
        PRIMARY KEY (`user_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET={$this->config['CHARSET']};
        ");
  }

  /**
   * Creating table topic.
   */
  private function createTableTopic()
  {
    $statement = $this->connection->query(
      "SELECT COUNT(*)
			  FROM information_schema.tables 
			  WHERE table_schema = '{$this->config['DATABASE']}' 
			  AND table_name = 'topics';
			  ");

    if (empty($statement->fetch(PDO::FETCH_NUM)[0])) {
      $this->connection->exec(
        "CREATE TABLE IF NOT EXISTS `{$this->config['DATABASE']}`.`topics` (
  		  `topic_id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        `topic_title` VARCHAR(255) NOT NULL,
        PRIMARY KEY (`topic_id`)
			  ) ENGINE=InnoDB DEFAULT CHARSET={$this->config['CHARSET']};
			  ");

      $this->connection->exec(
        "INSERT INTO `{$this->config['DATABASE']}`.`topics` (`topic_title`) 
			  VALUES ('Thanks'),('Service improvement proposal'),('Complaint')
			");
    }
  }

  /**
   * Creating table comments.
   */
  private function createTableComments()
  {
    $this->connection->exec(
      "CREATE TABLE IF NOT EXISTS `{$this->config['DATABASE']}`.`comments` (
        `comment_id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        `user_id` BIGINT(20) UNSIGNED NOT NULL,
        `topic_id` BIGINT(20) UNSIGNED NOT NULL,
        `comment` LONGTEXT NOT NULL,
        `like` INT(11) DEFAULT 0,
        `image` LONGBLOB,
        `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`comment_id`),
        KEY `fk_user_id_idx` (`user_id`),
        KEY `fk_topic_id_idx` (`topic_id`),
        CONSTRAINT `fk_topic_id` FOREIGN KEY (`topic_id`) REFERENCES `topics` (`topic_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
        CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
	      ) ENGINE=InnoDB DEFAULT CHARSET={$this->config['CHARSET']};
	      ");
  }


  /**
   * Config initialization.
   */
  private function initConfig()
  {
    $this->config = require_once 'config/config.php';
  }
}
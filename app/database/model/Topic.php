<?php

namespace app\database\model;

use PDO;
use Exception;
use PDOException;
use app\database\DatabaseConnection;

class Topic
{
  /**
   * @var DatabaseConnection
   */
  protected $database;

  public function __construct()
  {
    $this->database = DatabaseConnection::getInstance();
  }

  /**
   * @return mixed
   * @throws Exception
   */
  public function getTopicTitle()
  {
    try {
      $statement = $this->database->connection->prepare(
        "SELECT `topic_id`, `topic_title`
				FROM `{$this->database->config['DATABASE']}`.`topics`
			  ");

      $statement->execute();

      return $statement->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $exception) {
      throw new Exception($exception->getMessage());
    }
  }
}
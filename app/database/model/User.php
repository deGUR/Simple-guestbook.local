<?php

namespace app\database\model;

use PDO;
use Exception;
use PDOException;
use app\database\DatabaseConnection;

class User
{
  /**
   * @var DatabaseConnection
   */
  protected $database;

  /**
   * @var int - number of records on page.
   */
  protected $perPage = 2;

  protected $pagesCount;

  protected $currentPage;

  public function __construct()
  {
    $this->database = DatabaseConnection::getInstance();
  }

  /**
   * Adding data to a table users and comments.
   *
   * @param string $firstName
   * @param string $lastName
   *
   * @return int - Last insert ID.
   * @throws Exception
   */
  public function insert(string $firstName, string $lastName): int
  {
    try {
      $statement = $this->database->connection->prepare(
        "INSERT INTO `{$this->database->config['DATABASE']}`.`users`(
				`first_name`, `last_name`) VALUES (?, ?);
				");

      $statement->execute([$firstName, $lastName]);

      $statement = $this->database->connection->query(
        "SELECT LAST_INSERT_ID()");

      $statement->execute();

      return intval($statement->fetch(PDO::FETCH_NUM)[0]);

    } catch (PDOException $exception) {
      throw new Exception($exception->getMessage());
    }
  }

  /**
   * Fetching data from multiple tables users, comments, topics.
   *
   * @return array
   * @throws Exception
   */
  public function select(): array
  {
    if (isset($_GET['page'])) {
      $this->currentPage = $_GET['page'];
    } else {
      $this->currentPage = 1;
    }
    /** @var  $start - Comment number from which we start the selection. */
    $start = ($this->currentPage - 1) * $this->perPage;

    try {
      $statement = $this->database->connection->prepare(
        "SELECT `users`.`first_name`, `users`.`last_name`, 
			  `comments`.`comment_id`, `comments`.`comment`, `comments`.`created_at`, 
			  `comments`.`like`, `comments`.`image`, `topics`.`topic_title`
			  FROM `{$this->database->config['DATABASE']}`.`users` 
			  INNER JOIN `{$this->database->config['DATABASE']}`.`comments` ON `users`.`user_id` = `comments`.`user_id`
			  INNER JOIN `{$this->database->config['DATABASE']}`.`topics` ON `comments`.`topic_id` = `topics`.`topic_id`
			  ORDER BY `users`.`user_id` DESC 
			  LIMIT $start, $this->perPage;
			  ");

      $statement->execute();

      $this->getCountComments();

      return $statement->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $exception) {
      throw new Exception($exception->getMessage());
    }
  }

  /**
   * Helper for choosing the number of posts from the database.
   *
   * @return array
   * @throws Exception
   */
  private function getCountComments()
  {
    try {
      $statement = $this->database->connection->prepare(
        "SELECT COUNT(user_id) as count FROM `{$this->database->config['DATABASE']}`.`users`");

      $statement->execute();

      $count = $statement->fetchAll(PDO::FETCH_ASSOC);

      /** @var $pagesCount - The number of records from the database is divided by the number displayed on the page. */
      $this->pagesCount = ceil($count[0]['count']/$this->perPage);

      return $count;

    } catch (PDOException $exception) {
      throw new Exception($exception->getMessage());
    }
  }

  /**
   * @return mixed
   */
  public function getPagesCount()
  {
    return $this->pagesCount;
  }

  /**
   * @return mixed
   */
  public function getCurrentPage()
  {
    return $this->currentPage;
  }
}
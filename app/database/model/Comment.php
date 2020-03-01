<?php

namespace app\database\model;

use Exception;
use PDOException;
use app\database\DatabaseConnection;

class Comment
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
   * @param array $comment {
   *
   * @type int $user_id - User ID that created the comment.
   * @type int $topic_id - Topic ID that selected user.
   * @type string $comment - Comment on the topic.
   *}
   *
   * @throws Exception
   */
  public function insert(array $comment)
  {
    try {
      $statement = $this->database->connection->prepare(
        "INSERT INTO `{$this->database->config['DATABASE']}`.`comments`(
			      `user_id`, `topic_id`, `comment`, `image`) VALUES (?, ?, ?, ?);
			      ");

      $statement->execute(
        [$comment['user_id'], $comment['topic_id'], $comment['comment'], $comment['image']]
      );

    } catch (PDOException $exception) {
      throw new Exception($exception->getMessage());
    }
  }

  /**
   * @param int $commentId
   *
   * @throws Exception
   */
  public function update(int $commentId)
  {
    try {
      $statement = $this->database->connection->prepare(
        "UPDATE `{$this->database->config['DATABASE']}`.`comments` 
            SET `like` = `like` + 1 
            WHERE comment_id = :comment_id;
            ");

      $statement->execute(['comment_id' => $commentId]);

    } catch (PDOException $exception) {
      throw new Exception($exception->getMessage());
    }
  }
}
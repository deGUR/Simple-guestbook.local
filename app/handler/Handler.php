<?php

namespace app\handler;

use app\database\model\User;
use app\database\model\Comment;

class Handler
{
  /**
   * @var User
   */
  protected $user;

  /**
   * @var Comment
   */
  protected $comment;

  public function __construct()
  {
    $this->user    = new User();
    $this->comment = new Comment();
    $this->ajaxRequestHandler();
    $this->ajaxLikeIncrement();
  }

  /**
   * Processing data from the form.
   */
  protected function ajaxRequestHandler()
  {
    if ( ! isset($_POST['action']) || $_POST['action'] !== "create-comment") {
      return;
    }

    $firstName = $this->clean($_POST['first_name']);
    $lastName  = $this->clean($_POST['last_name']);
    $comment   = $_POST['comment'];

    if (isset($firstName) && ! empty($firstName) && isset($lastName) && ! empty($lastName)) {

      if (isset($_FILES['file']['name']) && ! empty($_FILES['file']['tmp_name'])) {

        /** @var $userId - last user ID that created the comment. */
        $userId = $this->user->insert($firstName, $lastName);

        /** @var  $imageFileType - file extension with dot. */
        $imageFileType = substr($_FILES["file"]["name"], -4);

        /** @var $extensions - array of valid extensions to load. */
        $extensions = array(".jpg", ".png");

        if ( ! in_array($imageFileType, $extensions)) {
          echo "Invalid file type!";
          exit;
        }

        $imageBase64 = base64_encode(file_get_contents($_FILES['file']['tmp_name']));

        $image = 'data:' . $_FILES['file']['type'] . ';base64,' . $imageBase64;

        $commentArguments = [
          'user_id'  => $userId,
          'topic_id' => $_POST['topic'],
          'comment'  => $comment,
          'image'    => $image
        ];

        $this->comment->insert($commentArguments);
      }
    }
  }

  /**
   * Increases Like counter for comments.
   */
  protected function ajaxLikeIncrement()
  {
    if ( ! isset($_POST['action']) || $_POST['action'] !== "increment-like") {
      return;
    }

    $commentId = intval($_POST['commentId']);

    $this->comment->update($commentId);
  }

  /**
   * Clears data from HTML characters.
   *
   * @param string $data
   *
   * @return string
   */
  private function clean(string $data)
  {
    $data = trim($data);
    $data = strip_tags($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);

    return $data;
  }

}
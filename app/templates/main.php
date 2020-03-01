<?php

use app\database\model\User;

include "form.php";

$user = new User();

?>

<?php foreach ($user->select() as $data) { ?>
  <div class="skl-section__comment">
    <div class="row">
      <div class="skl-comments">
        <div class="skl-comment__box">
					<span class="skl-commenter__pic">
						<img src="<?php echo $data['image']; ?>" class="skl-img__fluid">
					</span>
          <span class="skl-commenter__name">
            <span><?php echo $data['first_name'] . " " . $data['last_name']; ?></span>
            <span class="skl-comment__time"><?php echo $data['created_at']; ?></span>
					</span>
          <p class="skl-comment__txt more"><?php echo $data['comment']; ?></p>
          <span class="skl-topic__title"><?php echo "<strong>Theme: </strong>" . $data['topic_title']; ?></span>
          <div class="comment-meta">
              <span class="skl-comment__like" data-id="<?php echo $data['comment_id']; ?>">
                <i class="fa fa-thumbs-o-up" aria-hidden="true"></i>
                <span><?php echo $data['like']; ?></span>
              </span>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php } ?>

<?php include "pagination.php"; ?>

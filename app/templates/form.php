<?php

use app\database\model\Topic;

$topicTitle = new Topic();

?>
<div class="skl-section__form">
  <form action="/index.php" method="post" enctype="multipart/form-data" id="sklForm">
    <div class="row">
      <div class="skl_title">
        <label for="skl_firstName">First Name</label>
      </div>
      <div class="skl_text">
        <input type="text" id="skl_firstName" name="first_name" placeholder="Your name.." required>
      </div>
    </div>
    <div class="row">
      <div class="skl_title">
        <label for="skl_lastName">Last Name</label>
      </div>
      <div class="skl_text">
        <input type="text" id="skl_lastName" name="last_name" placeholder="Your last name.." required>
      </div>
    </div>
    <div class="row">
      <div class="skl_title">
        <label for="skl-topic">Theme</label>
      </div>
      <div class="skl_text">
        <select id="skl-topic" name="topic">
          <?php foreach ($topicTitle->getTopicTitle() as $topic) { ?>
          <option value="<?php echo $topic['topic_id']; ?>"><?php echo $topic['topic_title']; ?></option>
          <?php } ?>
        </select>
      </div>
    </div>
    <div class="row">
      <div class="skl_title">
        <label for="skl-comment">Comment</label>
      </div>
      <div class="skl_text">
        <textarea id="skl-comment" name="comment" placeholder="Write something.." style="height:200px"></textarea>
      </div>
    </div>
    <div class="row">
      <div class="skl_title">
        <label for="image">Image</label>
      </div>
      <div class="skl_text">
        <input type="file" id="skl_file" name="file">
      </div>
    </div>
    <div class="row" id="skl_button">
      <input type="submit" value="Submit">
    </div>
  </form>
</div>
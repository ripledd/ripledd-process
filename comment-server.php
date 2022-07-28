<?php include "dbconn.php" ?>

<?php
session_start();

if (($_POST['post_id']!="") && ($_POST['comment_data']!="")) {

$secure_id = $_SESSION['secure_id'];
  $sql = "SELECT * FROM users WHERE secure_id = '$secure_id'";
    $rs = mysqli_query($dbconn, $sql);
      $fetchRow = mysqli_fetch_assoc($rs);
        $user = $fetchRow['uname'];
          $user_url = $fetchRow['user_url'];

  $post_id = $_POST['post_id'];
  $comment_content = mysqli_real_escape_string($dbconn, $_POST['comment_data']);
  $comment_content = str_replace("*and*","&",$comment_content);
  $comment_content_notifi = preg_replace("/[']/","´",$comment_content);
  $get_date = date("Y-m-d h:i:sa");
  if ($user == '') {
    exit;
  }else{
    $query = "INSERT INTO comments_data (user, post_id, content, comment_time) VALUES('$secure_id', '$post_id', '$comment_content', '$get_date')";
       mysqli_query($dbconn, $query);
       // Get number of comments
        $sql = "SELECT comments FROM post_data WHERE id='$post_id'";
          $results = mysqli_query($dbconn, $sql);
            $current_comments = mysqli_fetch_assoc($results)['comments'];
            // Insert number + 1
              $sum = array($current_comments,'1');
                $plus_comment = array_sum($sum);
                  // update post_data with plus comment
                    $sql = "UPDATE post_data SET comments='$plus_comment' WHERE id='$post_id'";
                      $results = mysqli_query($dbconn, $sql);

                       // Notify user about new comment
                        $sql = "SELECT user_id FROM post_data WHERE id='$post_id'";
                          $results = mysqli_query($dbconn, $sql);
                            $notify_to = mysqli_fetch_assoc($results)['user_id'];
                              $head_txt = "$user commented your content!";
                                $content_txt = "$user commented: $comment_content_notifi ";
                                  $link = "content/$post_id";
                                    $get_date = date("Y-m-d h:i:sa");
                                      $query = "INSERT INTO notifications_data (user_s_id, head_txt, content_txt, notifi_date, link, from_id, type) VALUES('$notify_to', '$head_txt', '$content_txt', '$get_date', '$link', '$secure_id', 'comment')";
                                        mysqli_query($dbconn, $query);
  }
}
?>

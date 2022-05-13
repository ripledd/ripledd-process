<?php
session_start();

if (($_POST['post_id']!="")) {

$secure_id = $_SESSION['secure_id'];
  $sql = "SELECT * FROM users WHERE secure_id = '$secure_id'";
    $rs = mysqli_query($dbconn, $sql);
      $fetchRow = mysqli_fetch_assoc($rs);
        $user = $fetchRow['uname'];
          $user_url = $fetchRow['user_url'];
  $is_id = $_POST['post_id'];
  if ($user == '') {
    exit;
  }
  // get data from post, get how many likes are there
  $sql = "SELECT likes FROM post_data WHERE id='$is_id'";
    $results = mysqli_query($dbconn, $sql);
      $current_likes = mysqli_fetch_assoc($results)['likes'];
        // get data from likes history
          $sql = "SELECT * FROM likes_data WHERE post_id='$is_id' AND user='$secure_id'";
            $results = mysqli_query($dbconn, $sql);
              $who_liked = mysqli_fetch_assoc($results)['user'];

  // if results = 0 then allow to put one like
  if ($who_liked != $secure_id) {
    $sum = array($current_likes,'1');
      $plus_like = array_sum($sum);
        // update post_data with plus like
          $sql = "UPDATE post_data SET likes='$plus_like' WHERE id='$is_id'";
            $results = mysqli_query($dbconn, $sql);
              $query = "INSERT INTO likes_data (post_id, user) VALUES('$is_id', '$secure_id')";
  	             mysqli_query($dbconn, $query);

                  // Notify user about new like
                    $sql = "SELECT user_id FROM post_data WHERE id='$is_id'";
                      $n_results = mysqli_query($dbconn, $sql);
                        $notify_to = mysqli_fetch_assoc($n_results)['user_id'];
                          $head_txt = "$user litted your content!";
                            $content_txt = "You,ve got a new lit from <a href=,user/$user_url,> Click here to view $user,s channel</a>!";
                              $link = "content/$is_id";
                                $get_date = date("Y-m-d h:i:sa");
                                  $query = "INSERT INTO notifications_data (user_s_id, head_txt, content_txt, notifi_date, link, from_id) VALUES('$notify_to', '$head_txt', '$content_txt', '$get_date', '$link', '$secure_id')";
                                    mysqli_query($dbconn, $query);


  // if results = 1 then allow to minus 1 like
  }else {
    $minus_like = $current_likes - '1';
      $sql = "DELETE FROM likes_data WHERE user='$secure_id' AND post_id='$is_id'";
        $results = mysqli_query($dbconn, $sql);
          // update post_data with minus like
            $sql = "UPDATE post_data SET likes='$minus_like' WHERE id='$is_id'";
              $results = mysqli_query($dbconn, $sql);

    }
}
?>

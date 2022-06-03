<?php include "../database_connection.php" ?>
<?php
session_start();

if (($_POST['del_post_id']!="")) {

  $secure_id = $_SESSION['secure_id'];
  $sql = "SELECT * FROM users WHERE secure_id = '$secure_id'";
    $rs = mysqli_query($dbconn, $sql);
      $fetchRow = mysqli_fetch_assoc($rs);
        $post_id = $_POST['del_post_id'];
          if ($secure_id == '') {
            exit;
          }
  // get data from post
  $sql = "SELECT * FROM post_data WHERE id='$post_id'";
    $post_data_rs = mysqli_query($dbconn, $sql);
      $post_data_row = mysqli_fetch_assoc($post_data_rs);
        $post_author = $post_data_row['user_id'];
         $file = $post_data_row['file'];
          $poster = $post_data_row['poster'];

        if ($post_author != "$secure_id") {
            exit;
        }else {
         $sql = "DELETE FROM post_data WHERE id='$post_id'";
           $results = mysqli_query($dbconn, $sql);
            $sql = "DELETE FROM comments_data WHERE post_id='$post_id'";
             $results = mysqli_query($dbconn, $sql);
              if ($file != '') {
                unlink("C:/path/to/file/$file");
                if ($poster != "") {
                  unlink("C:/path/to/file/$poster");
                }
              }
        }

}
?>

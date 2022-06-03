<?php include "../database_connection.php" ?>
<?php
session_start();

if (($_POST['del_post_id']!="")) {

  $secure_id = $_SESSION['---'];
  $sql = "SELECT * FROM --- WHERE --- = '$secure_id'";
    $rs = mysqli_query($dbconn, $sql);
      $fetchRow = mysqli_fetch_assoc($rs);
        $post_id = $_POST['del_post_id'];
          if ($secure_id == '') {
            exit;
          }
  // get data from post
  $sql = "SELECT * FROM --- WHERE ---='$post_id'";
    $post_data_rs = mysqli_query($dbconn, $sql);
      $post_data_row = mysqli_fetch_assoc($post_data_rs);
        $post_author = $post_data_row['---'];
         $file = $post_data_row['---'];
          $poster = $post_data_row['---'];

        if ($post_author != "$secure_id") {
            exit;
        }else {
         $sql = "DELETE FROM --- WHERE --- ='$post_id'";
           $results = mysqli_query($dbconn, $sql);
            $sql = "DELETE FROM --- WHERE --- ='$post_id'";
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

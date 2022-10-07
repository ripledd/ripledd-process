<?php
session_start();
if (isset($_POST['submit_post'])) {
  
  // ownership proof address 
  $secure_id = $_SESSION['---'];
  // ---------------------------------
  
    $sql = "SELECT * FROM --- WHERE --- = '$secure_id'";
      $rs = mysqli_query($dbconn, $sql);
        $fetchRow = mysqli_fetch_assoc($rs);
          $user = $fetchRow['---'];
            $status = $fetchRow['---'];
              $post_content_orig = mysqli_real_escape_string($dbconn, $_POST['---']);
                $post_type = mysqli_real_escape_string($dbconn, $_POST['---']);
                  $post_content_x1 = preg_replace("/[']/","‘",$post_content_orig);
                    $post_content = preg_replace("/[<]/","&lt",$post_content_x1);

                    if ($status == "unver") {
                      $mediasize = "62914560";
                      $errto = "verify";
                    }else {
                      $mediasize = "1000719015";
                      $errto = "errsize";
                    }

  $prev_file = basename($_FILES["prev_file"]["name"]);

  $binary = bin2hex(random_bytes(15));
  $target_dir = "$binary";
  $target_files = $target_dir . basename($_FILES["post_file"]["name"]);
  $uploadOk = 1;
  $target_file_clr = preg_replace("/[^.a-zA-Z0-9]+/", "",$target_files);
  $target_file = "posts/$target_file_clr";
  $FileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
  if ($FileType == "") {
    $target_file = '';
  }

  $c = "$post_content$FileType";
  if ($c == "") {
    header("location: create?empty ");
    $uploadOk = 0;
    exit;
  }


  // Make smaller image file copy for previews
  if($FileType == "jpg" || $FileType == "png" || $FileType == "gif" || $FileType == "ico"  || $FileType == "jpeg"){

      // Get image rotation
      if (getimagesize($_FILES["post_file"]["tmp_name"])['mime'] === 'image/jpeg') {
        $get_orient = exif_read_data($_FILES["post_file"]["tmp_name"]);
        if (!empty($get_orient['Orientation'])) {
          $orientation = $get_orient['Orientation'];
        }
      }

       // Generate random hash for file
       $prev_binary = bin2hex(random_bytes(15));
       // Continue...
       function compress_image($source_url, $destination_url, $quality) {
         $info = getimagesize($source_url);
           if ($info['mime'] == 'image/jpeg') $image = imagecreatefromjpeg($source_url);
             elseif ($info['mime'] == 'image/gif') $image = imagecreatefromgif($source_url);
             elseif ($info['mime'] == 'image/png') $image = imagecreatefrompng($source_url);
             elseif ($info['mime'] == 'image/jpg') $image = imagecreatefromjpeg($source_url);
               //save it
               imagejpeg($image, $destination_url, $quality);
               //return destination file url
               return $destination_url;
       }
       $small_img= $_FILES["post_file"]["tmp_name"];
         $source_img = $small_img;
           $gen_name= "$prev_binary".time();
             $name_number= rand(1000 , 10000);
               $pic_name= $gen_name.$name_number;
                 $final_name = $pic_name.".jpeg";
                   $dest_img = 'posters/'.$final_name;
                     compress_image($source_img, $dest_img, 50);

                     // Rotate image to it's original position (if needed)
                     $output = $dest_img;
                     if ($orientation > 1) {
                       $image = imagecreatefromjpeg($output);
                       if (in_array($orientation, [3, 4])) {
                         $image = imagerotate($image, 180, 0);
                       }
                       if (in_array($orientation, [5, 6])) {
                         $image = imagerotate($image, -90, 0);
                       }
                       if (in_array($orientation, [7, 8])) {
                         $image = imagerotate($image, 90, 0);
                       }
                       if (in_array($orientation, [2, 5, 7, 4])) {
                         imageflip($image, IMG_FLIP_HORIZONTAL);
                       }
                       imagejpeg($image, $output);
                     }
  }else {
    if ($prev_file != "") {
      // Generate random hash for file
      $prev_binary = bin2hex(random_bytes(15));
      // Continue...
      function compress_image($source_url, $destination_url, $quality) {
        $info = getimagesize($source_url);
          if ($info['mime'] == 'image/jpeg') $image = imagecreatefromjpeg($source_url);
            elseif ($info['mime'] == 'image/gif') $image = imagecreatefromgif($source_url);
            elseif ($info['mime'] == 'image/png') $image = imagecreatefrompng($source_url);
            elseif ($info['mime'] == 'image/jpg') $image = imagecreatefromjpeg($source_url);
              //save it
              imagejpeg($image, $destination_url, $quality);
              //return destination file url
              return $destination_url;
      }
      $small_img= $_FILES["prev_file"]["tmp_name"];
        $source_img = $small_img;
          $gen_name= "$prev_binary".time();
            $name_number= rand(1000 , 10000);
              $pic_name= $gen_name.$name_number;
                $final_name = $pic_name.".jpeg";
                  $dest_img = 'posters/'.$final_name;
                    compress_image($source_img, $dest_img, 50);
    }
  }

  // If file is .flac, then change it to mp3
  if ($FileType == "flac"){$target_file = $target_file.".mp3";}

  if ($target_file != " ") {
    // Check file size
    if ($_FILES["post_file"]["size"] > $mediasize) {
      header("location: create?$errto ");
      $uploadOk = 0;
      exit;
    }
    // Allow file formats
    if($FileType != "jpg" && $FileType != "png" && $FileType != "gif" && $FileType != "ico"  && $FileType != "jpeg"
       && $FileType != "mp4" && $FileType != "avi" && $FileType != "mts" && $FileType != "mov" && $FileType != "ogg"
       && $FileType != "wav" && $FileType != "mp3" && $FileType != "flac" && $FileType != "m4a" && $FileType != "aac" && $FileType != ""  ){
      header("location: create?errformat ");
      $uploadOk = 0;
      exit;
    }


    // Check for error
    if ($uploadOk == 0) {
      echo "Sorry, your file was not uploaded.";
    // if no errors
    } else {
      move_uploaded_file($_FILES["post_file"]["tmp_name"], $target_file);

    }
  }

  if ($target_file == "$target_dir") {
    $target_file = "null";
  }
  $get_date = gmdate("d/m/y h:i");
  $get_date_two = date("Y-m-d h:i:sa");
  $id_gener1 = bin2hex(random_bytes(4));
  $id_gener2 = bin2hex(random_bytes(1));
  $id_gener = "$id_gener1$id_gener2";
  $query = "INSERT INTO --- (---, ---, ---, ---, ---, ---, ---, ---, ---)
    VALUES('$id_gener', '$post_content', '$get_date', '$get_date_two', '$target_file', '$dest_img', '$user', '$post_type', '$secure_id' )";
    mysqli_query($dbconn, $query);
  
  
    // For suggestion algorithm
      $sql = "SELECT * FROM suggest_content WHERE for_user = '$secure_id'";
        $rs = mysqli_query($dbconn, $sql);
          $get_data = mysqli_fetch_assoc($rs);
            $is_data = $get_data['for_user'];
              $from_data = $get_data['from_users'];
                $in_suggest_from = " +.$secure_id .";
                  $in_suggest_from = preg_replace("/[ ]/","",$in_suggest_from);

                    if ($is_data == '') {
                      $query = "INSERT INTO suggest_content (from_users, for_user) VALUES('$in_suggest_from', '$secure_id')";
                        mysqli_query($dbconn, $query);
                          }else {
                              if(strpos($from_data, $secure_id) == false){
                                $update_suggest_from = "$from_data $in_suggest_from";
                                  $sql = "UPDATE suggest_content SET from_users='$update_suggest_from' WHERE for_user='$is_data'";
                                    $results = mysqli_query($dbconn, $sql);
                                  }
                                }

                                header("location: ../");

}


?>

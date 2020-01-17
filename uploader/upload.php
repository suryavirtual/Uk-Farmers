<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Example: File Upload</title>
</head>
<body>
<?php
// Required: anonymous function reference number as explained above.
$funcNum = $_GET['CKEditorFuncNum'] ;
// Optional: instance name (might be used to load a specific configuration file or anything else).
$CKEditor = $_GET['CKEditor'] ;
// Optional: might be used to provide localized messages.
$langCode = $_GET['langCode'] ;
// Optional: compare it with the value of `ckCsrfToken` sent in a cookie to protect your server side uploader against CSRF.
// Available since CKEditor 4.5.6.
$token = $_POST['ckCsrfToken'] ;
$dir = $_REQUEST['dir'];
// Check the $_FILES array and save the file. Assign the correct path to a variable ($url).
//$url = '/path/to/uploaded/';
// Usually you will only assign something here if the file could not be uploaded.
//$message = 'The uploaded file has been renamed';
//print_r($_FILES);die;

define ('SITE_ROOT', realpath(dirname(__DIR__)));
//$uploads_dir = str_replace('\\','/',SITE_ROOT)."/uf_data/$dir/images/";
$uploads_dir = str_replace('\\','/',SITE_ROOT)."/uf_data/$dir/file_links/";

if(!is_dir($uploads_dir))
                 {
                 mkdir($uploads_dir, 0777, true);

                 }



//foreach ($_FILES["upload"]["error"] as $key => $error) {
   // if ($error == UPLOAD_ERR_OK) {
        $tmp_name = $_FILES["upload"]["tmp_name"];
        // basename() may prevent filesystem traversal attacks;
        // further validation/sanitation of the filename may be appropriate
        $name = basename($_FILES["upload"]["name"]);
      if (move_uploaded_file($_FILES['upload']['tmp_name'], $uploads_dir.$name)) {
        print "Received {$_FILES['upload']['name']} - its size is {$_FILES['upload']['size']}";
    } else {
        print "Upload failed!";
    }
    //}
//}
$url = "/uf_data/$dir/file_links/$name";
echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($funcNum, '$url', '$message');</script>";
?>
</body>
</html>
<?php
include "Download-Tiktok/DownloadClass.php";

$api = new DownloadClass();

// https://vt.tiktok.com/ZSe1Xp5Y3/

$error = false;
$errorMsg = "";
$strCon = false;
$str = '';

if(isset($_POST["buttonData"])) {
  $input = $_POST["input"];
  
  if($input != "") {
    if(filter_var($input, FILTER_VALIDATE_URL)) {
      
      $result = $api->Data($input);
      $resultErr = $result["err"];
      
      if($resultErr == "false") {
        $strCon = true;
        $str = '<div class="row justify-content-center mt-5 bg-primary rounded-3 p-3" id="detailContent">
      <h3 class="text-center my-3">Data Video Tiktok</h3>
      <div class="col-md-5">
        ' . $result["embedVid"] . '
      </div>
      <div class="col-md-2">
        
      </div>
      <div class="col-md-5">
        <div class="d-flex justify-content-center">
          <img src="' . $result["imageUrl"] . '" width="100" class="rounded-circle img-thumbnail mb-2">
        </div>
        
        <div class="text-center fs-5">
          <p> <b>Title :</b> '. $result["title"] .'</p>
          <p> <b>Nickname :</b> '. $result["nickname"] .'</p>
          <p> <b>Nick Id :</b> @'.  $result["nickId"] .'</p>
        </div>
      </div>
      
      <div class="col-md-8 mt-3">
        <form method="post">
            <input type="hidden" value="' . $result["filename"] . '" name="filename">
            <input type="hidden" value="' . $result["playAddr"] . '" name="url">
          <div class="d-grid gap-2">
            <button type="submit" class="btn btn-info" name="buttonDownload" id="buttonDownload">
              <i class="bi bi-download"></i> Download Video
            </button>
          </div>
        </form>
      </div>
      
    </div>';
        
      } else {
        $error = true;
        $errorMsg = $result["message"];
      }
      
    } else {
      $error = true;
      $errorMsg = "Input Field Must Be Url";
    }
    
  } else {
    $error = true;
    $errorMsg = "Input Field Must Be Required";
  }
  
}

if(isset($_POST["buttonDownload"])) {
  $url = $_POST["url"];
  $filename = $_POST["filename"];
  
  $api->DownloadUrl($url, $filename);
}

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
  <title>Fbaz Youtube | Tiktok Downloader</title>
</head>
<body class="bg-light">
  
  <div class="container">
    <div class="row mt-5 justify-content-center">
      <h2 class="text-center">Tiktok Downloader By Fbaz</h2>
      
      <form method="post">
        <input type="text" name="input" id="input" placeholder="Paste Your Url Tiktok Here" class="form-control mb-2" autofocus autocomplete="off"/>
        
        <div class="d-grid gap-2">
          <button type="submit" class="btn btn-primary" name="buttonData" id="buttonData">
            <i class="bi bi-file-earmark-bar-graph"></i> Get Data
          </button>
        </div>
        
      </form>
      
    </div>
    
    
    <?php if ( $error == true ): ?>
      <?= $errorMsg ?>
    <?php endif; ?>
    
    <?php if ( $strCon == true ): ?>
      <?= $str ?>
    <?php endif; ?>
    
    
  </div>
  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
  
  
  
</body>
</html>

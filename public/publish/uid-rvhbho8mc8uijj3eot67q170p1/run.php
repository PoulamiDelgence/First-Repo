
<html>
<head>
<link rel="stylesheet" href="//test.scampaigns.com/user_template/50-roelpro/css/main.css" />
<link rel="stylesheet" href="//test.scampaigns.com/user_template/50-roelpro/jquery.alerts.css">
 <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
  <script src="//test.scampaigns.com/user_template/50-roelpro/js/jquery.alerts.js"></script>
  <script type="text/javascript" src="//test.scampaigns.com/user_template/50-roelpro/js/html2canvas.js"></script>
<script type="text/javascript" src="//test.scampaigns.com/user_template/50-roelpro/js/jquery.plugin.html2canvas.js"></script>

         <script type="text/javascript">
      $(document).ready(function() {
            var uploadimg = $('#uploadimgtext').val();
            
          //$('.img-cont').css('background',"url('"+takeimgname+"') no-repeat"); 
          
         localStorage.setItem('imgupload',uploadimg);
         var takeimgname = localStorage.getItem('takeimgname');
            var imgupload = localStorage.getItem('imgupload');
            var lijfspreuk = localStorage.getItem('lijfspreuk');
        	//$('#target').css('visibility','hidden');
           // $('#target').fadeTo( 1000, 0 );
            //$("#target").animate({ opacity: 0 })
        $('#back-img').attr('src',takeimgname);
          $('#front-img').attr('src',imgupload);
         // $('.info').html(lijfspreuk);
        
          //alert(uploadimg);
         //
   
       // 
        	$('#target').html2canvas({
			onrendered: function (canvas) {
                //Set hidden field's value to image data (base-64 string)
				$('#img_val').val(canvas.toDataURL("image/png"));
                //Submit the form manually
			    $('form#myForm').submit();
               //window.location="//smartfanpage.com/screenshot/save.php";
			}
		});
        
        if($("#img_val").length)
          var val="";
          else{
            jAlert("Please upload a human's photo with front face");
              window.history.back();
          }
        
         });
         
            
         
</script>

<?php
ini_set('memory_limit', '-1');
//ini_set( 'display_errors', 1 );
//error_reporting( E_ALL ^ E_NOTICE );
include "FaceDetect.php";
include "ImageManipulator.php";


class FaceModify extends svay\FaceDetector {
 
  public function Rotate() {
    $canvas = imagecreatetruecolor($this->face['w'], $this->face['w']);
    imagecopy($canvas, $this->canvas, 0, 0, $this->face['x'], 
              $this->face['x'], $this->face['w'], $this->face['w']);
    $canvas = imagerotate($canvas, 180, 0);
    $this->_outImage($canvas);
  }
 
  public function toGrayScale() {
    $canvas = imagecreatetruecolor($this->face['w'], $this->face['w']);
    imagecopy($canvas, $this->canvas, 0, 0, $this->face['x'], 
              $this->face['x'], $this->face['w'], $this->face['w']);
    imagefilter ($canvas, IMG_FILTER_COLORIZE, 174, 192, 227);
    $this->_outImage($canvas);
  }
 
  public function resizeFace($width, $height) {
    $canvas = imagecreatetruecolor($width, $width);
    imagecopyresized($canvas, $this->canvas, 0, 0, $this->face['x'], 
                     $this->face['y'], $width, $height, 
                     $this->face['w'], $this->face['w']);
    imagefilter ($canvas, IMG_FILTER_GRAYSCALE);
    imagefilter ($canvas, IMG_FILTER_COLORIZE, 0, 0, 175);
    $this->_outImage($canvas);
  }
 
  private function _outImage($canvas) {
    header('Content-type: image/jpeg');
    imagejpeg($canvas);
  }
}
$detector = new FaceModify('detection.dat');
if ($_FILES['fileToUpload']['error'] > 0) {
     echo "dfs";
    echo "Error: " . $_FILES['fileToUpload']['error'] . "<br />";
} else {
    // array of valid extensions
    $validExtensions = array('.jpg', '.jpeg', '.gif', '.png');
    // get extension of the uploaded file
    $fileExtension = strrchr($_FILES['fileToUpload']['name'], ".");
    // check if file Extension is on the list of allowed ones
    if (in_array($fileExtension, $validExtensions)) {
        // we are renaming the file so we can upload files with the same name
        // we simply put current timestamp in fron of the file name
        $time=time().'_' ;
        $newName = $time. $_FILES['fileToUpload']['name'];
        $destination =  $_SERVER['DOCUMENT_ROOT'].'/publish/uid-rvhbho8mc8uijj3eot67q170p1//upload/'.$newName;
        if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $destination)) {
            //echo 'File ' .$newName. ' succesfully copied';
//            echo "File name: " . $_FILES['fileToUpload']['name'] . "<br />";
//    echo "File type: " . $_FILES['fileToUpload']['type'] . "<br />";
//    echo "File size: " . ($_FILES['fileToUpload']['size'] / 1024) . " Kb<br />";
//    echo "Temp path: " . $_FILES['fileToUpload']['tmp_name'];
$filename='upload/'.$time.$_FILES['fileToUpload']['name'] ;

 //$currentUrl = $_SERVER["SERVER_NAME"]; 
//$filename=$currentUrl.'/new/'.$filename;
$hold = $detector->faceDetect($filename);

$new_image_name = $filename;

$manipulator = new ImageManipulator($new_image_name);

  // print_r($hold);
        $x1 = $hold['x']; // 200 / 2
      $y1 = $hold['y']; // 130 / 2
       $x2 = $hold['x'] + $hold['w'] ; // 200 / 2
       $y2 = $hold['y'] + $hold['w']; // 130 / 2

    $newImage = $manipulator->crop($x1, $y1, $x2, $y2);
     
      //imagefilter($im, IMG_FILTER_COLORIZE, 0, 255, 0);
        // saving file to uploads folder
        $manipulator->save($new_image_name);   
        
//print_r($hold);
//exit;
//$detector->doDetectGreedyBigToSmall();

//$detector->toJpeg();

//
        }
        

    } else {
        echo 'You must upload an image...';
    }
  
}

?> 


</head>
<body style="position: inherit;">
 <div class="overlay_div" style="opacity: 0.40;">
        <img class="loading_img" src="//test.scampaigns.com/user_template/50-roelpro/712.GIF" />
  </div>
<form method="POST" enctype="multipart/form-data"  action="spreuk.php"id="myForm">
	<input type="hidden" name="img_val" id="img_val" value="" />
</form>

<div style="opacity: 0;">
 <div class="img-cont clearfix" id="target">
                <div class="front-img-cont"><img id="front-img" src=""  /></div>
                <div class="back-img-cont">
                <img  id="back-img" src="/publish/uid-rvhbho8mc8uijj3eot67q170p1//<?php echo $new_image_name;?>"/></div>
                </div> </div>

<input type="hidden" id="uploadimgtext" value="/publish/uid-rvhbho8mc8uijj3eot67q170p1//<?php echo $new_image_name;?>"/>
</body>
</html>

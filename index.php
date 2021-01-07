<?php
require_once "youtube_library_class.php";
$yt  = new YouTubeDownloader();
$error='';
if(isset($_POST['yt_video_link'])) {
    $yt_video_link = $_POST['yt_video_link'];
    if(!empty($yt_video_link)) {
        $vid = $yt->GetVideoId($yt_video_link);
        if($vid) {
            $data = $yt->VideoData($vid);          
            if($data) {
                $thumbnail    = $data['videos']["thumbnail"];
                $title    = $data['videos']["title"];
                $formats = $data['videos']['formats'];              
            }
            else {
                $error = "Something went wrong";
            }
        }
    }
    else {
        $error = "Enter YouTube video URL";
    }
}
?>
<!doctype html>
<html>
<head>
    <title>Download YouTube Video Using PHP</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
    .table-bordered {
        text-align: center;
    }
    th {
        background: #007bff;
        border: none;
        color: #fff;
    }
    button#submit {
        border-radius: unset;
    }
    </style>
</head>
<body>
    <div class="container mt-5">
        <form action="" method="post">
            <div class="row">
                <div class="col-lg-12">
                    <h2 class="text-center"> Download YouTube Video</h2>
                </div>
                <div class="col-lg-8 offset-2">
                    <div class="input-group">
                        <input type="text" class="form-control" name="yt_video_link" placeholder="Past your video link here">
                        <button type="submit" name="submit" id="submit" class="btn btn-primary">Download</button>
                    </div>
                    <?php if($error){?>
                        <div class="text-danger"><b><?php echo $error?></b></div>
                    <?php } ?>
                </div>
            </div>
        </form>
        <?php if(isset($formats)){?>
             <div class="row mt-4 text-center">
                <div class="col-lg-12">
                    <img src="<?php echo $thumbnail?>" class="img-responsive">
                </div>
                <div class="col-lg-12">
                    <b><?php echo $title?></b>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-lg-8 offset-2">
                    <table class="table table-bordered">
                        <tr>
                            <th>Type</th>    
                            <th>Quality</th>
                            <th>Download</th>     
                        </tr>
                         <?php foreach ($formats as $video) {?>
                            <tr>
                                <td><?php echo $video['type']?></td>
                                <td><?php echo $video['quality']?></td>
                                <td><a href="downloader.php?link=<?php echo urlencode($video['link'])?>&title=<?php echo urlencode($title)?>&type=<?php echo urlencode($video['type'])?>" class="btn btn-primary">Download</a> </td>
                            </tr>
                         <?php } ?>
                    </table>
                </div>    
            </div>
        <?php } ?>
    </div>
</body>
</html>

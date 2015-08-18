<?php


class FileControllerBase extends ControllerBase
{

    public function initialize()
    {
        parent::initialize();
    }

    /**
     * @param $background
     * @param $width
     * @param $height
     * @param $newfile
     * 图片等比列缩放
     */
    function thumn($background, $width, $height, $newfile)
    {
        list($s_w, $s_h) = getimagesize($background); //获取原图片高度、宽度

        if ($width && ($s_w < $s_h)) {
            $width = ($height / $s_h) * $s_w;
        } else {
            $height = ($width / $s_w) * $s_h;
        }

        $new = imagecreatetruecolor($width, $height);

        $img = imagecreatefromjpeg($background);

        imagecopyresampled($new, $img, 0, 0, 0, 0, $width, $height, $s_w, $s_h);

        imagejpeg($new, $newfile);

        imagedestroy($new);
        imagedestroy($img);
    }

    /**
     * @param $background
     * @param $text
     * @param $x
     * @param $y
     * 文字水印
     */
    function mark_text($background, $text, $x, $y)
    {
        $back = imagecreatefromjpeg($background);

        $color = imagecolorallocate($back, 0, 255, 0);

        imagettftext($back, 20, 0, $x, $y, $color, "simkai.ttf", $text);

        imagejpeg($back, imagePath . "shuiyin.jpg");

        imagedestroy($back);
    }

    /**
     * @param $background
     * @param $waterpic
     * @param $x
     * @param $y
     * 图片水印
     */
    function mark_pic($background, $waterpic, $x, $y)
    {
        $back = imagecreatefromjpeg($background);
        // $water = imagecreatefromgif($waterpic);
        $water = imagecreatefrompng($waterpic);
        $w_w = imagesx($water);
        $w_h = imagesy($water);
        imagecopy($back, $water, $x, $y, 0, 0, $w_w, $w_h);
        imagejpeg($back, imagePath . "tupian.jpg");
        imagedestroy($back);
        imagedestroy($water);
    }

    /**
     * 测试SFTP上传
     */
    public function  sftp($file,$destination)
    {
        $fileName = $file["name"];
        //  $sftp = new SFTPConnection("10.10.1.43", 22);
        // $sftp->login("hadoop", "hadoop11");
        // $sftp->uploadFile($destination, "/alidata/www/share/" . $fileName);
        $this->upload->uploadFile($destination, "/alidata/www/share/" . $fileName);


//        $fileName = $_FILES["files"]["name"];
//        $sftp = new SFTPConnection("10.10.1.43", 22);
//        $sftp->login("hadoop", "hadoop11");
//        $sftp->uploadFile($_FILES["files"]["tmp_name"], "/alidata/www/share/" . $fileName);
    }

    public function sftps($file){
        $fileName = $file["name"];
        //  $sftp = new SFTPConnection("10.10.1.43", 22);
        //  $sftp->login("hadoop", "hadoop11");
        $this->upload->uploadFile($file["tmp_name"], "/alidata/www/share/" . $fileName);
        //  $sftp->uploadFile($file["tmp_name"], "/alidata/www/share/" . $fileName);
    }

}
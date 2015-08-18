<?php

define('imagePath', $_SERVER['DOCUMENT_ROOT'] . '/files/images/');
define('appendixPath', $_SERVER['DOCUMENT_ROOT'] . '/files/');

class UploadController extends ControllerBase
{
    /**
     * 上传图片
     */
    public function  uploadImageAction()
    {
        header("Content-Type:application/json");
        $destination_folder = imagePath; //上传文件路径
        //  $destination_folder = "/alidata/www/share/"; //上传文件路径
        $file = $_FILES["file"];
        $errorMessage = array();
        if (!is_uploaded_file($file["tmp_name"])) //是否存在文件
        {
            $errorMessage["zz"] = "upload image not exists";
            return json_encode($errorMessage);

        }
        $pinfo = pathinfo($file["name"]);
        $ftype = $pinfo['extension'];
        $destination = $destination_folder . $file["name"];
        //   $this->sftps($file);
        if (!move_uploaded_file($file["tmp_name"], $destination)) {
            $errorMessage["yy"] = "upload fail";
        } else {
            $errorMessage["xx"] = "upload success";
            //  $this->sftp($file,$destination);
            //     $this->mark_text($destination,"bsito",150,250); //文字水印
            //    $this->mark_pic($destination, imagePath."bsito.png", 50, 200); //图片水印
            //     $this->thumn($destination, 200, 200, $destination_folder . $pinfo["filename"] . "thumn." . $ftype);//缩略图
//            $errorMessage["xx"] = shell_exec("/alidata/www/rsync.sh");
//            system("/alidata/www/rsync.sh",$result2);
//            $errorMessage["yy"]=$result2;
//            exec("/alidata/www/rsync.sh",$result);
//            $errorMessage["zz"]=$result;
//            passthru("/alidata/www/rsync.sh",$result1);
//            $errorMessage["aa"]=$result1;
        }
        //  return json_encode($errorMessage);
        return json_encode($errorMessage);
    }

    /**
     * 上传附件
     */
    public function uploadFileAction()
    {
        $destination_folder = appendixPath; //上传文件路径
        $file = $_FILES["appendix"];
        $errorMessage = array();
        if (!is_uploaded_file($file["tmp_name"])) //是否存在文件
        {
            $errorMessage[] = "附件不存在";
            return $errorMessage;

        }
        $pinfo = pathinfo($file["name"]);
        $ftype = $pinfo['extension'];
        //目标文件名为32位随机数。
        $destination = $destination_folder . $file["name"];
        if (!move_uploaded_file($file["tmp_name"], $destination)) {
            $errorMessage[] = "上传附件失败!";
        } else {
            $errorMessage[] = "上传附件成功";
        }
        return json_encode($errorMessage);
    }

    /**
     * 批量上传图片
     */
    public function  uploadBatchImageAction()
    {
        $dest_folder = imagePath;
        foreach ($_FILES["file"]["error"] as $key => $error) {
            if ($error == UPLOAD_ERR_OK) {
                $tmp_name = $_FILES["file"]["tmp_name"][$key];
                $name = $_FILES["file"]["name"][$key];
                $uploadfile = $dest_folder . $name;
                move_uploaded_file($tmp_name, $uploadfile);
            }
        }
    }
}
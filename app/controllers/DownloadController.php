<?php
/**
 * Created by PhpStorm.
 * User: lv
 * Date: 15-1-29
 * Time: 下午4:39
 */

define('imagePath', $_SERVER['DOCUMENT_ROOT'] . '/files/images/');
define('appendixPath', $_SERVER['DOCUMENT_ROOT'] . '/files/appendix/');

class DownloadController extends ControllerBase
{

    /**
     * @param $image
     * @return string
     * 下载图片
     */
    public function  downloadAction($image)
    {
        $errMesage = array();
        $suffix = pathinfo($image, PATHINFO_EXTENSION);
        switch ($suffix) {
            case "jpg":
                header('Content-type: application/x-jpg;charset=utf-8');
                break;
            case "jpeg":
                header('Content-type: image/jpeg;charset=utf-8');
                break;
            case "png":
                header('Content-type: image/jpeg;charset=utf-8');
                break;
            case "gif":
                header('Content-type: image/jpeg;charset=utf-8');
                break;
            case "icon":
                header('Content-type: image/x-icon;charset=utf-8');
                break;
            case "bmp":
                header('Content-type:application/x-bmp;charset:utf-8');
        }
        $fileName = imagePath . $image;
        if (is_file($fileName)) {
            $PSize = filesize($fileName);
            $picturedata = fread(fopen($fileName, "r"), $PSize);
            return $picturedata;
        } else {
            $errMesage[] = "file not exists";
            return json_encode($errMesage);
        }

    }

    /**
     * @param $appendix
     * @return array|int
     * 下载附件
     */
    public function  downloadFileAction($appendix)
    {
        $errorMessage = array();
        $fileName = appendixPath . $appendix;
        if (is_file($fileName)) {
            Header("Content-type: application/force-download");
            Header("Content-Disposition: attachment; filename=" . basename($fileName));
            return readfile($fileName);
        } else {
            $errorMessage[] = "file not exists";
            return json_encode($errorMessage);
        }

    }

} 
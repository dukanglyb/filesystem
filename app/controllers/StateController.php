<?php
/**
 * Created by PhpStorm.
 * User: lv
 * Date: 15-2-2
 * Time: 下午2:21
 */

define('imagePath', $_SERVER['DOCUMENT_ROOT'] . '/files/images/');
define('appendixPath', $_SERVER['DOCUMENT_ROOT'] . '/files/appendix/');
define('fileSystem', $_SERVER['DOCUMENT_ROOT'] . '/files/');
class StateController extends ControllerBase
{
    /**
     * 获取单个目录结构的文件系统
     */
    public function  getatalogueStateAction()
    {

    }

    /**
     *获取单个文件属性
     */
    public function  getSingleImageAction($image)
    {
        $properties = array();
        $fileName = imagePath . $image;
        $info = getimagesize($fileName);
        $properties["heght"] = $info[0];
        $properties["width"] = $info[1];
        $properties["mime"] = $info["mime"];
        $info1 = exif_read_data($fileName);
        $properties["size"] = $info1["FileSize"];
        $properties["time"] = date('Y-m-d H:i:s', $info1["FileDateTime"]);
        return json_encode($properties);
    }

    /**
     * 获取整个文件系统的大小
     */
    public function  getFileTotalAction(){
        $info = array();
        $total = $this->getFileTotal(fileSystem);
        $info["TotalSize"] = $total;
        return json_encode($info);

    }

    /**
     * @param $dir
     * @return int
     * 获取整个文件系统的大小
     */
    public function  getFileTotal($dir){
        $handle = opendir($dir);
        static $sizeResult=0;
        while (false!==($FolderOrFile = readdir($handle)))
        {
            if($FolderOrFile != "." && $FolderOrFile != "..")
            {
                if(is_dir("$dir/$FolderOrFile"))
                {
                      $this->getFileTotal("$dir/$FolderOrFile");
                }
                else
                {
                    $sizeResult += filesize("$dir/$FolderOrFile");
                }

            }
        }
        closedir($handle);
        return $sizeResult;
    }

} 
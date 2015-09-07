<?php

/**
 * @RoutePrefix("/tfs")
 * Class BfsController
 */
class BfsController extends FileControllerBase
{
    public function initialize()
    {
        parent::initialize();
        $this->view->setRenderLevel(\Phalcon\Mvc\View::LEVEL_NO_RENDER);
    }

    /**
     * 获取文件路径
     * @return string
     */
    public function getFilePath(){
        return 'files/'.date('Ym').'/';
    }

    /**
     * 创建目录
     * @param $dir
     * @param int $mode
     * @return bool
     */
    function createFile($dir,$mode=0777){
        if(is_dir($dir)||@mkdir($dir,$mode)){
            return true;
        }
        if(!mkdir(dirname($dir),$mode)){
            return false;
        }
        return @mkdir($dir,$mode);
    }

    /**
     * 上传图片
     * @Route("/", methods={"POST"}, name="filegost")
     */
    public function  filePostAction()
    {
        //是否重命名，默认为重命名
        $customname = $this->request->get('customname',null,false);
        //文件扩展名
        $suffix = $this->request->get('suffix',null,null);
        //如果有自定义文件名，则使用自定义。
        //如果没有，则生成32位文件名。
        if($customname){
            $filename =$customname;
        }else{
            //$filename = Phalcon\Text::random(\Phalcon\Text::RANDOM_ALNUM, 32);//随机数可为16 24 32
            $filename = microtime(true);
        }
        $fileNameWithOutExt = $filename;
        //加入扩展名
        if($suffix!=null){
            $filename .= '.'.$suffix;
        }

        //如果文件夹不存在则创建
        if (!file_exists ($this->getFilePath())) {
            $this->createFile($this->getFilePath(),0755);
        }
        echo $this->getFilePath();
        //保存二进制流文件到文件名所在路径
        $ret = $this->binary_to_file($this->getFilePath() . $filename);

        if($ret) {
            if($suffix=='pdf'){
                chmod($_SERVER['DOCUMENT_ROOT'] . '/'.$this->getFilePath() . $filename, 0666);
                $pdfpngfilenames = $this->pdf2png($_SERVER['DOCUMENT_ROOT'] . '/'.$this->getFilePath() . $filename,$_SERVER['DOCUMENT_ROOT'] . '/'.$this->getFilePath() ,$fileNameWithOutExt);
                echo json_encode(array('TFS_FILE_NAME' => $pdfpngfilenames));
            }else{
                echo json_encode(array('TFS_FILE_NAME' => $filename));
            }
        }else{
            echo json_encode(array('TFS_FILE_NAME' => false));
        }
    }

    /** 二进制流生成文件
     * $_POST 无法解释二进制流，需要用到 $GLOBALS['HTTP_RAW_POST_DATA'] 或 php://input
     * $GLOBALS['HTTP_RAW_POST_DATA'] 和 php://input 都不能用于 enctype=multipart/form-data
     * @param    String  $file   要生成的文件路径
     * @return   boolean
     */
    function binary_to_file($file){
        //中文名特殊处理
        $file = iconv( 'UTF-8', 'GB18030', $file );
        //如果文件名已经存在，则返回失败。
        if(file_exists($file)){
            return false;
        }
        $content = file_get_contents('php://input');    // 不需要php.ini设置，内存压力小
        $ret = file_put_contents($file, $content, true);
        return $ret;
    }



    /**
     * 获取文件
     * @Route("/{filePath}/{fileName}", methods={"GET"}, name="fileget")
     * @param $filePath
     * @param $fileName
     * @return \Phalcon\HTTP\ResponseInterface|string
     */
    public function  filegetAction($filePath,$fileName)
    {
        $errorMessage = array();
//        $fileName = $_SERVER['DOCUMENT_ROOT'] .'/'.$this->getFilePath(). $fileName;
        $fileName = $_SERVER['DOCUMENT_ROOT'] .'/files/'.$filePath.'/'. $fileName;
        //中文名特殊处理
        $fileName = iconv( 'UTF-8', 'GB18030', $fileName );
        if (is_file($fileName)) {
            $response = $this->response;

            $file = escapeshellarg( $fileName );
            $mimetype =explode(';', shell_exec("file -bi " . $file))[0];
            $response->setHeader("Content-Disposition", "inline");
            $response->setHeader("Content-Type", $mimetype);
            $response->setHeader('Cache-Control', 'public, max-age=86400');
            $response->setContent(file_get_contents($fileName));
            return $response;
        } else {
            $errorMessage[] = "file not exists";
            return json_encode($errorMessage);
        }
    }

    /**
     * 删除文件
     * @Route("/{filePath}/{fileName}", methods={"DELETE"}, name="filedelete")
     * @param $filePath
     * @param $fileName
     * @return string
     */
    public function  filedeleteAction($filePath,$fileName)
    {
        $errorMessage = array();
//        $fileName = $_SERVER['DOCUMENT_ROOT'] .'/'.$this->getFilePath(). $fileName;
        $fileName = $_SERVER['DOCUMENT_ROOT'] .'/files/'.$filePath.'/'. $fileName;
        $fileName = iconv( 'UTF-8', 'GB18030', $fileName );
        if (is_file($fileName)) {
            return json_encode(
                array(
                    'fileName' => $fileName,
                    'deleted' => unlink($fileName)
                )
            );
        } else {
            $errorMessage[] = "file not exists";
            return json_encode($errorMessage);
        }
    }

    function pdf2png($PDF,$Path,$filename){
        if(!extension_loaded('imagick')){
            return false;
        }
        if(!file_exists($PDF)){
            return false;
        }
        $IM =new imagick();
//        $IM->setResolution(120,120);
//        $IM->setCompressionQuality(100);
        $IM->readImage($PDF);
        $IM->writeImages($Path."/$filename.png", false);
        $pages = $IM->count();
        $Return = array();
        for($i=0;$i<$pages;$i++){
            $Return[]="$filename-$i.png";
        }
//        $page = 0;
//        foreach($IM as $Key => $Var){
//            $page++;
//            $Var->setImageFormat('png');
//            $Filefullname = $Path."/$filename($page).png";
//            if($Var->writeImage($Filefullname)==true){
//                $Return[]= "$filename($page).png";
//            }
//        }
        return $Return;
    }
}
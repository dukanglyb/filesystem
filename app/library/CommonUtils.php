<?php
/**
 * Created by PhpStorm.
 * User: xiang
 * Date: 14-10-20
 * Time: 上午10:15
 */

use Phalcon\Mvc\User\Component;


class CommonUtils extends Component{


  /**
   * 判断$string是否以$neddle结尾
   * @param $string
   * @param $needle
   * @return bool
   */
  public   static function  endWith($string,$needle){

    if(!$needle) return false;

    $nlen = strlen($needle);

    $strend = substr($string,-$nlen);

    return ($strend==$needle)?true:false;

  }

  /**
   * 字符串驼峰转下划线
   * @param $string
   * @return string
   */
  public static  function camelToUnderline($string){

    return   strtolower(preg_replace('/((?<=[a-z])(?=[A-Z]))/', '_',  $string));

  }

  /**
   * 递归循环遍历文件目录
   * @param $dir
   * @return array
   */
  public  static function scandir($dir)
  {
    static $files = array();
    $dir_list = scandir($dir);
    foreach($dir_list as $file)
    {
      if ( $file != ".." && $file != "." )
      {
        if ( is_dir($dir . "/" . $file) )
        {
          CommonUtils::scandir($dir . "/" . $file);
        }
        else
        {
          $files[] = $file;
        }
      }
    }

    return $files;
  }

  /**
   * 生成随机数
   * @param int $length
   * @return string
   */
  public static function generationRand($length = 20){
    $output='';
    if(!(is_numeric($length)&&$length>=1)){//如果不是大于1的正整，则取默认值
      $length = 20;
    }
    $result = '';
    for($gen_length = 0;$gen_length <$length;){
      $charid = strtoupper(md5(uniqid(mt_rand(), true)));
      $result.=$charid;
      $gen_length+=strlen($charid);
    }
    $result = substr($result,0,$length);
    return $result;
  }


  /**
   * 时间差计算
   * @param $nowTime
   * @param $frontTime
   * @return int
   */
  public static function  subtractionTime($nowTime,$frontTime){
    // $nowTime =strtotime(date('Y-m-d H:i:s',$nowTime));
    $frontTime =strtotime($frontTime);
    return $nowTime-$frontTime;
  }


  /**
   * 获取数组key的value,如果key不存在，则返回默认值
   * @param $array 数组
   * @param $key key
   * @param $defaultValue 默认值（null）
   * @return 返回key的value
   */
  public static function arrayKey($array,$key,$defaultValue = null){
    if(array_key_exists($key,$array)){
      return $array[$key];
    }else{
      return $defaultValue;
    }
  }

  /**
   * 如果为空，则返回默认值
   * @param $var 判断的变量
   * @param null $defaultValue
   */
  public static function getNullDefault($var,$defaultValue = null){
    if(!$var){
      return $defaultValue;
    }else{
      return $var;
    }
  }

    /**
     * 获取真实IP地址
     * @return string
     */
    public static function getIPaddress()
    {
        $IPaddress='';
        if (isset($_SERVER)){
            if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])){
                $IPaddress = $_SERVER["HTTP_X_FORWARDED_FOR"];
            } else if (isset($_SERVER["HTTP_CLIENT_IP"])) {
                $IPaddress = $_SERVER["HTTP_CLIENT_IP"];
            } else {
                $IPaddress = $_SERVER["REMOTE_ADDR"];
            }
        } else {
            if (getenv("HTTP_X_FORWARDED_FOR")){
                $IPaddress = getenv("HTTP_X_FORWARDED_FOR");
            } else if (getenv("HTTP_CLIENT_IP")) {
                $IPaddress = getenv("HTTP_CLIENT_IP");
            } else {
                $IPaddress = getenv("REMOTE_ADDR");
            }
        }
        return $IPaddress;
    }

    /**
     * 腾讯获取客户端地址
     * @param $queryip
     * @return mixed
     */
    public static function getiploc_qq($queryip){
        $url = 'http://ip.qq.com/cgi-bin/searchip?searchip1='.$queryip;
        $ch = curl_init($url);
        curl_setopt($ch,CURLOPT_ENCODING ,'gb2312');
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; // 获取数据返回
        $result = curl_exec($ch);
        $result = mb_convert_encoding($result, "utf-8", "gb2312"); // 编码转换，否则乱码
        curl_close($ch);
        preg_match("@<span>(.*)</span></p>@iu",$result,$iparray);
        $loc = $iparray[1];
        return $loc;
    }

    /**
     * 新浪获取客户端地址
     * @param $queryip
     * @return string
     */
    public static  function getiploc_sina($queryip){
        $url = 'http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip='.$queryip;
        $ch = curl_init($url);
        curl_setopt($ch,CURLOPT_ENCODING ,'utf8');
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; // 获取数据返回
        $location = curl_exec($ch);
        $location = json_decode($location);
        curl_close($ch);
        $loc = "";
        if($location===false) return "";

        if(ret===1){
            if (empty($location->desc)) {
                $loc = $location->province.$location->city.$location->district.$location->isp;
            }else{
                $loc = $location->desc;
            }
        }
        return $loc;
    }
}
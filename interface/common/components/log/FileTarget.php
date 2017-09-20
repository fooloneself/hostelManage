<?php
namespace common\components\log;
use Think\Exception;
class FileTarget {
    //文件日志记录文件目录
    public $logFile;
    //单个日志文件最大byte数
    public $maxFileSize;
    //日志文件扩展名
    public $ext;
    //日志文件权限
    public $dirMode;
    //单条日志之间的分割符
    public $directorySeparator;
    //即将记录的日志大小
    protected $len;

    public function __construct($logFile='runtime/log/app',$maxFileSize=10485760,$ext='log',$dirMode=0777,$directorySeparator="\n")
    {
        $this->logFile=$logFile;
        $this->maxFileSize=$maxFileSize;
        $this->ext=$ext;
        $this->dirMode=$dirMode;
        $this->directorySeparator=$directorySeparator;
    }

    /**
     * 获取日志文件存放目录
     * @return bool|string
     */
    protected function getLogBasePath(){
        if($this->logFile===null) {
            $this->logFile = \Yii::$app->getBasePath() . DIRECTORY_SEPARATOR . 'log/app';
        }
        return \Yii::getAlias($this->logFile);
    }

    /**
     * 获取日志将要记录到的文件路径
     * 如果不存在，则自动创建
     * 如果当前日志文件达到最大，则把当前文件名重命名为下一个日志文件
     * @return string
     */
    protected function getLogFile(){
        $path=$this->getLogBasePath();
        if(!is_dir($path)){
            mkdir($path,$this->dirMode,true);
        }
        $fileName=date('Ymd');
        $file=$path.DIRECTORY_SEPARATOR.$fileName;
        if($this->isTooBig($file)){
            rename($file,$this->getNotExitLog($path,$fileName));
        }
        return $file;
    }

    /**
     * 获取下一个要存放的日志文件文件
     * @param $path
     * @param $fileName
     * @param int $num
     * @return string
     */
    protected function getNotExitLog($path,$fileName,$num=1){
        $file=$path.DIRECTORY_SEPARATOR.$fileName.'_'.$num.'.'.$this->ext;
        if(is_file($file)){
            return $this->getNotExitLog($path,$fileName,$num+1);
        }else{
            return $file;
        }
    }

    /**
     * 记录日志
     * @param $message
     * @throws Exception
     */
    public function log($message){
        $this->len=strlen($message);
        $message.=$this->directorySeparator;
        try{
            $file=$this->getLogFile();
            $isNew=is_file($file)?false:true;
            file_put_contents($file,$message,FILE_APPEND);
            if($isNew){
                chmod($file,$this->dirMode);
            }
        }catch (Exception $e){
            if(YII_DEBUG){
                throw $e;
            }
        }
    }

    /**
     * 判断日志文件是否过大
     * @param $file
     * @return bool
     */
    public function isTooBig($file){
        if(is_file($file)){
            if((filesize($file)+$this->len)>=$this->maxFileSize){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
}
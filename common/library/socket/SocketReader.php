<?php
namespace common\library\socket;
use common\library\Helper;

class SocketReader implements SocketReaderInterface
{
    public $lengthPerReadTime=10240000; //单词读取长度
    public $sleepPerReadTime=100000;        //每次读取间隔时间（单位：微秒）
    protected $socket;                      //socket连接管理器

    /**
     * 获取相应的数据
     * @return array|bool
     */
    public function getResponse()
    {
        $response='';
        $len = 0;                     //响应的字符长度
        $isFirstGetString = true;     //是否是首次获取到响应字符串
        $n = 0;
        while (true) {
            $buffer = socket_read($this->socket->getSocketObject(), $this->lengthPerReadTime);
            if ($buffer) {
                if ($isFirstGetString ===true) {
                    $len = intval(substr($buffer, 0, 8));
                    $isFirstGetString=false;
                }
                $response .= $buffer;
                if (strlen($response) >= $len + 8) {
                    break;
                }
            }
            if($n>10){
                $response=false;
                break;
            }
            $n++;
            usleep($this->sleepPerReadTime); //暂停0.01秒
        }
        if($response===false){
            return false;
        }else{
            return self::xmlToArray($response,$len);
        }
    }

    /**
     * 将相应的xml转为数组
     * @param $response
     * @param $len
     * @return array
     */
    protected static function xmlToArray($response,$len){
        $response = substr($response, 8, $len);
        $response = substr($response, 0, strrpos($response, ">") + 1);
        $response = iconv('gb2312', 'utf-8', $response);
        $response = str_replace('encoding="GB2312"', 'encoding="UTF-8"', $response);
        $response = new \SimpleXMLElement($response);
        return Helper::xmlToArray($response);
    }

    /**
     * 绑定socket管理器
     * @param SocketInterface $socket
     * @return $this
     */
    public function bindSocket(SocketInterface $socket)
    {
        $this->socket=$socket;
        return $this;
    }
}
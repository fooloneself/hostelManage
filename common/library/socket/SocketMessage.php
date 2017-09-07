<?php
namespace common\library\socket;
class SocketMessage implements SocketMessageInterface{
    public $macIp;
    public $macPort;
    public $defaultTrBranch;    //默认交易机构
    public $icApiTermId;        //终端ID
    public $icApiUserName;      //用户名
    public $icApiPwd;           //用户密码
    protected $code;            //交易编码
    protected $cityCode='028';  //城市编号
    protected $trBranch='';     //交易机构
    protected $bodyParams=[];    //交易参数

    /**
     * 获取报文
     * @return string
     */
    public function getMessage()
    {
        $xml = $this->getXml();
        $len = 100000008 + strlen($xml);
        return substr($len, 1, 8) . $xml . $this->getICMac($xml); //拼接字符串
    }

    /**
     * 设置请求主体参数
     * @param array $params
     * @return $this
     */
    public function setBodyParams(array $params)
    {
        $this->bodyParams=$params;
        return $this;
    }

    /**
     * 设置交易编码
     * @param $code
     * @return $this
     */
    public function setCode($code){
        $this->code=$code;
        return $this;
    }

    /**
     * 设置交易机构
     * @param $trBranch
     * @return $this
     */
    public function setTrBranch($trBranch){
        $this->trBranch=$trBranch;
        return $this;
    }

    /**
     * 设置城市编码
     * @param $cityCode
     * @return $this
     */
    public function setCityCode($cityCode){
        $this->cityCode=$cityCode;
        return $this;
    }

    /**
     * 获取交易机构
     * @return string
     */
    public function getTrBranch(){
        return $this->trBranch ? $this->trBranch : $this->defaultTrBranch;
    }

    /**
     * 获取ic卡的MAC号
     * @param $xml
     * @return bool|int|string
     */
    protected function getICMac($xml){
        $num= en_mac($this->macIp,$this->macPort,$xml);
        if(intval($num)<0){
            return -1;
        }else{
            return substr($num,0,8);
        }
    }

    /**
     * 获取请求数据的xml
     * @return string
     */
    protected function getXml() {
        $xml = '<?xml version="1.0" encoding="GB2312"?>';
        $xml.= '<request>';
        $xml.=$this->makeRequestHeaderXml();
        $xml.=$this->makeRequestBodyXml();
        $xml.="</request>";
        return $xml;
    }

    /**
     * 生成一个流水号
     * */
    public static function makeSerial(){
        return 'lp'.self::random32(4);
    }
    /**
     * 生成32位随机数
     * @return Ambigous <string, number>
     */
    public static function random32($n=8){
        $str='';
        for ($i=0;$i<$n;$i++){
            $str .=rand(1000, 9999);
        }
        return $str;
    }

    /**
     * 生成请求主体的xml
     * @return string
     */
    protected function makeRequestBodyXml(){
        $body='<body>';
        if(!empty($this->bodyParams)){
            $body.=self::toXml($this->bodyParams);
        }
        $body.='</body>';
        return $body;
    }

    /**
     * 生成请求头部的xml
     * @return string
     */
    protected function makeRequestHeaderXml(){
        $header ='<header>';
        $header.=self::toXml($this->getHeaderParams());
        $header.='</header>';
        return $header;
    }

    /**
     * 获取请求头部信息
     * @return array
     */
    public function getHeaderParams(){
        $serial = self::makeSerial();
        //如果商户号为空使用默认商户号
        $trBranch=$this->getTrBranch();
        $headerParams=[
            'TrCode'=>$this->code,                  //交易码
            'Cserial'=>$serial,                     //渠道流水号
            'Cdate'=>date('Ymd', time()),   //渠道日期
            'Ctime'=>date('Hms', time()),   //渠道时间
            'ChID'=>'03',                           //渠道标志  00-柜面，01-自助机，02-POS机，03-手机APP
            'Teller'=>'app',                        //app 填写手机号
            'TermID'=>$this->icApiTermId,
            'TrBranch'=>$trBranch,                  //交易机构
            'Poundage'=>0,                          //手续费
            'Expense'=>0,                           //工本费
            'Currey'=>156,                          //币种
            'CityCode'=>$this->cityCode,            //城市区域码
            'UserName'=>$this->icApiUserName,       //用户名
            'UserPsw'=>$this->icApiPwd              //用户密码
        ];
        return $headerParams;
    }

    /**
     * 将参数转为xml标签
     * @param array $data
     * @return string
     */
    public static function toXml(array $data){
        $str='';
        foreach ($data as $k => $v) {
            $str .= '<' . $k . '>' . $v . '</' . $k . '>';
        }
        return $str;
    }
}
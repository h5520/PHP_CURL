<?php
namespace houzhonghua\curl;

class Curl
{
	// url
	private $url;
	// 请求方式
	private $method;
	// https 请求
	private $https;
	// 请求头信息
	private $header;
	// 请求时间，超过这个时间自动断开请求
	private $timeout;

	/**
	 * 初始化方法
	 * @Author   HOU
	 * @DateTime 2021-09-11T16:11:42+0800
	 * @param    [type]                   $url
	 * @param    [type]                   $method
	 * @param    [type]                   $header
	 * @param    [type]                   $https
	 * @param    [type]                   $timeout
	 */
	public function __construct($url,$method = "POST",$https = false,$header = [],$timeout = 60)
	{
		$this->url = $url;
		$this->method = $method;
		$this->https = $https;
		$this->header = $header;
		$this->timeout = $timeout;
	}

	/**
	 * Curl
	 * @Author   HOU
	 * @DateTime 2021-09-11T16:12:18+0800
	 * @param    array                    $data
	 */
	public function curl($data = [])
	{
		$method = strtoupper($this->method);
        // 初始化
        $ch = curl_init();
        // 访问的URL
        curl_setopt($ch, CURLOPT_URL, $this->url);
        // 只获取页面内容，但不输出
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if($this->https){

            // https请求 不验证证书
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            // https请求 不验证 HOST
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }

        if ($method != "GET"){

            if($method == 'POST'){
                // 请求方式为post请求
                curl_setopt($ch, CURLOPT_POST, true);
            }

            if($method == 'PUT' || strtoupper($method) == 'DELETE'){
                // 设置请求方式
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            }

            // 请求数据
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }

        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
        
        if($this->header){
        	// 模拟的header头
	        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->header);
        }else{
        	// 设置不需要头信息
        	curl_setopt($ch, CURLOPT_HEADER, false);
        }
        
        // 执行请求
        $result = curl_exec($ch);

        //  输出错误信息
        if (curl_errno($ch)) {
            return 'Curl error: ' . curl_error($ch);
        }

        // 关闭 curl，释放资源
        curl_close($ch);

        return $result;
	}
}
?>
<?php
class RedisServer{
    /**
     * $host : redis服务器ip
     * $port : redis服务器端口
     * $lifetime : 缓存文件有效期,单位为秒
     * $cacheid : 缓存文件路径,包含文件名
     */
    private $host;
    private $port;
    private $lifetime;
    private $cacheid;
    private $data;
    public $redis;

    //析构函数,检查缓存目录是否有效,默认赋值

    function __construct()
    {
        $this->host='127.0.0.1';
        $this->port='6379';
        $redis = new Redis();
        $redis->connect($this->host,$this->port);
        $redis->lpush('today_cost', 30);
        $redis->lpush('today_cost', 1.5);
        $redis->lpush('today_cost', 10);
        $redis->lpush('today_cost', 8);
    }
}
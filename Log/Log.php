<?php


namespace base\log;


class Log
{
    public $date;
    public $requestUrl;
    public $requestType;

    public $get = 'null';
    public $post = 'null';

    public $code = 'null';
    public $message = 'null';

    public $remoteAddr;
    public $userAgent;
    public $redirectStatus;

    public function __construct()
    {
        $this->date = date("Y-m-d H:i:s");
        $this->requestUrl = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : 'null';
        $this->requestType = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'null';

        $this->remoteAddr = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'null';
        $this->userAgent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'null';
        $this->redirectStatus = isset($_SERVER['REDIRECT_STATUS']) ? $_SERVER['REDIRECT_STATUS'] : 'null';
    }

    public function save($filepath)
    {
        $log = "{$this->date} | Request URL: {$this->requestUrl} | Request type: {$this->requestType} | Error code: {$this->code} | Error message: {$this->message} | GET-data: {$this->get} | POST-data: {$this->post} | Remote IP: {$this->remoteAddr} | User Agent: {$this->userAgent} | Redirect status: {$this->redirectStatus} \n";

        $file = fopen($filepath, "a");
        fwrite($file, $log);
        fclose($file);
    }
}
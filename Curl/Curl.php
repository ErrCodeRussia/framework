<?php


namespace base\curl;


class Curl
{
    private $curl;

    private $url;

    private $is_post = false;
    private $is_put = false;
    private $is_delete = false;
    private $postdata = null;

    private $returntransfer = true;
    private $ssl_veryfipeer = false;
    private $header = false;

    public function __construct()
    {
        $this->curl = curl_init();
    }

    public function get($url)
    {
        $this->url = $url;
        return $this->exec();
    }

    public function post($url, $data = null)
    {
        $this->url = $url;

        $this->is_post = true;
        $this->postdata = $data;

        return $this->exec();
    }

    public function put($url, $data = null)
    {
        $this->url = $url;

        $this->is_put = true;
        $this->postdata = $data;

        return $this->exec();
    }

    public function delete($url)
    {
        $this->url = $url;
        $this->is_delete = true;

        return $this->exec();
    }

    public function exec()
    {
        curl_setopt($this->curl, CURLOPT_URL, $this->url);

        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, $this->returntransfer);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST, $this->ssl_veryfipeer);

        curl_setopt($this->curl, CURLOPT_POST, $this->is_post);
        curl_setopt($this->curl, CURLOPT_PUT, $this->is_put);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $this->postdata);
        curl_setopt($this->curl, CURLOPT_HEADER, $this->header);

        if ($this->is_delete)
            curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, "DELETE");

        return curl_exec($this->curl);
    }

    public function __destruct()
    {
        curl_close($this->curl);

        $this->postdata = null;

        $this->is_post = false;
        $this->is_put = false;
        $this->is_delete = false;
    }
}
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class My_Input extends CI_Input
{

    function __construct()
    {
        parent::__construct();

        $content_type = $this->get_request_header('Content-Type');

        if ($this->method('post') && stripos($content_type, 'application/json') !== FALSE &&
        ($postdata = $this->raw_post()) &&
        in_array($postdata[0], array('{', '[')))
        {
            $decoded_postdata = json_decode($postdata, true);
            if ((json_last_error() == JSON_ERROR_NONE))
            {
                $_POST = $decoded_postdata;
            }
        }
    }

    protected function raw_post()
    {
        return file_get_contents('php://input');
    }
}
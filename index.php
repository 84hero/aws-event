<?php

class AwsEvent
{

    protected $postObj = null;
    protected $error = 'Invalid request';

    protected $arnAction = [''];

    public function __construct()
    {
        $this->checkToken();

        if ($postStr = file_get_contents('php://input')) {
            $this->logging($postStr);
            $this->postObj = json_decode($postStr);
        }
    }

    protected function logging($str)
    {
        file_put_contents('logs/' . date("YmdHis") . '.log', $str, FILE_APPEND);
    }

    protected function checkToken()
    {
        if (strtolower(@$_GET['token']) !== strtolower(md5(ACCESS_TOKEN))) {
            $this->_return();
        }
    }

    public function response()
    {

        if (!$this->postObj->Type) {
            return false;
        }

        if (!method_exists(__CLASS__, $this->postObj->Type)) {
            return $this->error = 'method Invalid';
        }

        return call_user_func([$this, $this->postObj->Type]);
    }

    protected function _return()
    {
        exit($this->error);
    }

    public function __destruct()
    {
        $this->_return();
    }

    protected function Notification(){
    	if($this->postObj->TopicArn){
    		$sns = array_pop(explode(':',$this->postObj->TopicArn));

    		$method = '_return';
    		switch ($sns) {
    			case 'test-app-create':
    				$method = 'bindDevice';
    				break;
    			
    			default:
    				# code...
    				break;
    		}

    		return call_user_func([$this, $method]);
    	}
    }

    /**
     *
     * [SubscriptionConfirmation description]
     */
    protected function SubscriptionConfirmation()
    {
        if (!$this->postObj->Type) {
            return false;
        }

        return file_get_contents($this->postObj->SubscribeURL);
    }

    /**
     * 
     * [bindDevice 绑定设备]
     * @return [type] [description]
     */
    protected function bindDevice(){
    	return true;
    }

}

define("ACCESS_TOKEN", 'aws_sns_notify_token'); //fa7709419308696b5537849bd6dd656f
$Event = new AwsEvent();
$Event->response();


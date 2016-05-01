<?php namespace AwatBayazidi\Abzar;


class ResponseManager
{

	/* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */

	public static function makeResult($data, $message)
	{
		return self::make($data, $message);
	}

	public static function makeError($errorCode, $message, $data = array())
	{
		return self::make($data ,$errorCode, $message);
	}

	public static function make($data, $message, $errorCode = null)
	{
		$result = array();
		if(is_null($errorCode)){
			$result['flag'] = true;
			$result['response'] = 'success';
		}else{
			$error['flag'] = false;
			$result['response'] = 'failed';
			$error['code'] = $errorCode;
		}
		$result['message'] = $message;
		if(!empty($data))
			$result['data'] = $data;
		return $result;
	}
}
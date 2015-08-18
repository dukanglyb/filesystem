<?php

class JSONResponse extends Response{

	protected $snake = true;
	protected $envelope = true;

	public function __construct(){
		parent::__construct();
	}

	public function send($records, $error=false){

	        $response = $this->di->get('response');
		    $success = ($error) ? 'ERROR' : 'SUCCESS';
			$response->setHeader('X-Record-Count', count($records));
			$response->setHeader('X-Status', $success);
			$response->setJsonContent($records,JSON_NUMERIC_CHECK);
    		$response->send();
    		//return $this;
	}

	public function convertSnakeCase($snake){
		$this->snake = (bool) $snake;
		return $this;
	}

	public function useEnvelope($envelope){
		$this->envelope = (bool) $envelope;
		return $this;
	}

}

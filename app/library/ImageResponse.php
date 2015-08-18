<?php

class ImageResponse extends Response{

	protected $headers = true;

	public function __construct(){
		parent::__construct();
	}

	public function send($records){

		$response = $this->di->get('response');
		$response->setHeader('Expires', '0');
        $response->setContent($records);
        $response->send();
		return $this;
	}

}
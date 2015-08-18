<?php

use Phalcon\Mvc\Controller;

class ControllerBase extends Base
{
    protected function forward($uri)
    {
        $uriParts = explode('/', $uri);
        return $this->dispatcher->forward(
            array(
                'controller' => $uriParts[0],
                'action' => $uriParts[1]
            )
        );
    }

    public function initialize()
    {
    }
}
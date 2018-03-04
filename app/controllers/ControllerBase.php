<?php

use Phalcon\Mvc\Controller;

class ControllerBase extends Controller
{
    public function sendJson($data) {
        $this->view->disable();
        $this->response->setContentType('application/json', 'UTF-8');
        $this->response->setContent(json_encode($data));
        return $this->response;
    }
}

<?php

class IndexController extends ControllerBase
{

    public function initialize() {
        // $this->assets->setTargetPath("public/");
        // $this->assets->addJs("public/js/index/index.js");
    }

    public function indexAction()
    {
        $this->assets->addJs("public/js/index/index.js");
    }

    public function widgetAction() {

    }

    public function logOutAction() {
        $this->session->destroy();
        $this->response->redirect("/");
    }

}

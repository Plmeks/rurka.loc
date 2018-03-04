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
        if($this->session->has("email"))
            $this->view->email = $this->session->get("email");
        if($this->session->has("user_id"))
            $this->view->user_id = $this->session->get("user_id");
    }

    public function logOutAction() {
        $this->session->destroy();
        $this->response->redirect("/");
    }

}


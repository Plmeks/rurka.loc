<?php

class DashboardController extends ControllerBase
{
    public function initialize() {
        if(!$this->session->has("dashboard"))
            $this->response->redirect("/");
    }

    public function indexAction()
    {

    }

    public function addAction()
    {
        $this->assets->addCss("public/css/dashboard/add.css");
        $this->assets->addJs("public/js/dashboard/add.js");
    }

}


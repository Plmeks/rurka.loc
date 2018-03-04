<?php

class VkController extends ControllerBase
{
    public function initialize() {
        $this->view->disable();
    }

    public function indexAction()
    {

    }

    public function getCodeUrlAction() {
        $client_id = "6396314";
        $redirect_uri = "http://rurka.loc/vk/token";
        $scope = "docs,email";
        $vk = "https://oauth.vk.com/authorize?client_id=$client_id&display=page&redirect_uri=$redirect_uri&scope=$scope&response_type=code&v=5.73";

        return $this->sendJson(array("url" => $vk));
    }

    public function tokenAction() {
        $code = $this->request->get("code");

        if($code) {
            $tokenResponse = json_decode(file_get_contents("https://oauth.vk.com/access_token?client_id=6396314&client_secret=HVoyLSVCnj1z2ufFRHO9&redirect_uri=http://rurka.loc/vk/token&code=$code"));
            if($tokenResponse) {
                $this->session->set("email", $tokenResponse->email);
                $this->session->set("user_id", $tokenResponse->user_id);
                $this->response->redirect("/");
            }
        }
    }
}


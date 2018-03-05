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
         if(!$code) {
             throw new Exception("Ошибка: code из контакта не был передан");
             return;
         }

        $tokenResponse = json_decode(file_get_contents("https://oauth.vk.com/access_token?client_id=6396314&client_secret=HVoyLSVCnj1z2ufFRHO9&redirect_uri=http://rurka.loc/vk/token&code=$code"));
        if(!$tokenResponse) {
            throw new Exception("Ошибка: не был получен response_token");
            return;
        }

        $this->session->set("loggedIn", true);
        $this->session->set("email", $tokenResponse->email);
        $this->session->set("user_id", $tokenResponse->user_id);

        $user = new Users();
        $userExists = $user->findFirst([
            "conditions" => "email='$tokenResponse->email' AND user_id='$tokenResponse->user_id'"
        ]);

        if(!$userExists) {
            $success = $user->save([
                "user_id" => $tokenResponse->user_id,
                "email" => $tokenResponse->email
            ]);
        }

        $this->response->redirect("/");
    }
}

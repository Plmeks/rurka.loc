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

        $this->session->set("dashboard", true);
        $this->session->set("email", $tokenResponse->email);
        $this->session->set("user_id", $tokenResponse->user_id);
        $this->session->set("token", $tokenResponse->access_token);

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

    public function uploadStickerAction() {
        $upload_server = $this->getUploadServer();
        $upload_url = $upload_server->response->upload_url;
        $file = $this->request->get("file");

        if(!$upload_url) {
            throw new Exception("Ошибка: не был получен upload_url");
            return;
        }

        $vkFileResponse = $this->uploadFileVkServer($upload_url, $file);

        return $this->sendJson([$vkFileResponse]);
    }

    private function getUploadServer() {
        $token = $this->session->get("token");
        $user_id = $this->session->get("user_id");

        $uploadServer = json_decode(file_get_contents("https://api.vk.com/method/docs.getMessagesUploadServer?peer_id={$user_id}&type=graffiti&access_token={$token}&v=5.73"));
        if(!$uploadServer) {
            throw new Exception("Ошибка: не был получен uploadServer");
            return;
        }

        return $uploadServer;
    }

    private function uploadFileVkServer($upload_url, $file) {
        $curl = curl_init($upload_url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: multipart/form-data'));
        curl_setopt($curl, CURLOPT_POSTFIELDS, [
            'file' => new CURLFile($file, mime_content_type($file), basename($file))	
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }
}

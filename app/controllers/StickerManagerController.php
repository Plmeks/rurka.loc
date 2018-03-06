<?php

class StickerManagerController extends ControllerBase
{

    public function indexAction()
    {

    }

    public function createPackAction() {
        $pack = $this->request->get("pack");
        $user_id = $this->session->get("user_id");
        
        if(!$pack) {
            throw new Exception("No pack choosed");
            return;
        }
    
        if(!$user_id) {
            throw new Exception("Invalid user_id");
            return;
        }

        $uploadDir = "img/stickers/{$user_id}/{$pack}/";
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
            return $this->sendJson(["created" => true]);
        } else {
            return $this->sendJson(["created" => false]);
        }
    }

    public function createStickerAction() {
        $pack = $this->request->get("pack");
        $file = $this->request->getUploadedFiles()[0];
        $user_id = $this->session->get("user_id");
        
        if(!$pack) {
            throw new Exception("No pack");
            return;
        }
    
        if(!$user_id) {
            throw new Exception("No user_id");
            return;
        }

        if(!$file) {
            throw new Exception("No uploaded file");
            return;
        }

        $filePath = "img/stickers/{$user_id}/{$pack}/{$file->getName()}";
        if(!file_exists($filePath)) {
            move_uploaded_file($file->getTempName(), $filePath);
            //return $this->sendJson(["created" => true, "file" => $filePath]);
        } else {
            //return $this->sendJson(["created" => false, "file" => $filePath]);
        }

        $token = $this->session->get("token");
        $user_id = $this->session->get("user_id");

        $uploadServer = json_decode(file_get_contents("https://api.vk.com/method/docs.getUploadServer?group_id=161886453&type=graffiti&access_token=$token&v=5.73"));
        if(!$uploadServer) {
            throw new Exception("Ошибка: не был получен uploadServer");
            return;
        }

        $upload_url = $uploadServer->response->upload_url;
        $curl = curl_init($upload_url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: multipart/form-data'));
        // curl_setopt($curl, CURLOPT_POSTFIELDS, [
        //     'file' => new CURLFile("http://rurka.loc/public/img/stickers/22596081/123/file_8652540.png", $file->getType(), "file_8652540.png")	
        // ]);
        curl_setopt($curl, CURLOPT_SAFE_UPLOAD, false);
        curl_setopt($curl, CURLOPT_POSTFIELDS, [
            'file' => "@/http://rurka.loc/public/img/stickers/22596081/123/file_8652540.png"
        ]);
        $curlResp = curl_exec($curl);
        curl_close($curl);
        // $uploadFile = array('file' => '@/' .$filePath);
        // $uploadFile2 = [
        //     'name' => new \CurlFile($filePath, 'image/png', $file->getName())
        // ];
        // $curl = curl_init($upload_url);
        // curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        // curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        // curl_setopt($curl, CURLOPT_POST, true);
        // curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: multipart/form-data'));
        // // curl_setopt($curl, CURLOPT_SAFE_UPLOAD, true);
        // // curl_setopt($curl, CURLOPT_POSTFIELDS, $uploadFile);
        
        // curl_setopt($curl, CURLOPT_POSTFIELDS, $uploadFile2);
        
        // $response = curl_exec($curl);
        // curl_close($curl);

        return $this->sendJson([$upload_url]);
    }

}


<?php
namespace App\Graph;

class OneDriveController{

    private static $instance=null;
    private GraphApiCaller $caller;

    private function __construct() {
        GraphHelper::initializeGraphForUserAuth();
    }

    public static function getInstance(){
        if(OneDriveController::$instance==null){
            OneDriveController::$instance=new OneDriveController();
        }
        return OneDriveController::$instance;
    }

    public function createFirmFolder($firmName){
        $this->updateToken();
        return $this->caller->createFirmFolder($firmName);
    }

    public function deleteFirmFolder($itemName){
        $this->updateToken();
        return $this->caller->deleteFirmFolder($itemName);
    }

    public function deleteItemInFirm($firmName,$firmItem){
        $this->updateToken();
        return $this->caller->deleteItemInFirm($firmName,$firmItem);
    }

    public function getDownloadLinkFileInFirm($firmName,$firmItem){
        $this->updateToken();
        return $this->caller->getDownloadLinkFileInFirm($firmName,$firmItem);
    }

    public function getDownloadContentFileInFirm($firmName,$firmItem){
        $this->updateToken();
        return $this->caller->getDownloadContentFileInFirm($firmName,$firmItem);
    }
    public function uploadFileInFirm($firmName,$firmItem){
        $this->updateToken();
        return $this->caller->uploadFileInFirm($firmName,$firmItem);
    }


    

    private function updateToken(){
        
        $token=GraphHelper::getUserToken();

        $this->caller=new GraphApiCaller();
        $this->caller->initGraphApiCaller($token);
    }


}


?>
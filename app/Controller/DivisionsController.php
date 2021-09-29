<?php

class DivisionsController extends AppController {

    public function beforeRender() {
        parent::beforeRender();
        if ($this->Session->read("LoggedIn") != "1") {
            return $this->redirect(array('controller' => 'Login', 'action' => 'index'));
        }
    }

    public function index() {
        $ModuleID = "100";
        $GetModulePermissionsQuery = "EXEC BO_GetUserModuleRole '" . $this->Session->read('UserCode') . "' , '" . $ModuleID . "' ";
        $GetModulePermissionsResult = $this->Division->query($GetModulePermissionsQuery);
        if (count($GetModulePermissionsResult) > 0) {
            $ModuleRole = $GetModulePermissionsResult[0][0]['ModuleRole'];
            $UserRole = $GetModulePermissionsResult[0][0]['UserRole'];
        } else {
            $ModuleRole = "0";
            $UserRole = "0";
        }
        if (!$this->Division->CanView($ModuleRole, $UserRole)) {
            return $this->redirect(array('controller' => 'Login', 'action' => 'index'));
        }
       

        $this->set("CanAdd", $this->Division->CanAdd($ModuleRole, $UserRole));
        $this->set("CanEdit", $this->Division->CanEdit($ModuleRole, $UserRole));
        $this->set("CanDelete", $this->Division->CanDelete($ModuleRole, $UserRole));
        $this->set("CanApply", $this->Division->CanApply($ModuleRole, $UserRole));
    }

}

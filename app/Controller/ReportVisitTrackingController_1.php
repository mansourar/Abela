<?php

class ReportVisitTrackingController extends AppController {

    public function beforeRender() {
        parent::beforeRender();
        if ($this->Session->read("LoggedIn") != "1") {
            return $this->redirect(array('controller' => 'Login', 'action' => 'index'));
        }
    }

    public function index() {
        $ModuleID = "500";
        $GetModulePermissionsQuery = "EXEC BO_GetUserModuleRole '" . $this->Session->read('UserCode') . "' , '" . $ModuleID . "' ";
        $GetModulePermissionsResult = $this->ReportVisitTracking->query($GetModulePermissionsQuery);
        if (count($GetModulePermissionsResult) > 0) {
            $ModuleRole = $GetModulePermissionsResult[0][0]['ModuleRole'];
            $UserRole = $GetModulePermissionsResult[0][0]['UserRole'];
        } else {
            $ModuleRole = "0";
            $UserRole = "0";
        }
        if (!$this->ReportVisitTracking->CanView($ModuleRole, $UserRole)) {
            return $this->redirect(array('controller' => 'Login', 'action' => 'index'));
        }

        $this->set("CanAdd", $this->ReportVisitTracking->CanAdd($ModuleRole, $UserRole));
        $this->set("CanEdit", $this->ReportVisitTracking->CanEdit($ModuleRole, $UserRole));
        $this->set("CanDelete", $this->ReportVisitTracking->CanDelete($ModuleRole, $UserRole));
        $this->set("CanApply", $this->ReportVisitTracking->CanApply($ModuleRole, $UserRole));
    }

}

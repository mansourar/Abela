<?php


class ReportUsersAttendanceController  extends AppController {

    public function beforeRender() {
        parent::beforeRender();
        if ($this->Session->read("LoggedIn") != "1") {
            return $this->redirect(array('controller' => 'Login', 'action' => 'index'));
        }
    }

    public function index() {
        $ModuleID = "302";
        $GetModulePermissionsQuery = "EXEC BO_GetUserModuleRole '" . $this->Session->read('UserCode') . "' , '" . $ModuleID . "' ";
        $GetModulePermissionsResult = $this->ReportUsersAttendance->query($GetModulePermissionsQuery);
        if (count($GetModulePermissionsResult) > 0) {
            $ModuleRole = $GetModulePermissionsResult[0][0]['ModuleRole'];
            $UserRole = $GetModulePermissionsResult[0][0]['UserRole'];
        } else {
            $ModuleRole = "0";
            $UserRole = "0";
        }
        if (!$this->ReportUsersAttendance->CanView($ModuleRole, $UserRole)) {
            return $this->redirect(array('controller' => 'Login', 'action' => 'index'));
        }

        $this->set("CanAdd", $this->ReportUsersAttendance->CanAdd($ModuleRole, $UserRole));
        $this->set("CanEdit", $this->ReportUsersAttendance->CanEdit($ModuleRole, $UserRole));
        $this->set("CanDelete", $this->ReportUsersAttendance->CanDelete($ModuleRole, $UserRole));
        $this->set("CanApply", $this->ReportUsersAttendance->CanApply($ModuleRole, $UserRole));
    }

}

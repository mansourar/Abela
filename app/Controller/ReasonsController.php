<?php

class ReasonsController extends AppController {

    public function beforeRender() {
        parent::beforeRender();
        if ($this->Session->read("LoggedIn") != "1") {
            return $this->redirect(array('controller' => 'Login', 'action' => 'index'));
        }
    }

    public function index() {
        $ModuleID = "101";
        $GetModulePermissionsQuery = "EXEC BO_GetUserModuleRole '" . $this->Session->read('UserCode') . "' , '" . $ModuleID . "' ";
        $GetModulePermissionsResult = $this->Reason->query($GetModulePermissionsQuery);
        if (count($GetModulePermissionsResult) > 0) {
            $ModuleRole = $GetModulePermissionsResult[0][0]['ModuleRole'];
            $UserRole = $GetModulePermissionsResult[0][0]['UserRole'];
        } else {
            $ModuleRole = "0";
            $UserRole = "0";
        }
        if (!$this->Reason->CanView($ModuleRole, $UserRole)) {
            return $this->redirect(array('controller' => 'Login', 'action' => 'index'));
        }

        $TypeFilter = "";
        $this->set("CanAdd", $this->Reason->CanAdd($ModuleRole, $UserRole));
        $this->set("CanEdit", $this->Reason->CanEdit($ModuleRole, $UserRole));
        $this->set("CanDelete", $this->Reason->CanDelete($ModuleRole, $UserRole));
        $this->set("CanApply", $this->Reason->CanApply($ModuleRole, $UserRole));

        $Query = "SELECT * FROM ReasonTypes";
        $Res = $this->Reason->query($Query);
        for ($i = 0; $i < count($Res); $i++) {
            $TypeFilter .= $Res[$i][0]["TypeCode"] . ":" . $Res[$i][0]["TypeName"];
            if ($i < count($Res) - 1) {
                $TypeFilter .= ";";
            }
        }
        $this->set("TypeFilter", $TypeFilter);
    }

    public function GetReasonsData() {

        Configure::write('debug', 0);
        $this->layout = false;
        $this->autoRender = false;

        if ($this->Session->read("LoggedIn") != "1") {
            echo(json_decode("false"));
        }

        $page = $this->SecureInjectionlike($this->request->query['page']);
        $limit = $this->SecureInjectionlike($this->request->query['rows']);
        $sidx = $this->SecureInjectionlike($this->request->query['sidx']);
        $sord = $this->SecureInjectionlike($this->request->query['sord']);

        if (!$sidx) {
            $sidx = "ReasonCode";
        }

        $ReasonCode = $this->SecureInjectionlike($this->request->query('ReasonCode'));
        $ReasonName = $this->SecureInjectionlike($this->request->query('ReasonName'));
        $ReasonAltName = $this->SecureInjectionlike($this->request->query('ReasonAltName'));
        $Seq = $this->SecureInjectionlike($this->request->query('ReasonDisplaySequence'));
        $ReasonType = $this->SecureInjectionlike($this->request->query('ReasonTypeName'));
        $ReasonAffectStock = $this->SecureInjectionlike($this->request->query('ReasonAffectStock'));
        

        $CountQueryStr = "EXEC BO_GetReasonsListData "
                . "NULL,"
                . "NULL,"
                . "'" . $ReasonCode . "' ,"
                . "'" . $ReasonName . "' ,"
                . "'" . $ReasonAltName . "' ,"
                . "'" . $Seq . "' ,"
                . "'" . $ReasonType . "' ,"
                . "'" . $ReasonAffectStock . "' ,"
                . "'' ,"
                . "'' ";
        
        
        $CountQuery = $this->Reason->query($CountQueryStr);
        $Count = $CountQuery[0][0]['Count'];
        
        if ($Count > 0) {
            $total_pages = ceil($Count / $limit);
        } else {
            $total_pages = 0;
        }
        if ($page > $total_pages) {
            $page = $total_pages;
        }
        $start = $limit * $page - $limit;
        $GetReasonsQuery = "EXEC BO_GetReasonsListData "
                . "'" . $limit . "',"
                . "'" . $start . "',"
                . "'" . $ReasonCode . "' ,"
                . "'" . $ReasonName . "' ,"
                . "'" . $ReasonAltName . "' ,"
                . "'" . $Seq . "' ,"
                . "'" . $ReasonType . "' ,"
                . "'" . $ReasonAffectStock . "' ,"
                . "'" . $sidx . "' ,"
                . "'" . $sord . "' ";

        $GetReasonsResult = $this->Reason->query($GetReasonsQuery);

        $response->page = $page;
        $response->total = $total_pages;
        $response->records = $Count;
        for ($i = 0; $i < count($GetReasonsResult); $i++) {
            $row = $GetReasonsResult[$i][0];
            $response->rows[$i]['ReasonCode'] = $row['ReasonCode'];
            $response->rows[$i]['cell'] = array(
                $row['ReasonCode'],
                $row['ReasonName'],
                $row['ReasonAltName'],
                $row['ReasonDisplaySequence'],
                $row['ReasonAffectStock'],
                $row['ReasonTypeName']
            );
        }
        echo json_encode($response);
    }

    public function SetReasonsData() {
        Configure::write('debug', 2);
        $this->layout = false;
        $this->autoRender = false;

        if ($this->Session->read("LoggedIn") != "1") {
            echo(json_decode("false"));
        }

        $ReasonCode = $this->SecureInjectionlike($this->request->data('ReasonCode'));
        $ReasonName = $this->SecureInjectionlike($this->request->data('ReasonName'));
        $ReasonAltName = $this->SecureInjectionlike($this->request->data('ReasonAltName'));
        $Seq = $this->SecureInjectionlike($this->request->data('ReasonDisplaySequence'));
        $ReasonAffectStock = $this->SecureInjectionlike($this->request->data('ReasonAffectStock'));
        $ReasonType = $this->SecureInjectionlike($this->request->data('ReasonTypeName'));

        $InsertUpdate = "EXEC BO_SetReasonsListData "
                . "'" . $ReasonCode . "' ,"
                . "'" . $ReasonName . "' ,"
                . "'" . $ReasonAltName . "' ,"
                . "'" . $ReasonType . "' ,"
                . "'" . $ReasonAffectStock . "' ,"
                . "'" . $Seq . "'";
        
        $Result = $this->Reason->query($InsertUpdate);
    }

}

<?php

class NewCustomersController extends AppController {

    public function beforeRender() {
        parent::beforeRender();
        if ($this->Session->read("LoggedIn") != "1") {
            return $this->redirect(array('controller' => 'Login', 'action' => 'index'));
        }
    }

    public function index() {
        $ModuleID = "302";
        $GetModulePermissionsQuery = "EXEC BO_GetUserModuleRole '" . $this->Session->read('UserCode') . "' , '" . $ModuleID . "' ";
        $GetModulePermissionsResult = $this->NewCustomer->query($GetModulePermissionsQuery);
        if (count($GetModulePermissionsResult) > 0) {
            $ModuleRole = $GetModulePermissionsResult[0][0]['ModuleRole'];
            $UserRole = $GetModulePermissionsResult[0][0]['UserRole'];
        } else {
            $ModuleRole = "0";
            $UserRole = "0";
        }
        if (!$this->NewCustomer->CanView($ModuleRole, $UserRole)) {
            return $this->redirect(array('controller' => 'Login', 'action' => 'index'));
        }

        $this->set("CanAdd", $this->NewCustomer->CanAdd($ModuleRole, $UserRole));
        $this->set("CanEdit", $this->NewCustomer->CanEdit($ModuleRole, $UserRole));
        $this->set("CanDelete", $this->NewCustomer->CanDelete($ModuleRole, $UserRole));
        $this->set("CanApply", $this->NewCustomer->CanApply($ModuleRole, $UserRole));
    }

    public function GetNewCustomersData() {

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
            $sidx = "ClientCode";
        }

        $ClientCode = $this->SecureInjectionlike($this->request->query('ClientCode'));
        $UserCode = $this->SecureInjectionlike($this->request->query('UserCode'));
        $UserName = $this->SecureInjectionlike($this->request->query('UserName'));
        $ClientName = $this->SecureInjectionlike($this->request->query('ClientName'));
        $specialty = $this->SecureInjectionlike($this->request->query('specialty'));
        $Classification = $this->SecureInjectionlike($this->request->query('Classification'));
        $Frequency = $this->SecureInjectionlike($this->request->query('Frequency'));
        $Date = $this->SecureInjectionlike($this->request->query('Date'));
        $Address = $this->SecureInjectionlike($this->request->query('ClientAddress'));
        $Phone = $this->SecureInjectionlike($this->request->query('Phone'));
        $Area = $this->SecureInjectionlike($this->request->query('Area'));
        $Email = $this->SecureInjectionlike($this->request->query('Email'));
        $Notes = $this->SecureInjectionlike($this->request->query('Notes'));
        $IsPharmacy = $this->SecureInjectionlike($this->request->query('IsPharmacy'));
        $Status = $this->SecureInjectionlike($this->request->query('Status'));

        $CountQueryStr = "EXEC BO_GetNewCustomersListData "
                . "NULL,"
                . "NULL,"
                . "'" . $ClientCode . "' ,"
                . "'" . $UserCode . "' ,"
                . "'" . $UserName . "' ,"
                . "'" . $ClientName . "' ,"
                . "'" . $specialty . "' ,"
                . "'" . $Classification . "' ,"
                . "'" . $Frequency . "' ,"
                . "'" . $Date . "' ,"
                . "'" . $Address . "' ,"
                . "'" . $Phone . "' ,"
                . "'" . $Area . "' ,"
                . "'" . $Email . "' ,"
                . "'" . $Notes . "' ,"
                . "'" . $IsPharmacy . "' ,"
                . "'" . $Status . "' ,"
                . "'' ,"
                . "'' ";

        $CountQuery = $this->NewCustomer->query($CountQueryStr);
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
        $GetCustomersQuery = "EXEC BO_GetNewCustomersListData "
                . "'" . $limit . "',"
                . "'" . $start . "',"
                . "'" . $ClientCode . "' ,"
                . "'" . $UserCode . "' ,"
                . "'" . $UserName . "' ,"
                . "'" . $ClientName . "' ,"
                . "'" . $specialty . "' ,"
                . "'" . $Classification . "' ,"
                . "'" . $Frequency . "' ,"
                . "'" . $Date . "' ,"
                . "'" . $Address . "' ,"
                . "'" . $Phone . "' ,"
                . "'" . $Area . "' ,"
                . "'" . $Email . "' ,"
                . "'" . $Notes . "' ,"
                . "'" . $IsPharmacy . "' ,"
                . "'" . $Status . "' ,"
                . "'" . $sidx . "' ,"
                . "'" . $sord . "' ";

        $GetCustomersResult = $this->NewCustomer->query($GetCustomersQuery);

        $response->page = $page;
        $response->total = $total_pages;
        $response->records = $Count;
        for ($i = 0; $i < count($GetCustomersResult); $i++) {
            $row = $GetCustomersResult[$i][0];
            $response->rows[$i]['ClientCode'] = $row['ClientCode'];
            $response->rows[$i]['cell'] = array(
                $row['ClientCode'],
                $row['UserCode'],
                $row['UserName'],
                $row['ClientName'],
                $row['Specialty'],
                $row['Classification'],
                $row['Freq'],
                $row['Date'],
                $row['ClientAddress'],
                $row['Phone'],
                $row['Area'],
                $row['Email'],
                $row['Notes'],
                $row['IsPharmacy'],
                $row['Status']
            );
        }
        echo json_encode($response);
    }

    public function ApproveNewCustomer() {

        Configure::write('debug', 0);
        $this->layout = false;
        $this->autoRender = false;
        $ClientCode = $this->SecureInjectionlike($this->request->query('ClientCode'));
        if ($this->Session->read("LoggedIn") != "1") {
            echo(json_decode("false"));
        }
        $ApproveQuery = "EXEC BO_NewCustomers_Approve '" . $ClientCode . "' ";
        $Res = $this->NewCustomer->query($ApproveQuery);
        echo($Res[0][0]["Result"] );
    }

    public function RejectNewCustomer() {

        Configure::write('debug', 0);
        $this->layout = false;
        $this->autoRender = false;
        $ClientCode = $this->SecureInjectionlike($this->request->query('ClientCode'));
        if ($this->Session->read("LoggedIn") != "1") {
            echo(json_decode("false"));
        }
        $RejectQuery = "EXEC BO_NewCustomers_Reject '" . $ClientCode . "' ";
        $Res = $this->NewCustomer->query($RejectQuery);

        echo($Res[0][0]["Result"] );
    }

}

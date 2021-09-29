<?php

class ItemsController extends AppController {

    public function beforeRender() {
        parent::beforeRender();
        if ($this->Session->read("LoggedIn") != "1") {
            return $this->redirect(array('controller' => 'Login', 'action' => 'index'));
        }
    }

    public function index() {
        $ModuleID = "400";
        $GetModulePermissionsQuery = "EXEC BO_GetUserModuleRole '" . $this->Session->read('UserCode') . "' , '" . $ModuleID . "' ";
        $GetModulePermissionsResult = $this->Item->query($GetModulePermissionsQuery);
        if (count($GetModulePermissionsResult) > 0) {
            $ModuleRole = $GetModulePermissionsResult[0][0]['ModuleRole'];
            $UserRole = $GetModulePermissionsResult[0][0]['UserRole'];
        } else {
            $ModuleRole = "0";
            $UserRole = "0";
        }
        if (!$this->Item->CanView($ModuleRole, $UserRole)) {
            return $this->redirect(array('controller' => 'Login', 'action' => 'index'));
        }

        $this->set("CanAdd", $this->Item->CanAdd($ModuleRole, $UserRole));
        $this->set("CanEdit", $this->Item->CanEdit($ModuleRole, $UserRole));
        $this->set("CanDelete", $this->Item->CanDelete($ModuleRole, $UserRole));
        $this->set("CanApply", $this->Item->CanApply($ModuleRole, $UserRole));
    }

    public function GetItemsData() {

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
            $sidx = "ItemCode";
        }

        $ItemCode = $this->SecureInjectionlike($this->request->query('ItemCode'));
        $ItemName = $this->SecureInjectionlike($this->request->query('ItemName'));
        $ItemAltName = $this->SecureInjectionlike($this->request->query('ItemAltName'));
        $ItemStatus = $this->SecureInjectionlike($this->request->query('ItemStatus'));
        

        $CountQueryStr = "EXEC BO_GetItemsListData "
                . "NULL,"
                . "NULL,"
                . "'" . $ItemCode . "' ,"
                . "'" . $ItemName . "' ,"
                . "'" . $ItemAltName . "' ,"
                . "'" . $ItemStatus . "' ,"
                . "'' ,"
                . "'' ";

        $CountQuery = $this->Item->query($CountQueryStr);
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
        $GetItemsQuery = "EXEC BO_GetItemsListData "
                . "'" . $limit . "',"
                . "'" . $start . "',"
                . "'" . $ItemCode . "' ,"
                . "'" . $ItemName . "' ,"
                . "'" . $ItemAltName . "' ,"
                . "'" . $ItemStatus . "' ,"
                . "'" . $sidx . "' ,"
                . "'" . $sord . "' ";

        $GetItemsResult = $this->Item->query($GetItemsQuery);

        $response->page = $page;
        $response->total = $total_pages;
        $response->records = $Count;
        for ($i = 0; $i < count($GetItemsResult); $i++) {
            $row = $GetItemsResult[$i][0];
            $response->rows[$i]['ItemCode'] = $row['ItemCode'];
            $response->rows[$i]['cell'] = array(
                $row['ItemCode'],
                $row['ItemName'],
                $row['ItemAltName'],
                $row['ItemStatus'],
            );
        }
        echo json_encode($response);
    }

}

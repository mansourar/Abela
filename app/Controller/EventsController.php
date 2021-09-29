<?php

class EventsController extends AppController {

    public function beforeRender() {
        parent::beforeRender();
        if ($this->Session->read("LoggedIn") != "1") {
            return $this->redirect(array('controller' => 'Login', 'action' => 'index'));
        }
    }

    public function index() {
        $ModuleID = "102";
        $GetModulePermissionsQuery = "EXEC BO_GetUserModuleRole '" . $this->Session->read('UserCode') . "' , '" . $ModuleID . "' ";
        $GetModulePermissionsResult = $this->Event->query($GetModulePermissionsQuery);
        if (count($GetModulePermissionsResult) > 0) {
            $ModuleRole = $GetModulePermissionsResult[0][0]['ModuleRole'];
            $UserRole = $GetModulePermissionsResult[0][0]['UserRole'];
        } else {
            $ModuleRole = "0";
            $UserRole = "0";
        }
        if (!$this->Event->CanView($ModuleRole, $UserRole)) {
            return $this->redirect(array('controller' => 'Login', 'action' => 'index'));
        }

        $this->set("CanAdd", $this->Event->CanAdd($ModuleRole, $UserRole));
        $this->set("CanEdit", $this->Event->CanEdit($ModuleRole, $UserRole));
        $this->set("CanDelete", $this->Event->CanDelete($ModuleRole, $UserRole));
        $this->set("CanApply", $this->Event->CanApply($ModuleRole, $UserRole));

        $CurrenciesFilter = "";
        $Query = "SELECT * FROM Currencies";
        $Res = $this->Event->query($Query);
        for ($i = 0; $i < count($Res); $i++) {
            $CurrenciesFilter .= $Res[$i][0]["CurrencyCode"] . ":" . $Res[$i][0]["CurrencySymbol"];
            if ($i < count($Res) - 1) {
                $CurrenciesFilter .= ";";
            }
        }
        $this->set("Currencies", $CurrenciesFilter);
    }

    public function GetEventsData() {

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
            $sidx = "EventCode";
        }

        $EventCode = $this->SecureInjectionlike($this->request->query('EventCode'));
        $EventName = $this->SecureInjectionlike($this->request->query('EventName'));
        $EventLocation = $this->SecureInjectionlike($this->request->query('EventLocation'));
        $EventDate = $this->SecureInjectionlike($this->request->query('EventDate'));
        $Presenters = $this->SecureInjectionlike($this->request->query('Presenters'));
        $RegistrationDate = $this->SecureInjectionlike($this->request->query('RegistrationDate'));
        $ParticipantsNumber = $this->SecureInjectionlike($this->request->query('ParticipantsNumber'));
        $Expenses = $this->SecureInjectionlike($this->request->query('Expenses'));
        $Currency = $this->SecureInjectionlike($this->request->query('Currency'));
        $ExpectCost = $this->SecureInjectionlike($this->request->query('ExpectCost'));
        $AttendanceCost = $this->SecureInjectionlike($this->request->query('AttendanceCost'));
        $ActualCost = $this->SecureInjectionlike($this->request->query('ActualCost'));

        $CountQueryStr = "EXEC BO_GetEventsListData "
                . "NULL,"
                . "NULL,"
                . "'" . $EventCode . "' ,"
                . "'" . $EventName . "' ,"
                . "'" . $EventLocation . "' ,"
                . "'" . $EventDate . "' ,"
                . "'" . $Presenters . "' ,"
                . "'" . $RegistrationDate . "' ,"
                . "'" . $ParticipantsNumber . "' ,"
                . "'" . $Expenses . "' ,"
                . "'" . $Currency . "' ,"
                . "'" . $ExpectCost . "' ,"
                . "'" . $AttendanceCost . "' ,"
                . "'" . $ActualCost . "' ,"
                . "'' ,"
                . "'' ";

        $CountQuery = $this->Event->query($CountQueryStr);
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
        $GetReasonsQuery = "EXEC BO_GetEventsListData "
                . "'" . $limit . "',"
                . "'" . $start . "',"
                . "'" . $EventCode . "' ,"
                . "'" . $EventName . "' ,"
                . "'" . $EventLocation . "' ,"
                . "'" . $EventDate . "' ,"
                . "'" . $Presenters . "' ,"
                . "'" . $RegistrationDate . "' ,"
                . "'" . $ParticipantsNumber . "' ,"
                . "'" . $Expenses . "' ,"
                . "'" . $Currency . "' ,"
                . "'" . $ActualCost . "' ,"
                . "'" . $AttendanceCost . "' ,"
                . "'" . $ExpectCost . "' ,"
                . "'" . $sidx . "' ,"
                . "'" . $sord . "' ";

        $GetReasonsResult = $this->Event->query($GetReasonsQuery);

        $response->page = $page;
        $response->total = $total_pages;
        $response->records = $Count;
        for ($i = 0; $i < count($GetReasonsResult); $i++) {
            $row = $GetReasonsResult[$i][0];
            $response->rows[$i]['EventID'] = $row['EventID'];
            $response->rows[$i]['cell'] = array(
                $row['EventID'],
                $row['EventName'],
                $row['EventLocation'],
                $row['EventDate'],
                $row['Presenters'],
                $row['RegistrationDate'],
                $row['ParticipantsNumber'],
                $row['Expenses'],
                $row['CurrencyCode'],
                $row['ExpectCost'],
                $row['AttendanceCost'],
                $row['ActualCost']
            );
        }
        echo json_encode($response);
    }

    public function SetEventsData() {
        Configure::write('debug', 0);
        $this->layout = false;
        $this->autoRender = false;

        if ($this->Session->read("LoggedIn") != "1") {
            echo(json_decode("false"));
        }

        $EventCode = $this->SecureInjectionlike($this->request->data('EventCode'));
        $EventName = $this->SecureInjectionlike($this->request->data('EventName'));
        $EventLocation = $this->SecureInjectionlike($this->request->data('EventLocation'));
        $EventDate = $this->SecureInjectionlike($this->request->data('EventDate'));
        $Presenters = $this->SecureInjectionlike($this->request->data('Presenters'));
        $RegistrationDate = $this->SecureInjectionlike($this->request->data('RegistrationDate'));
        $ParticipantsNumber = $this->SecureInjectionlike($this->request->data('ParticipantsNumber'));
        $Expenses = $this->SecureInjectionlike($this->request->data('Expenses'));
        $Currency = $this->SecureInjectionlike($this->request->data('Currency'));
        $ExpectCost = $this->SecureInjectionlike($this->request->data('ExpectCost'));
        $AttendanceCost = $this->SecureInjectionlike($this->request->data('AttendanceCost'));
        $ActualCost = $this->SecureInjectionlike($this->request->data('ActualCost'));

        $InsertUpdate = "EXEC BO_SetEvents "
                . "'" . $EventCode . "' ,"
                . "'" . $EventName . "' ,"
                . "'" . $EventLocation . "' ,"
                . "'" . $EventDate . "' ,"
                . "'" . $Presenters . "' ,"
                . "'" . $RegistrationDate . "' ,"
                . "'" . $ParticipantsNumber . "' ,"
                . "'" . $Expenses . "' ,"
                . "'" . $Currency . "' ,"
                . "'" . $ActualCost . "' ,"
                . "'" . $AttendanceCost . "' ,"
                . "'" . $ExpectCost . "' ";

        $Result = $this->Event->query($InsertUpdate);
    }

    public function DelEventsData() {
        Configure::write('debug', 0);
        $this->layout = false;
        $this->autoRender = false;

        if ($this->Session->read("LoggedIn") != "1") {
            echo(json_decode("false"));
        }

        $EventCode = $this->SecureInjectionlike($this->request->query('EventCode'));

        $DelQ = "EXEC BO_DelEventFromList "
                . "'" . $EventCode . "'";

        $Result = $this->Event->query($DelQ);
    }

    public function GetEventAssignments() {
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
            $sidx = "AssignmentCode";
        }

        $EventCode = $this->SecureInjectionlike($this->request->query('EventCode'));
        $AssignmentType = $this->SecureInjectionlike($this->request->query('AssignmentType'));

        $AssignmentCode = $this->SecureInjectionlike($this->request->query('AssignmentCode'));
        $AssignmentDescription = $this->SecureInjectionlike($this->request->query('AssignmentDescription'));

        if ($EventCode == "false") {
            $EventCode = '0';
        }

        $CountQueryStr = "EXEC BO_GetEventsAssignmentsList "
                . "NULL,"
                . "NULL,"
                . "'" . $EventCode . "' ,"
                . "'" . $AssignmentType . "' ,"
                . "'" . $AssignmentCode . "' ,"
                . "'" . $AssignmentDescription . "' ,"
                . "'" . $this->Session->read("UserCode") . "' ,"
                . "'' ,"
                . "'' ";

        $CountQuery = $this->Event->query($CountQueryStr);
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
        $GetAssignemntsQuery = "EXEC BO_GetEventsAssignmentsList "
                . "'" . $limit . "',"
                . "'" . $start . "',"
                . "'" . $EventCode . "' ,"
                . "'" . $AssignmentType . "' ,"
                . "'" . $AssignmentCode . "' ,"
                . "'" . $AssignmentDescription . "' ,"
                . "'" . $this->Session->read("UserCode") . "' ,"
                . "'" . $sidx . "' ,"
                . "'" . $sord . "' ";

        $GetAssignemntsResult = $this->Event->query($GetAssignemntsQuery);

        $response->page = $page;
        $response->total = $total_pages;
        $response->records = $Count;
        for ($i = 0; $i < count($GetAssignemntsResult); $i++) {
            $row = $GetAssignemntsResult[$i][0];
            $response->rows[$i]['RowID'] = $row['RowID'];
            $response->rows[$i]['cell'] = array(
                $row['RowID'],
                $row['AssignmentCode'],
                $row['AssignmentName'],
                $row['Type'],
                $row['Assigned'],
            );
        }
        echo json_encode($response);
    }

    public function SetEventAssignments() {
        Configure::write('debug', 0);
        $this->layout = false;
        $this->autoRender = false;
        if ($this->Session->read("LoggedIn") != "1") {
            echo(json_decode("false"));
        }
        $EventCode = $this->SecureInjectionlike($this->request->query('EventCode'));
        $AssignmentType = $this->SecureInjectionlike($this->request->query('AssignmentType'));
        $AssignmentCode = $this->SecureInjectionlike($this->request->query('AssignmentCode'));
        $Action = $this->SecureInjectionlike($this->request->query('Action'));
        $AssignQuery = "EXEC BO_SetEventAssignments '" . $EventCode . "' , '" . $AssignmentType . "', '" . $AssignmentCode . "' , '" . $Action . "' ";
        $Result = $this->Event->query($AssignQuery);
    }

}

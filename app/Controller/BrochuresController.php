<?php

class BrochuresController extends AppController {

    public function beforeRender() {
        parent::beforeRender();
        if ($this->Session->read("LoggedIn") != "1") {
            return $this->redirect(array('controller' => 'Login', 'action' => 'index'));
        }
    }

    public function index() {
        $ModuleID = "700";
        $GetModulePermissionsQuery = "EXEC BO_GetUserModuleRole '" . $this->Session->read('UserCode') . "' , '" . $ModuleID . "' ";
        $GetModulePermissionsResult = $this->Brochure->query($GetModulePermissionsQuery);
        if (count($GetModulePermissionsResult) > 0) {
            $ModuleRole = $GetModulePermissionsResult[0][0]['ModuleRole'];
            $UserRole = $GetModulePermissionsResult[0][0]['UserRole'];
        } else {
            $ModuleRole = "0";
            $UserRole = "0";
        }
        if (!$this->Brochure->CanView($ModuleRole, $UserRole)) {
            return $this->redirect(array('controller' => 'Login', 'action' => 'index'));
        }
        $this->set("CanAdd", $this->Brochure->CanAdd($ModuleRole, $UserRole));
        $this->set("CanEdit", $this->Brochure->CanEdit($ModuleRole, $UserRole));
        $this->set("CanDelete", $this->Brochure->CanDelete($ModuleRole, $UserRole));
        $this->set("CanApply", $this->Brochure->CanApply($ModuleRole, $UserRole));
    }

    public function AddBrochure() {
        $ModuleID = "700";
        $GetModulePermissionsQuery = "EXEC BO_GetUserModuleRole '" . $this->Session->read('UserCode') . "' , '" . $ModuleID . "' ";
        $GetModulePermissionsResult = $this->Brochure->query($GetModulePermissionsQuery);
        if (count($GetModulePermissionsResult) > 0) {
            $ModuleRole = $GetModulePermissionsResult[0][0]['ModuleRole'];
            $UserRole = $GetModulePermissionsResult[0][0]['UserRole'];
        } else {
            $ModuleRole = "0";
            $UserRole = "0";
        }
        if (!$this->Brochure->CanView($ModuleRole, $UserRole)) {
            return $this->redirect(array('controller' => 'Login', 'action' => 'index'));
        }
        if (!$this->Brochure->CanAdd($ModuleRole, $UserRole)) {
            return $this->redirect(array('controller' => 'Login', 'action' => 'index'));
        }

        $this->set("CanAdd", $this->Brochure->CanAdd($ModuleRole, $UserRole));
        $this->set("CanEdit", $this->Brochure->CanEdit($ModuleRole, $UserRole));
        $this->set("CanDelete", $this->Brochure->CanDelete($ModuleRole, $UserRole));
        $this->set("CanApply", $this->Brochure->CanApply($ModuleRole, $UserRole));

        $ErrorByFileCreation = "";
        if (isset($this->request->data['BrochureName'])) {
            if (isset($_FILES['FileToUpload'])) {
                $file = $_FILES['FileToUpload'];
                $FileName = $_FILES['FileToUpload']['name'];
                $Tmp_name = $_FILES['FileToUpload']['tmp_name'];
                $Size = $_FILES['FileToUpload']['size'];
                $File_error = $_FILES['FileToUpload']['error'];

                $File_ext = explode('.', $FileName);
                $File_ext = strtolower(end($File_ext));
                $Allowed = array('pdf', 'jpeg', 'jpg', 'png');

                if (in_array($File_ext, $Allowed)) {
                    if ($File_error === 0) {
                        if ($Size <= 6097152) {
                            $FileNameNew = $FileName;
                            $Destination = 'H:\\Web\\Abela\\app\\webroot\\brochures\\' . $FileNameNew;
                            if (move_uploaded_file($Tmp_name, $Destination)) {
                                $Name = $this->SecureInjection($this->request->data("BrochureName"));
                                $ItemCode = $this->request->data("SelectedItem");
                                $GetBroCodeQuery = "INSERT INTO Brochures (BrochureCode , BrochureName, BrochureAltName,BrochureExtension,ItemCode, StampDate)VALUES( "
                                        . "(SELECT ISNULL(MAX(CAST(BrochureCode AS INT) ) , '0' ) + 1 FROM Brochures)  , '" . $Name . "' , '" . $FileNameNew . "','" . $File_ext . "' ,'" . $ItemCode . "' ,GETDATE() )";
                                $this->Brochure->query($GetBroCodeQuery);
                            } else {
                                $ErrorByFileCreation = "Cannot Save Uploaded File !";
                            }
                        } else {
                            $ErrorByFileCreation = "File Mus Be Lower Than 5 Mb ! ";
                        }
                    } else {
                        $ErrorByFileCreation = " An Error Has Occured While Uploading Your File ! ";
                    }
                } else {
                    $ErrorByFileCreation = "File Cannot Be Uploaded : Invalid File Type !";
                }
            }
            $this->set('UploadError', $ErrorByFileCreation);
        }

        $this->set('UploadError', $ErrorByFileCreation);
    }

    public function GetBrochuresData() {

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
            $sidx = "BrochureCode";
        }

        $BrochureCode = $this->SecureInjectionlike($this->request->query('BrochureCode'));
        $BrochureName = $this->SecureInjectionlike($this->request->query('BrochureName'));
        $ItemCode = $this->SecureInjectionlike($this->request->query('ItemCode'));
        $ItemName = $this->SecureInjectionlike($this->request->query('ItemName'));

        $CountQueryStr = "EXEC BO_GetBrochuresListData "
                . "NULL,"
                . "NULL,"
                . "'" . $BrochureCode . "' ,"
                . "'" . $BrochureName . "' ,"
                . "'" . $ItemCode . "' ,"
                . "'" . $ItemName . "' ,"
                . "'' ,"
                . "'' ";

        $CountQuery = $this->Brochure->query($CountQueryStr);
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
        $GetBrochuresQuery = "EXEC BO_GetBrochuresListData "
                . "'" . $limit . "',"
                . "'" . $start . "',"
                . "'" . $BrochureCode . "' ,"
                . "'" . $BrochureName . "' ,"
                . "'" . $ItemCode . "' ,"
                . "'" . $ItemName . "' ,"
                . "'" . $sidx . "' ,"
                . "'" . $sord . "' ";

        $GetBrochuresResult = $this->Brochure->query($GetBrochuresQuery);

        $response->page = $page;
        $response->total = $total_pages;
        $response->records = $Count;
        for ($i = 0; $i < count($GetBrochuresResult); $i++) {
            $row = $GetBrochuresResult[$i][0];
            $response->rows[$i]['BrochureCode'] = $row['BrochureCode'];
            $response->rows[$i]['cell'] = array(
                $row['BrochureCode'],
                $row['BrochureName'],
                $row['ItemCode'],
                $row['ItemName'],
                '<a href="../brochures/' . $row['BrochureAltName'] . '" target="_blank">View</a>'
            );
        }
        echo json_encode($response);
    }

    public function SetBrochuresData() {
        Configure::write('debug', 2);
        $this->layout = false;
        $this->autoRender = false;

        if ($this->Session->read("LoggedIn") != "1") {
            echo(json_decode("false"));
        }

        $BrochureCode = $this->SecureInjectionlike($this->request->data('BrochureCode'));
        $BrochureName = $this->SecureInjectionlike($this->request->data('BrochureName'));
        $ItemCode = $this->SecureInjectionlike($this->request->data('ItemCode'));
        $ItemName = $this->SecureInjectionlike($this->request->data('ItemName'));

        $InsertUpdate = "EXEC BO_SetBrochuresListData "
                . "'" . $BrochureCode . "' ,"
                . "'" . $BrochureName . "' ,"
                . "'" . $ItemCode . "' ,"
                . "'" . $ItemName . "'";

        $Result = $this->Brochure->query($InsertUpdate);
    }

    public function DelBrochuresData() {
        Configure::write('debug', 2);
        $this->layout = false;
        $this->autoRender = false;

        $ModuleID = "700";
        $GetModulePermissionsQuery = "EXEC BO_GetUserModuleRole '" . $this->Session->read('UserCode') . "' , '" . $ModuleID . "' ";
        $GetModulePermissionsResult = $this->Brochure->query($GetModulePermissionsQuery);
        if (count($GetModulePermissionsResult) > 0) {
            $ModuleRole = $GetModulePermissionsResult[0][0]['ModuleRole'];
            $UserRole = $GetModulePermissionsResult[0][0]['UserRole'];
        } else {
            $ModuleRole = "0";
            $UserRole = "0";
        }

        if (!$this->Brochure->CanDelete($ModuleRole, $UserRole)) {
            echo(json_decode("false"));
            return;
        }
        if ($this->Session->read("LoggedIn") != "1") {
            echo(json_decode("false"));
            return;
        }


        $BrochureCode = $this->SecureInjectionlike($this->request->query('BrochureCode'));

        $DeleteQuery = "EXEC BO_DelBrochuresListData "
                . "'" . $BrochureCode . "'";
        $Result = $this->Brochure->query($DeleteQuery);
    }

    public function GetBrochureAssignments() {
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

        $BrochureCode = $this->SecureInjectionlike($this->request->query('BrochureCode'));
        $AssignmentType = $this->SecureInjectionlike($this->request->query('AssignmentType'));

        $AssignmentCode = $this->SecureInjectionlike($this->request->query('AssignmentCode'));
        $AssignmentDescription = $this->SecureInjectionlike($this->request->query('AssignmentDescription'));

        if ($BrochureCode == "false") {
            $BrochureCode = '0';
        }

        $CountQueryStr = "EXEC BO_GetBrochuresAssignmentsList "
                . "NULL,"
                . "NULL,"
                . "'" . $BrochureCode . "' ,"
                . "'" . $AssignmentType . "' ,"
                . "'" . $AssignmentCode . "' ,"
                . "'" . $AssignmentDescription . "' ,"
                . "'" . $this->Session->read("UserCode") . "' ,"
                . "'' ,"
                . "'' ";

        $CountQuery = $this->Brochure->query($CountQueryStr);
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
        $GetAssignemntsQuery = "EXEC BO_GetBrochuresAssignmentsList "
                . "'" . $limit . "',"
                . "'" . $start . "',"
                . "'" . $BrochureCode . "' ,"
                . "'" . $AssignmentType . "' ,"
                . "'" . $AssignmentCode . "' ,"
                . "'" . $AssignmentDescription . "' ,"
                . "'" . $this->Session->read("UserCode") . "' ,"
                . "'" . $sidx . "' ,"
                . "'" . $sord . "' ";

        $GetAssignemntsResult = $this->Brochure->query($GetAssignemntsQuery);

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

    public function SetBrochureAssignments() {
        Configure::write('debug', 2);
        $this->layout = false;
        $this->autoRender = false;
        if ($this->Session->read("LoggedIn") != "1") {
            echo(json_decode("false"));
        }
        $BrochureCode = $this->SecureInjectionlike($this->request->query('BrochureCode'));
        $AssignmentType = $this->SecureInjectionlike($this->request->query('AssignmentType'));
        $AssignmentCode = $this->SecureInjectionlike($this->request->query('AssignmentCode'));
        $Action = $this->SecureInjectionlike($this->request->query('Action'));
        $AssignQuery = "EXEC BO_SetBrochureAssignments '" . $BrochureCode . "' , '" . $AssignmentType . "', '" . $AssignmentCode . "' , '" . $Action . "' ";
        $Result = $this->Brochure->query($AssignQuery);
    }

    public function SetBrochure() {
        Configure::write('debug', 2);
        $this->layout = false;
        $this->autoRender = false;

        if ($this->Session->read("LoggedIn") != "1") {
            echo(json_decode("false"));
        }

        $BrochureCode = $this->SecureInjectionlike($this->request->data('id'));
        $BrochureName = $this->SecureInjectionlike($this->request->data('BrochureName'));

        $Query = "UPDATE Brochures SET BrochureName = '" . $BrochureName . "' WHERE BrochureCode = '" . $BrochureCode . "' ";

        $Result = $this->Brochure->query($Query);
    }

}

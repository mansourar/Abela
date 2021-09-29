<?php

class LoginController extends AppController {

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Session->destroy();
        $this->layout = "default";
    }

    public function beforeRender() {
        parent::beforeRender();
        $this->Session->destroy();
        $this->layout = "default";
    }

    public function index() {
        $this->Session->destroy();
    }

    public function AutheticateUser() {
        Configure::write('debug', 2);
        $this->layout = false;
        $this->autoRender = false;

        $UserName = $this->SecureInjection($this->request->data['UserName']);
        $Password = $this->SecureInjection($this->request->data['Password']);
        $AuthenticateQuery = "EXEC BO_AuthenticateUser '" . $UserName . "' , '" . $Password . "' ";
        $AuthenticateResult = $this->Login->query($AuthenticateQuery);
        if (count($AuthenticateResult) != 1) {
            echo(json_encode("false"));
            die();
        }
        
        $this->Session->write("CanEditProfile", "N");
        $this->Session->write("CanEditSettings", "Y");
        $this->Session->write("LoggedIn", "1");
        $this->Session->write("TypeID", $AuthenticateResult[0][0]['UserTypeID']);
        $this->Session->write("UserCode", $AuthenticateResult[0][0]['UserCode']);
        $this->Session->write("UserFirstName", $AuthenticateResult[0][0]['UserName']);
        $this->Session->write("UserLastName", "");
        $this->Session->write("UserMiddleName", "");
        $this->Session->write("TypeName", $AuthenticateResult[0][0]['UserTypeDescription']);
        $this->Session->write("ProfilePicture", "");
        $this->Session->write("MenuString", $this->_GetMenuString());
        $this->Session->write("ProfilePicture", "../images/img.png");
        echo(json_encode("true"));
    }

    public function _GetMenuString() {

        $result = "";
        $MenuQuery = "EXEC BO_GetUserModulesParent '" . $this->SecureInjection($this->Session->read('UserCode')) . "' ";
        $MenuResult = $this->Login->query($MenuQuery);

        for ($i = 0; $i < count($MenuResult); $i++) {
            $result .= "<li id='" . $MenuResult[$i][0]['ModuleName'] . "_li' ><a><i class='" . $MenuResult[$i][0]['CssClass'] . "'></i> " . $MenuResult[$i][0]['ModuleName'] . " <span class='fa fa-chevron-down'></span></a>";
            $result .= "<ul id = '" . $MenuResult[$i][0]['ModuleName'] . "_Menu' class='nav child_menu' style='display: none'>";
            $SubMenuQuery = "Exec BO_GetUserModulesChild '" . $this->SecureInjection($this->Session->read('UserCode')) . "' , '" . $MenuResult[$i][0]['ModuleID'] . "' ";
            $SubMenuResult = $this->Login->query($SubMenuQuery);
            for ($j = 0; $j < count($SubMenuResult); $j++) {
                $result .= "<li id='" . $SubMenuResult[$j][0]['ModuleName'] . "_Sub' ><a href='../" . $SubMenuResult[$j][0]['ModuleController'] . "/" . $SubMenuResult[$j][0]['ModuleView'] . "'>" . $SubMenuResult[$j][0]['ModuleName'] . "</a>";
                $result .= "</li>";
            }
            $result .="</ul>";
            $result .="</li>";
        }
        return $result;
    }

}

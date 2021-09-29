<?php

App::import('Vendor', 'tcpdf/tcpdf');

class XTCPDF extends TCPDF {

    public $_EntityName;
    public $_FullName;
    public $_Position;
    public $_Address;
    public $_AltAddress;
    public $_Phone;
    public $_Mobile;
    public $_Email;
    public $_LogoLocation;
    public $_ImageName;

    public function Header() {
        $fontname = TCPDF_FONTS::addTTFfont(APP.'\webroot\fonts\\Roboto-Light.ttf', 'TrueTypeUnicode', '', 96);
        
        $this->Ln(10);
        $HtmlString = '<table border = "0">';
        $HtmlString .= '<tr>';
        $HtmlString .= '<td height="80" width = "20%">';
        if ($this->_LogoLocation != "" && $this->_ImageName == "") {
            $this->_ImageName = explode("/", $this->_LogoLocation)[3];
        }
        if($this->_ImageName != ""){
            $HtmlString .= '<img src="' . APP . 'webroot\Uploads\\' . $this->_ImageName . '"  /> ';
        }
        $HtmlString .= '</td>';
        $HtmlString .= '<td width="50%" >';
        $HtmlString .= '';
        $HtmlString .= '</td>';
        $HtmlString .= '<td width= "33%">';
        $HtmlString .= '<table border ="0">';
        if($this->_EntityName != ""){
        $HtmlString .= '<tr>';
        $HtmlString .= '<td height = "11"><b>';
        $HtmlString .= $this->_EntityName."</b>";
        if($this->_FullName != ""){
            $HtmlString .= ", ". $this->_FullName;
        }
        $HtmlString .= '</td>';
        $HtmlString .= '</tr>';
        }
        $this->SetFont($fontname, '', 11);
        if($this->_Position != ""){
        $HtmlString .= '<tr>';
        $HtmlString .= '<td height = "11">';
        $HtmlString .= $this->_Position;
        $HtmlString .= '</td>';
        $HtmlString .= '</tr>';
        }
        if($this->_Address != ""){
        $HtmlString .= '<tr>';
        $HtmlString .= '<td height = "11">';
        $HtmlString .= $this->_Address;
        $HtmlString .= '</td>';
        $HtmlString .= '</tr>';
        }
        if($this->_AltAddress != ""){
        $HtmlString .= '<tr>';
        $HtmlString .= '<td height = "12">';
        $HtmlString .= $this->_AltAddress;
        $HtmlString .= '</td>';
        $HtmlString .= '</tr>';
        }
        if($this->_Phone != ""){
        $HtmlString .= '<tr>';
        $HtmlString .= '<td height = "12">';
        $HtmlString .= $this->_Phone;
        if($this->_Mobile != ""){
            $HtmlString .= ", ". $this->_Mobile;
        }
        $HtmlString .= '</td>';
        $HtmlString .= '</tr>';
        }
        if($this->_Email != ""){
        $HtmlString .= '<tr>';
        $HtmlString .= '<td height = "12">';
        $HtmlString .= $this->_Email;
        $HtmlString .= '</td>';
        $HtmlString .= '</tr>';
        }
        $HtmlString .= '</table>';
        $HtmlString .= '</td>';
        $HtmlString .= '</tr>';
        $HtmlString .= '</table>';
        $this->writeHTML($HtmlString, true, false, true, false, 'L');
     
        
        
    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }

}

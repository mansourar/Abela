<?php
App::uses('Model', 'Model');
class AppModel extends Model {
    
    function CanAdd($ModuleRole , $UserRole){
        $ModuleBinary =  sprintf("%08b" , $ModuleRole);
        $UserRoleBinary =  sprintf("%08b" , $UserRole);
        if(substr($ModuleBinary, -2,1) == "1" ){
            if(substr($UserRoleBinary, -2,1) == "1" ){
                return true;
            }
        }
        return false;
    }
    
    function CanEdit($ModuleRole , $UserRole){
        $ModuleBinary =  sprintf("%08b" , $ModuleRole);
        $UserRoleBinary =  sprintf("%08b" , $UserRole);
        if(substr($ModuleBinary, -3,1) == "1" ){
            if(substr($UserRoleBinary, -3,1) == "1" ){
                return true;
            }
        }
        return false;
    }
    
    function CanDelete($ModuleRole , $UserRole){
        $ModuleBinary =  sprintf("%08b" , $ModuleRole);
        $UserRoleBinary =  sprintf("%08b" , $UserRole);
        if(substr($ModuleBinary, -4,1) == "1" ){
            if(substr($UserRoleBinary, -4,1) == "1" ){
                return true;
            }
        }
        return false;
    }
    
    function CanApply($ModuleRole , $UserRole){
        $ModuleBinary =  sprintf("%08b" , $ModuleRole);
        $UserRoleBinary =  sprintf("%08b" , $UserRole);
        if(substr($ModuleBinary, -5,1) == "1" ){
            if(substr($UserRoleBinary, -5,1) == "1" ){
                return true;
            }
        }
        return false;
    }
    
    function CanView($ModuleRole , $UserRole){
        $ModuleBinary =  sprintf("%08b" , $ModuleRole);
        $UserRoleBinary =  sprintf("%08b" , $UserRole);
        if(substr($ModuleBinary, -1) == "1" ){
            if(substr($UserRoleBinary, -1) == "1" ){
                return true;
            }
        }
        return false;
    }
}

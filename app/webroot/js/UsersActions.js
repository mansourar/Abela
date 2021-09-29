function AddUserAction() {
    var Grid = $('#UserGrid');
    $('#UserGrid').jqGrid('editGridRow', "new", {recreateForm: true, closeAfterAdd: true});
}
function AddReasonAction() {
    var Grid = $('#ReasonGrid');
    $('#ReasonGrid').jqGrid('editGridRow', "new", {recreateForm: true, closeAfterAdd: true});
}

function EditUserAction() {
    var Grid = $('#UserGrid');
    var selectedRowId = Grid.jqGrid('getGridParam', 'selrow');
    if (selectedRowId === null) {
        var ErrorString = "<h4>Cannot Perform This Action.</h4>";
        ErrorString += "<p>- Please Select A Row To Edit A User !</p>";
        $('#myModalLabel').html("Error: ");
        $('#PopupModal').html(ErrorString);
        $('#PopupModalSave').hide();
    }
    else {
        var cellValue = Grid.jqGrid('getCell', selectedRowId, 'IsERP');
        if (cellValue === 'YES') {
            var ErrorString = "<h4>Invalid Operation .</h4>";
            ErrorString += "<p>- This Is An ERP Entry, Please Select A Valid User !</p>";
            $('#myModalLabel').html("Error: ");
            $('#PopupModal').html(ErrorString);
            $('#PopupModalSave').hide();
            return;
        }

        var ErrorString = "<h4>You Are About To Edit Core Informations.</h4>";
        ErrorString += "<p>Are You sure You Want To Edit This User ?</p> Click Edit To Continue !";
        $('#myModalLabel').html("Warning: ");
        $('#PopupModal').html(ErrorString);
        $('#PopupModalSave').html("Edit !");
        $('#PopupModalSave').show();
        $("#PopupModalSave").click(function () {
            Grid.jqGrid('editGridRow', Grid.jqGrid('getGridParam', 'selrow'), {recreateForm: true, closeAfterAdd: true});
            $("#ModalUser").modal("hide");
        });

    }

}

function OTPUserAction() {
    var Grid = $('#UserGrid');
    var selectedRowId = Grid.jqGrid('getGridParam', 'selrow');
    if (selectedRowId === null) {
        var ErrorString = "<h4>Cannot Perform This Action.</h4>";
        ErrorString += "<p>- Please Select A Row To Generate An OTP !</p>";
        $('#myModalLabel').html("Error: ");
        $('#PopupModal').html(ErrorString);
        $('#PopupModalSave').hide();
    }
    else {
        var URL = "/Vitas/Users/GenerateOTP";
        $.ajax({
            url: URL,
            dataType: "json",
            type: 'POST',
            async: false,
            data: {
                UserID: function () {
                    return selectedRowId;
                }
            },
            success: function (data) {
                var ErrorString = "<h4>OTP.</h4>";
                ErrorString += "<p>" + data + " </p>";
                $('#myModalLabel').html("Warning: ");
                $('#PopupModal').html(ErrorString);
                $('#PopupModalSave').hide();
            },
            error: function (request, error) {
                new PNotify({
                    title: 'Network Error. ',
                    text: 'An Error Has Occured ! Please Try Again Later. ',
                    type: 'error'
                });
            }
        });
    }
}


function EditReasonAction() {
    var Grid = $('#ReasonGrid');
    var selectedRowId = Grid.jqGrid('getGridParam', 'selrow');
    if (selectedRowId === null) {
        var ErrorString = "<h4>Cannot Perform This Action.</h4>";
        ErrorString += "<p>- Please Select A Row To Edit A Reason !</p>";
        $('#myModalLabel').html("Error: ");
        $('#PopupModal').html(ErrorString);
        $('#PopupModalSave').hide();
    }
    else {
        var cellValue = Grid.jqGrid('getCell', selectedRowId, 'IsERP');
        if (cellValue === 'YES') {
            var ErrorString = "<h4>Invalid Operation .</h4>";
            ErrorString += "<p>- This Is An ERP Entry, Please Select A Valid Reason !</p>";
            $('#myModalLabel').html("Error: ");
            $('#PopupModal').html(ErrorString);
            $('#PopupModalSave').hide();
            return;
        }

        var ErrorString = "<h4>You Are About To Edit Core Informations.</h4>";
        ErrorString += "<p>Are You sure You Want To Edit This Reason ?</p> Click Edit To Continue !";
        $('#myModalLabel').html("Warning: ");
        $('#PopupModal').html(ErrorString);
        $('#PopupModalSave').html("Edit !");
        $('#PopupModalSave').show();
        $("#PopupModalSave").click(function () {
            Grid.jqGrid('editGridRow', Grid.jqGrid('getGridParam', 'selrow'), {recreateForm: true, closeAfterAdd: true});
            $("#ModalReason").modal("hide");
        });

    }

}


function DeleteUserAction() {

    var Grid = $('#UserGrid');
    var selectedRowId = Grid.jqGrid('getGridParam', 'selrow');
    if (selectedRowId === null) {
        var ErrorString = "<h4>Cannot Perform This Action.</h4>";
        ErrorString += "<p>- Please Select A Row To Delete A User !</p>";
        $('#myModalLabel').html("Error: ");
        $('#PopupModal').html(ErrorString);
        $('#PopupModalSave').hide();
    }
    else {
        var ErrorString = "<h4>You Are About To Delete Core Informations.</h4>";
        ErrorString += "<p><b>This Will Cause A Cascade Delete !</b> Are You sure You Want To Delete This User ?</p> Click Delete To Continue !";
        $('#myModalLabel').html("Warning: ");
        $('#PopupModal').html(ErrorString);
        $('#PopupModal').html(ErrorString);
        $('#PopupModalSave').show();
        $('#PopupModalSave').html("Delete !");
        $("#PopupModalSave").click(function () {
            $("#PopupModalSave").unbind("click");
            var URL = "/Vitas/Users/DeleteUser";
            $.ajax({
                url: URL,
                dataType: "json",
                type: 'POST',
                async: false,
                data: {
                    UserID: function () {
                        return selectedRowId;
                    }
                },
                success: function (data) {
                    $('#ModalUser').modal('hide');
                    Grid.trigger('reloadGrid');
                },
                error: function (request, error) {
                    new PNotify({
                        title: 'Network Error. ',
                        text: 'An Error Has Occured ! Please Try Again Later. ',
                        type: 'error'
                    });
                }
            });
        });
    }
}

function ManageGridUser() {
    $("#ManagePopup").modal("show");
}

function ToggleColumn(Value, Checkbox) {
    if (!Checkbox.checked) {
        jQuery("#UserGrid").jqGrid('hideCol', [Value]);
    }
    else {
        jQuery("#UserGrid").jqGrid('showCol', [Value]);
    }
}


function AddEditUserValidation() {
    try {
        var UserType = document.getElementById('UserType');
        var UserName = document.getElementById('Username');
        var Password = document.getElementById('Password');
        var FirstName = document.getElementById('FirstName');
        var MiddleName = document.getElementById('MiddleName');
        var LastName = document.getElementById('LastName');
        var BirthDate = document.getElementById('BirthDate');
        var Address = document.getElementById('Address');
        var Mobile = document.getElementById('Mobile');
        var Phone = document.getElementById('Phone');
        var Fax = document.getElementById('Fax');
        var Email = document.getElementById('UserEmail');

        var ChangePassword = document.getElementById('ChangePassword');
        var ChangePasswordRetype = document.getElementById('ChangePasswordRetype');

        var PhoneRegex = new RegExp("^[0-9]{8}$");

        if (UserType.value.trim() === "") {
            new PNotify({
                title: 'Invalid Input. ',
                text: 'User Type Input Is Required !',
                type: 'error',
                delay: 1500
            });
            $('.nav-tabs a[href="#PersonalInfo"]').tab('show');
            return false;
        }

        if (UserName.value.trim() === "") {
            UserName.focus();
            new PNotify({
                title: 'Invalid Input. ',
                text: 'Username Input Is Required !',
                type: 'error',
                delay: 1500
            });
            $('.nav-tabs a[href="#PersonalInfo"]').tab('show');
            return false;
        }
        if (Password.value.trim() === "") {
            Password.focus();
            new PNotify({
                title: 'Invalid Input. ',
                text: 'Password Input Is Required !',
                type: 'error',
                delay: 1500
            });
            $('.nav-tabs a[href="#PersonalInfo"]').tab('show');
            return false;
        }
        if (FirstName.value.trim() === "") {
            FirstName.focus();
            new PNotify({
                title: 'Invalid Input. ',
                text: 'Password Input Is Required !',
                type: 'error',
                delay: 1500
            });
            $('.nav-tabs a[href="#PersonalInfo"]').tab('show');
            return false;
        }
        if (MiddleName.value.trim() === "") {
            MiddleName.focus();
            new PNotify({
                title: 'Invalid Input. ',
                text: 'Middle Name Input Is Required !',
                type: 'error',
                delay: 1500
            });
            $('.nav-tabs a[href="#PersonalInfo"]').tab('show');
            return false;
        }
        if (LastName.value.trim() === "") {
            LastName.focus();
            new PNotify({
                title: 'Invalid Input. ',
                text: 'Last Name Input Is Required !',
                type: 'error',
                delay: 1500
            });
            $('.nav-tabs a[href="#PersonalInfo"]').tab('show');
            return false;
        }
        if (BirthDate.value.trim() === "") {
            BirthDate.focus();
            new PNotify({
                title: 'Invalid Input. ',
                text: 'Birth Date Input Is Required !',
                type: 'error',
                delay: 1500
            });
            $('.nav-tabs a[href="#PersonalInfo"]').tab('show');
            return false;
        }
        if (Address.value.trim() === "") {
            Address.focus();
            new PNotify({
                title: 'Invalid Input. ',
                text: 'Address Input Is Required !',
                type: 'error',
                delay: 1500
            });
            $('.nav-tabs a[href="#PersonalInfo"]').tab('show');
            return false;
        }
        if (Mobile.value.trim() === "") {
            Mobile.focus();
            new PNotify({
                title: 'Invalid Input. ',
                text: 'Mobile Input Is Required !',
                type: 'error',
                delay: 1500
            });
            $('.nav-tabs a[href="#PersonalInfo"]').tab('show');
            return false;
        }
        if (!PhoneRegex.test(Mobile.value)) {
            Mobile.focus();
            Mobile.value = "";
            new PNotify({
                title: 'Invalid Mobile Number. ',
                text: 'Only 8 Digits Are Allowed !',
                type: 'error',
                delay: 1500
            });
            $('.nav-tabs a[href="#PersonalInfo"]').tab('show');
            return false;
        }
        if (Phone.value.trim() !== "") {
            if (!PhoneRegex.test(Phone.value)) {
                Phone.focus();
                Phone.value = "";
                new PNotify({
                    title: 'Invalid Phone Number. ',
                    text: 'Only 8 Digits Are Allowed !',
                    type: 'error',
                    delay: 1500
                });
                $('.nav-tabs a[href="#PersonalInfo"]').tab('show');
                return false;
            }
        }
        if (Fax.value.trim() !== "") {
            if (!PhoneRegex.test(Fax.value)) {
                Fax.focus();
                Fax.value = "";
                new PNotify({
                    title: 'Invalid Fax Number. ',
                    text: 'Only 8 Digits Are Allowed !',
                    type: 'error',
                    delay: 1500
                });
                $('.nav-tabs a[href="#PersonalInfo"]').tab('show');
                return false;
            }
        }
        if (Email.value.trim() !== "") {
            var EmailRegex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            if (!EmailRegex.test(Email.value)) {
                Email.focus();
                Email.value = "";
                new PNotify({
                    title: 'Invalid Email Address. ',
                    text: 'Please Enter A Valid Email Address!',
                    type: 'error',
                    delay: 1500
                });
                $('.nav-tabs a[href="#PersonalInfo"]').tab('show');
                return false;
            }
        }

        if (ChangePassword.value !== ChangePasswordRetype.value) {
            new PNotify({
                title: 'Change Password Error',
                text: 'Passwords Does Not match',
                type: 'error',
                delay: 1500
            });
            $('.nav-tabs a[href="#UserPassword"]').tab('show');
            ChangePassword.value = "";
            ChangePasswordRetype.value = "";
            ChangePassword.focus();
            return false;
        }

        var UserExists = false;

        $.ajax({
            url: "/Vitas/Users/CheckUsername",
            dataType: "text",
            type: 'POST',
            async: false,
            data: {
                UserName: function () {
                    return UserName.value.trim();
                }
            },
            success: function (data) {
                if (data !== "0") {
                    UserExists = true;
                    new PNotify({
                        title: 'Username Error',
                        text: 'Username Already In Use',
                        type: 'error',
                        delay: 1500
                    });
                }
            },
            error: function (request, error) {
                new PNotify({
                    title: 'Network Error. ',
                    text: 'An Error Has Occured ! Please Try Again Later. ',
                    type: 'error',
                    delay: 1500
                });
            }
        });

        if (UserExists) {
            $('.nav-tabs a[href="#PersonalInfo"]').tab('show');
            return false;
        }
    }
    catch (e) {
        alert(e);
        $('.nav-tabs a[href="#PersonalInfo"]').tab('show');
        return false;
    }
    return true;
}


function AddEmployeeValidation() {
    try {
        var Reference = document.getElementById('Reference');
        var Profession = document.getElementById('Profession');
        var FirstName = document.getElementById('FirstName');
        var MiddleName = document.getElementById('MiddleName');
        var LastName = document.getElementById('LastName');
        var BirthDate = document.getElementById('BirthDate');
        var Address = document.getElementById('Address');
        var Mobile = document.getElementById('Mobile');
        var Phone = document.getElementById('Phone');
        var Note = document.getElementById('Note');
        var Email = document.getElementById('EmployeeEmail');
        var PhoneRegex = new RegExp("^[0-9]+$");

        if (Reference.value.trim() === "") {
            Reference.focus();
            new PNotify({
                title: 'Invalid Input. ',
                text: 'Reference Input Is Required !',
                type: 'error',
                delay: 1500
            });
            $('.nav-tabs a[href="#PersonalInfo"]').tab('show');
            return false;
        }

//        if (Profession.value.trim() === "") {
//            Profession.focus();
//            new PNotify({
//                title: 'Invalid Input. ',
//                text: 'Profession Input Is Required !',
//                type: 'error',
//                delay: 1500
//            });
//            $('.nav-tabs a[href="#PersonalInfo"]').tab('show');
//            return false;
//        }

        if (FirstName.value.trim() === "") {
            FirstName.focus();
            new PNotify({
                title: 'Invalid Input. ',
                text: 'Password Input Is Required !',
                type: 'error',
                delay: 1500
            });
            $('.nav-tabs a[href="#PersonalInfo"]').tab('show');
            return false;
        }


        if (LastName.value.trim() === "") {
            LastName.focus();
            new PNotify({
                title: 'Invalid Input. ',
                text: 'Last Name Input Is Required !',
                type: 'error',
                delay: 1500
            });
            $('.nav-tabs a[href="#PersonalInfo"]').tab('show');
            return false;
        }

        if (BirthDate.value.trim() === "") {
            BirthDate.focus();
            new PNotify({
                title: 'Invalid Input. ',
                text: 'Birth Date Input Is Required !',
                type: 'error',
                delay: 1500
            });
            $('.nav-tabs a[href="#PersonalInfo"]').tab('show');
            return false;
        }

//        if (Address.value.trim() === "") {
//            Address.focus();
//            new PNotify({
//                title: 'Invalid Input. ',
//                text: 'Address Input Is Required !',
//                type: 'error',
//                delay: 1500
//            });
//            $('.nav-tabs a[href="#PersonalInfo"]').tab('show');
//            return false;
//        }

//        if (Mobile.value.trim() === "") {
//            Mobile.focus();
//            new PNotify({
//                title: 'Invalid Input. ',
//                text: 'Mobile Input Is Required !',
//                type: 'error',
//                delay: 1500
//            });
//            $('.nav-tabs a[href="#PersonalInfo"]').tab('show');
//            return false;
//        }
        if (Mobile.value.trim() !== "") {
            if (!PhoneRegex.test(Mobile.value)) {
                Mobile.focus();
                Mobile.value = "";
                new PNotify({
                    title: 'Invalid Mobile Number. ',
                    text: 'Only Digits Are Allowed !',
                    type: 'error',
                    delay: 1500
                });
                $('.nav-tabs a[href="#PersonalInfo"]').tab('show');
                return false;
            }
        }
//        
//        if (Phone.value.trim() !== "") {
//            if (!PhoneRegex.test(Phone.value)) {
//                Phone.focus();
//                Phone.value = "";
//                new PNotify({
//                    title: 'Invalid Phone Number. ',
//                    text: 'Only Digits Are Allowed !',
//                    type: 'error',
//                    delay: 1500
//                });
//                $('.nav-tabs a[href="#PersonalInfo"]').tab('show');
//                return false;
//            }
//        }

//        if (Email.value.trim() !== "") {
//            var EmailRegex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
//            if (!EmailRegex.test(Email.value)) {
//                Email.focus();
//                Email.value = "";
//                new PNotify({
//                    title: 'Invalid Email Address. ',
//                    text: 'Please Enter A Valid Email Address!',
//                    type: 'error',
//                    delay: 1500
//                });
//                $('.nav-tabs a[href="#PersonalInfo"]').tab('show');
//                return false;
//            }
//        }


        var RefExists = false;

        $.ajax({
            url: "/Vitas/Employees/CheckRef",
            dataType: "text",
            type: 'POST',
            async: false,
            data: {
                Reference: function () {
                    return Reference.value.trim();
                }
            },
            success: function (data) {
                if (data !== "0") {
                    RefExists = true;
                    new PNotify({
                        title: 'Reference Error',
                        text: 'Reference Already In Use',
                        type: 'error',
                        delay: 1500
                    });
                }
            },
            error: function (request, error) {
                new PNotify({
                    title: 'Network Error. ',
                    text: 'An Error Has Occured ! Please Try Again Later. ',
                    type: 'error',
                    delay: 1500
                });
            }
        });

        if (RefExists) {
            $('.nav-tabs a[href="#PersonalInfo"]').tab('show');
            return false;
        }


    }
    catch (e) {
        alert(e);
        $('.nav-tabs a[href="#PersonalInfo"]').tab('show');
        return false;
    }
    return true;



}


function EditEmployeeValidation() {
    try {
        var Reference = document.getElementById('Reference');
        var Profession = document.getElementById('Profession');
        var FirstName = document.getElementById('FirstName');
        var MiddleName = document.getElementById('MiddleName');
        var LastName = document.getElementById('LastName');
        var BirthDate = document.getElementById('BirthDate');
        var Address = document.getElementById('Address');
        var Mobile = document.getElementById('Mobile');
        var Phone = document.getElementById('Phone');
        var Note = document.getElementById('Note');
        var Email = document.getElementById('EmployeeEmail');


        var StandardValue = document.getElementById('StandardValue');
        var StandardCurrency = document.getElementById('StandardCurrency');
        var OverTimeValue = document.getElementById('OvertimeValue');
        var OverTimeCurrency = document.getElementById('OverTimeCurrency');

        if (StandardValue.value.trim() === "") {
            new PNotify({
                title: 'Invalid Input. ',
                text: 'Value Input Is Required !',
                type: 'error',
                delay: 1500
            });
            $('.nav-tabs a[href="#Rate"]').tab('show');
            StandardValue.value = "";
            StandardValue.focus();
            return false;
        }

        if (!isNumeric(StandardValue.value.trim())) {

            new PNotify({
                title: 'Invalid Input. ',
                text: 'Value Input Must Be Numeric !',
                type: 'error',
                delay: 1500
            });
            $('.nav-tabs a[href="#Rate"]').tab('show');
            StandardValue.value = "";
            StandardValue.focus();

            return false;
        }

        if (OverTimeValue.value.trim() === "") {

            new PNotify({
                title: 'Invalid Input. ',
                text: 'Value Input Is Required !',
                type: 'error',
                delay: 1500
            });
            $('.nav-tabs a[href="#Rate"]').tab('show');
            OverTimeValue.value = "";
            OverTimeValue.focus();
            return false;
        }

        if (!isNumeric(OverTimeValue.value.trim())) {

            new PNotify({
                title: 'Invalid Input. ',
                text: 'Value Input Must Be Numeric !',
                type: 'error',
                delay: 1500
            });
            $('.nav-tabs a[href="#Rate"]').tab('show');
            OverTimeValue.value = "";
            OverTimeValue.focus();
            return false;
        }

        var PhoneRegex = new RegExp("^[0-9]+$");

        if (Reference.value.trim() === "") {
            Reference.focus();
            new PNotify({
                title: 'Invalid Input. ',
                text: 'Reference Input Is Required !',
                type: 'error',
                delay: 1500
            });
            $('.nav-tabs a[href="#PersonalInfo"]').tab('show');
            return false;
        }

//        if (Profession.value.trim() === "") {
//            Profession.focus();
//            new PNotify({
//                title: 'Invalid Input. ',
//                text: 'Profession Input Is Required !',
//                type: 'error',
//                delay: 1500
//            });
//            $('.nav-tabs a[href="#PersonalInfo"]').tab('show');
//            return false;
//        }

        if (FirstName.value.trim() === "") {
            FirstName.focus();
            new PNotify({
                title: 'Invalid Input. ',
                text: 'Password Input Is Required !',
                type: 'error',
                delay: 1500
            });
            $('.nav-tabs a[href="#PersonalInfo"]').tab('show');
            return false;
        }


        if (LastName.value.trim() === "") {
            LastName.focus();
            new PNotify({
                title: 'Invalid Input. ',
                text: 'Last Name Input Is Required !',
                type: 'error',
                delay: 1500
            });
            $('.nav-tabs a[href="#PersonalInfo"]').tab('show');
            return false;
        }
        if (BirthDate.value.trim() === "") {
            BirthDate.focus();
            new PNotify({
                title: 'Invalid Input. ',
                text: 'Birth Date Input Is Required !',
                type: 'error',
                delay: 1500
            });
            $('.nav-tabs a[href="#PersonalInfo"]').tab('show');
            return false;
        }
//        if (Address.value.trim() === "") {
//            Address.focus();
//            new PNotify({
//                title: 'Invalid Input. ',
//                text: 'Address Input Is Required !',
//                type: 'error',
//                delay: 1500
//            });
//            $('.nav-tabs a[href="#PersonalInfo"]').tab('show');
//            return false;
//        }
//        if (Mobile.value.trim() === "") {
//            Mobile.focus();
//            new PNotify({
//                title: 'Invalid Input. ',
//                text: 'Mobile Input Is Required !',
//                type: 'error',
//                delay: 1500
//            });
//            $('.nav-tabs a[href="#PersonalInfo"]').tab('show');
//            return false;
//        }
        if (Mobile.value.trim() !== "") {
            if (!PhoneRegex.test(Mobile.value)) {
                Mobile.focus();
                Mobile.value = "";
                new PNotify({
                    title: 'Invalid Mobile Number. ',
                    text: 'Only Digits Are Allowed !',
                    type: 'error',
                    delay: 1500
                });
                $('.nav-tabs a[href="#PersonalInfo"]').tab('show');
                return false;
            }
        }
//        if (Phone.value.trim() !== "") {
//            if (!PhoneRegex.test(Phone.value)) {
//                Phone.focus();
//                Phone.value = "";
//                new PNotify({
//                    title: 'Invalid Phone Number. ',
//                    text: 'Only Digits Are Allowed !',
//                    type: 'error',
//                    delay: 1500
//                });
//                $('.nav-tabs a[href="#PersonalInfo"]').tab('show');
//                return false;
//            }
//        }
        if (Email.value.trim() !== "") {
            var EmailRegex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            if (!EmailRegex.test(Email.value)) {
                Email.focus();
                Email.value = "";
                new PNotify({
                    title: 'Invalid Email Address. ',
                    text: 'Please Enter A Valid Email Address!',
                    type: 'error',
                    delay: 1500
                });
                $('.nav-tabs a[href="#PersonalInfo"]').tab('show');
                return false;
            }
        }
    }
    catch (e) {
        alert(e);
        $('.nav-tabs a[href="#PersonalInfo"]').tab('show');
        return false;
    }
    return true;



}

function isNumeric(n) {
    return !isNaN(parseFloat(n)) && isFinite(n);
}


function AddPrinterAction() {
    var Grid = $('#UserGrid');
    $('#PrinterGrid').jqGrid('editGridRow', "new", {recreateForm: true, closeAfterAdd: true,
        afterSubmit: function (response, postdata) {
            if (response.responseText != "Done") {
                return [false, response.responseText];
            } else {
                return [true, ''];
            }
        }
    });
}

function EditPrinterAction() {
    var Grid = $('#PrinterGrid');
    var selectedRowId = Grid.jqGrid('getGridParam', 'selrow');
    if (selectedRowId === null) {
        var ErrorString = "<h4>Cannot Perform This Action.</h4>";
        ErrorString += "<p>- Please Select A Row To Edit A Printer !</p>";
        $('#myModalLabel').html("Error: ");
        $('#PopupModal').html(ErrorString);
        $('#PopupModalSave').hide();
    }
    else {
        var cellValue = Grid.jqGrid('getCell', selectedRowId, 'IsERP');
        if (cellValue === 'YES') {
            var ErrorString = "<h4>Invalid Operation .</h4>";
            ErrorString += "<p>- This Is An ERP Entry, Please Select A Valid Printer !</p>";
            $('#myModalLabel').html("Error: ");
            $('#PopupModal').html(ErrorString);
            $('#PopupModalSave').hide();
            return;
        }

        var ErrorString = "<h4>You Are About To Edit Core Informations.</h4>";
        ErrorString += "<p>Are You sure You Want To Edit This Printer ?</p> Click Edit To Continue !";
        $('#myModalLabel').html("Warning: ");
        $('#PopupModal').html(ErrorString);
        $('#PopupModalSave').html("Edit !");
        $('#PopupModalSave').show();
        $("#PopupModalSave").click(function () {
            Grid.jqGrid('editGridRow', Grid.jqGrid('getGridParam', 'selrow'), {recreateForm: true, closeAfterAdd: true,
                afterSubmit: function (response, postdata) {
                    if (response.responseText != "Done") {
                        return [false, response.responseText];
                    } else {
                        return [true, ''];
                    }
                }
            });
            $("#ModalPrinter").modal("hide");
        });

    }

}

function DeletePrinterAction() {

    var Grid = $('#PrinterGrid');
    var selectedRowId = Grid.jqGrid('getGridParam', 'selrow');
    if (selectedRowId === null) {
        var ErrorString = "<h4>Cannot Perform This Action.</h4>";
        ErrorString += "<p>- Please Select A Row To Delete A Printer !</p>";
        $('#myModalLabel').html("Error: ");
        $('#PopupModal').html(ErrorString);
        $('#PopupModalSave').hide();
    }
    else {
        var ErrorString = "<h4>You Are About To Delete Core Informations.</h4>";
        ErrorString += "<p><b>This Will Cause A Cascade Delete !</b> Are You sure You Want To Delete This Printer ?</p> Click Delete To Continue !";
        $('#myModalLabel').html("Warning: ");
        $('#PopupModal').html(ErrorString);
        $('#PopupModal').html(ErrorString);
        $('#PopupModalSave').show();
        $('#PopupModalSave').html("Delete !");
        $("#PopupModalSave").click(function () {
            $("#PopupModalSave").unbind("click");
            var URL = "/Vitas/Printers/DeletePrinter";
            $.ajax({
                url: URL,
                dataType: "text",
                type: 'POST',
                async: false,
                data: {
                    PrinterMAC: function () {
                        return selectedRowId;
                    }
                },
                success: function (data) {
                    $('#ModalPrinter').modal('hide');
                    Grid.trigger('reloadGrid');
                },
                error: function (request, error) {
                    new PNotify({
                        title: 'Network Error. ',
                        text: 'An Error Has Occured ! Please Try Again Later. ',
                        type: 'error'
                    });
                }
            });
        });
    }
}

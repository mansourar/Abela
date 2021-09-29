var LoginLock = false;

function AddUserTypeAction() {
    $("#UserTypeGrid").jqGrid('editGridRow', "new", {recreateForm: true, closeAfterAdd: true});
}



function EditUserTypeAction() {
    var Grid = $('#UserTypeGrid');
    var selectedRowId = Grid.jqGrid('getGridParam', 'selrow');
    if (selectedRowId === null) {
        var ErrorString = "<h4>Cannot Perform This Action.</h4>";
        ErrorString += "<p>- Please Select A Row To Edit A User Type !</p>";
        $('#myModalLabel').html("Error: ");
        $('#PopupModal').html(ErrorString);
        $('#PopupModalSave').hide();
    }
    else {
        var ErrorString = "<h4>You Are About To Edit Core Informations.</h4>";
        ErrorString += "<p>Are You sure You Want To Edit This User Type ?</p> Click Edit To Continue !";
        $('#myModalLabel').html("Warning: ");
        $('#PopupModal').html(ErrorString);
        $('#PopupModalSave').html("Edit !");
        $('#PopupModalSave').show();
        $("#PopupModalSave").click(function () {
            Grid.jqGrid('editGridRow', Grid.jqGrid('getGridParam', 'selrow'), {recreateForm: true, closeAfterAdd: true});
            $('#ModalUserType').modal('hide');
            $("#nData").hide();
            $("#pData").hide();
            $("#PopupModalSave").unbind("click");
        });

    }

}


function DeleteUserTypeAction() {

    var Grid = $('#UserTypeGrid');
    var selectedRowId = Grid.jqGrid('getGridParam', 'selrow');
    if (selectedRowId === null) {
        var ErrorString = "<h4>Cannot Perform This Action.</h4>";
        ErrorString += "<p>- Please Select A Row To Delete A User Type !</p>";
        $('#myModalLabel').html("Error: ");
        $('#PopupModal').html(ErrorString);
        $('#PopupModalSave').hide();
    }
    else {
        var ErrorString = "<h4>You Are About To Delete Core Informations.</h4>";
        ErrorString += "<p><b>This Will Cause A Cascade Delete !</b> Are You sure You Want To Delete This User Type ?</p> Click Delete To Continue !";
        $('#myModalLabel').html("Warning: ");
        $('#PopupModal').html(ErrorString);
        $('#PopupModal').html(ErrorString);
        $('#PopupModalSave').show();
        $('#PopupModalSave').html("Delete !");
        $("#PopupModalSave").click(function () {
            $("#PopupModalSave").unbind("click");
            var URL = "/Vitas/UserTypes/DeleteUserType";
            $.ajax({
                url: URL,
                dataType: "json",
                type: 'POST',
                async: false,
                data: {
                    UserTypeID: function () {
                        return selectedRowId;
                    }
                },
                success: function (data) {
                    $('#ModalUserType').modal('hide');
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

function ApplyUserTypeAction() {
    var Grid = $('#UserTypeGrid');
    var selectedRowId = Grid.jqGrid('getGridParam', 'selrow');
    if (selectedRowId === null) {
        var ErrorString = "<h4>Cannot Perform This Action.</h4>";
        ErrorString += "<p>- Please Select A Row To Assing A User Type !</p>";
        $('#myModalLabel').html("Error: ");
        $('#PopupModal').html(ErrorString);
        $('#PopupModalSave').hide();
    }
    else {
        $('#PopupModalSave').html("Save Changes !");
        $('#myModalLabel').html("Warning: ");
        $('#PopupModal').html("<p>Are You sure You Want To Assign This User Type ?</p>Click Save To Continue !");
        $('#PopupModalSave').show();
        $("#PopupModalSave").click(function () {
            var URL = "/Vitas/UserType/AssignUserType";
            if (LoginLock) {
                return false;
            }
            LoginLock = true;
            $.ajax({
                url: URL,
                dataType: "json",
                type: 'POST',
                async: false,
                data: {
                    UserTypeID: function () {
                        return selectedRowId;
                    }
                },
                success: function (data) {
                    if (data === "Redirect") {
                        window.location.href = "/Vitas/Dashboard/index";
                        LoginLock = false;
                    }
                },
                error: function (request, error) {
                    new PNotify({
                        title: 'Network Error. ',
                        text: 'An Error Has Occured ! Please Try Again Later. ',
                        type: 'error'
                    });
                    LoginLock = false;
                }
            });
        });
    }
}
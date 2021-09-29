var LoginLock = false;
function AddPermissionAction() {
    $("#PermissionGrid").jqGrid('editGridRow', "new", {recreateForm: true, closeAfterAdd: true});
}

function EditPermissionAction() {
    var Grid = $('#PermissionGrid');
    var selectedRowId = Grid.jqGrid('getGridParam', 'selrow');
    if (selectedRowId === null) {
        var ErrorString = "<h4>Cannot Perform This Action.</h4>";
        ErrorString += "<p>- Please Select A Row To Edit A Permission !</p>";
        $('#myModalLabel').html("Error: ");
        $('#PopupModal').html(ErrorString);
        $('#PopupModalSave').hide();
    }
    else {
        var ErrorString = "<h4>You Are About To Edit Core Informations.</h4>";
        ErrorString += "<p>Are You sure You Want To Edit This Permission ?</p> Click Edit To Continue !";
        $('#myModalLabel').html("Warning: ");
        $('#PopupModal').html(ErrorString);
        $('#PopupModalSave').html("Edit !");
        $('#PopupModalSave').show();
        $("#PopupModalSave").click(function () {
            $("#PermissionGrid").jqGrid('editGridRow', Grid.jqGrid('getGridParam', 'selrow'), {recreateForm: true, closeAfterAdd: true});
            $('#ModalPermission').modal('hide');
            $("#nData").hide();
            $("#pData").hide();
            $("#PopupModalSave").unbind("click");
        });
    }
}

function DeletePermissionAction() {

    var Grid = $('#PermissionGrid');
    var selectedRowId = Grid.jqGrid('getGridParam', 'selrow');
    if (selectedRowId === null) {
        var ErrorString = "<h4>Cannot Perform This Action.</h4>";
        ErrorString += "<p>- Please Select A Row To Delete A Permission !</p>";
        $('#myModalLabel').html("Error: ");
        $('#PopupModal').html(ErrorString);
        $('#PopupModalSave').hide();
    }
    else {
        var ErrorString = "<h4>You Are About To Delete Core Informations.</h4>";
        ErrorString += "<p><b>This Will Cause A Cascade Delete !</b> Are You sure You Want To Delete This Permission ?</p> Click Delete To Continue !";
        $('#myModalLabel').html("Warning: ");
        $('#PopupModal').html(ErrorString);
        $('#PopupModal').html(ErrorString);
        $('#PopupModalSave').show();
        $('#PopupModalSave').html("Delete !");
        $("#PopupModalSave").click(function () {
            $("#PopupModalSave").unbind("click");
            var URL = "/Vitas/Permission/DeletePermission";
            $.ajax({
                url: URL,
                dataType: "json",
                type: 'POST',
                async: false,
                data: {
                    PermissionID: function () {
                        return selectedRowId;
                    }
                },
                success: function (data) {
                    $('#ModalPermission').modal('hide');
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

   
function AddProjectAction() {
    $("#ProjectGrid").jqGrid('editGridRow', "new", {recreateForm: true, closeAfterAdd: true});
}

function EditProjectAction() {
    var Grid = $('#ProjectGrid');
    var selectedRowId = Grid.jqGrid('getGridParam', 'selrow');
    if (selectedRowId === null) {
        var ErrorString = "<h4>Cannot Perform This Action.</h4>";
        ErrorString += "<p>- Please Select A Row To Edit A Project !</p>";
        $('#myModalLabel').html("Error: ");
        $('#PopupModal').html(ErrorString);
        $('#PopupModalSave').hide();
    }
    else {
        var ErrorString = "<h4>You Are About To Edit Core Informations.</h4>";
        ErrorString += "<p>Are You sure You Want To Edit This Project ?</p> Click Edit To Continue !";
        $('#myModalLabel').html("Warning: ");
        $('#PopupModal').html(ErrorString);
        $('#PopupModalSave').html("Edit !");
        $('#PopupModalSave').show();
        $("#PopupModalSave").click(function () {
            Grid.jqGrid('editGridRow', Grid.jqGrid('getGridParam', 'selrow'), {recreateForm: true, closeAfterAdd: true});
            $('#ModalProject').modal('hide');
            $("#nData").hide();
            $("#pData").hide();
            $("#PopupModalSave").unbind("click");
        });

    }

}


function DeleteProjectAction() {

    var Grid = $('#ProjectGrid');
    var selectedRowId = Grid.jqGrid('getGridParam', 'selrow');
    if (selectedRowId === null) {
        var ErrorString = "<h4>Cannot Perform This Action.</h4>";
        ErrorString += "<p>- Please Select A Row To Delete A Project !</p>";
        $('#myModalLabel').html("Error: ");
        $('#PopupModal').html(ErrorString);
        $('#PopupModalSave').hide();
    }
    else {
        var ErrorString = "<h4>You Are About To Delete Core Informations.</h4>";
        ErrorString += "<p><b>This Will Cause A Cascade Delete !</b> Are You sure You Want To Delete This Project ?</p> Click Delete To Continue !";
        $('#myModalLabel').html("Warning: ");
        $('#PopupModal').html(ErrorString);
        $('#PopupModal').html(ErrorString);
        $('#PopupModalSave').show();
        $('#PopupModalSave').html("Delete !");
        $("#PopupModalSave").click(function () {
        $("#PopupModalSave").unbind("click");
            var URL = "/Vitas/Projects/DeleteProject";
            $.ajax({
                url: URL,
                dataType: "json",
                type: 'POST',
                async: false,
                data: {
                    ProjectID: function () {
                        return selectedRowId;
                    }
                },
                success: function (data) {
                    $('#ModalProject').modal('hide');
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

function ApplyProjectAction() {
    var Grid = $('#ProjectGrid');
    var selectedRowId = Grid.jqGrid('getGridParam', 'selrow');
    if (selectedRowId === null) {
        var ErrorString = "<h4>Cannot Perform This Action.</h4>";
        ErrorString += "<p>- Please Select A Row To Assing A Project !</p>";
        $('#myModalLabel').html("Error: ");
        $('#PopupModal').html(ErrorString);
        $('#PopupModalSave').hide();
    }
    else {
        $('#PopupModalSave').html("Initialize !");
        $('#myModalLabel').html("Warning: ");
        $('#PopupModal').html("<p>Are You sure You Want To Initialize This Project ?</p>Click Save To Continue !");
        $('#PopupModalSave').show();
        $("#PopupModalSave").click(function () {
            document.location.href = "/Vitas/Projects/Mapping?ProjectID="+selectedRowId;
        });
    }
}
<style>
    .ui-jqgrid .ui-jqgrid-labels th.ui-th-column {
        background-color: #73879C;
        background-image: none;
        color:#f0eeee
    }
    .ui-jqgrid-hbox{
        background-color: #73879C;
        background-image: none;
        color:#2A3F54
    }
    .ui-jqgrid .loading
    {
        left: 45%;
        top: 45%;
        background-position-x: 50%;
        background-position-y: 50%;
        background-repeat: no-repeat;
        height: 20px;
        width: 20px;
    }
</style>


<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id = "ModalAssign">
    <div class="modal-dialog modal-lg modal-content">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="">
                    Assign Brochure !
                </h4>
            </div>
            <div class="modal-body" id = "PopupModal_Assign">
                <div class="row">
                    <label class="control-label col-md-2 col-sm-2 col-xs-2 col-lg-2 " for="FirstName" style="padding-top: 10px">
                        Assignment Type
                        <span class="required">*</span>
                    </label>

                    <div class="col-md-4 col-sm-4 col-xs-4 col-lg-4">
                        <select 
                            id="AssignmentType" 
                            name="AssignmentType" 
                            onchange="$('#AssignmentGrid').trigger('reloadGrid');"
                            class="form-control col-md-4 col-sm-4 col-xs-4 col-lg-4">
                            <option value = "1" >Medical Representative</option>
                            <option value = "3" >Classification</option>
                            <option value = "4" >Specialty</option>
                        </select>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div id="AssignGrid">
                        <table id="AssignmentGrid"></table>
                        <div id="AssignmentGridPager"></div>
                    </div>
                </div>

                <script type="text/javascript">
                    // User Grid !
                    $(document).ready(function () {
                        $("#AssignmentGrid").jqGrid({
                            url: '/Abela/Brochures/GetBrochureAssignments',
                            postData: {
                                BrochureCode: function () {
                                    var Grid = $('#BrochureGrid');
                                    var selectedRowId = Grid.jqGrid('getGridParam', 'selrow');
                                    if (selectedRowId === null) {
                                        return '0'
                                    }
                                    var cellValue = Grid.jqGrid('getCell', selectedRowId, 'BrochureCode');
                                    return cellValue;
                                },
                                AssignmentType: function () {
                                    return document.getElementById("AssignmentType").value;
                                }
                            },
                            mtype: "GET",
                            styleUI: 'Bootstrap',
                            datatype: "json",
                            shrinkToFit: true,
                            autowidth: true,
                            editurl: "/Abela/Brochures/AssignBrochure",
                            colModel: [
                                {id: "RowID", label: 'RecordID', name: 'RowID', key: true, width: 110, align: "left", hidden: true},
                                {id: "AssignmentCode", label: 'Assignment Code', name: 'AssignmentCode', key: true, width: 70, align: "left", hidden: false},
                                {id: "AssignmentDescription", label: 'Assignment Value', name: 'AssignmentDescription', width: 210, align: "left", hidden: false},
                                {id: "AssignmentType", label: 'Type', name: 'AssignmentType', width: 210, align: "left", hidden: true},
                                {id: "Assigned", label: 'Type', name: 'Assigned', width: 10, align: "left", hidden: true}
                            ],
                            viewrecords: true,
                            height: GetGridHeight() - 170,
                            rowNum: 15,
                            pager: "#AssignmentGridPager",
                            multiselect: true,
                            multiboxonly: false,
                            onSelectRow: function (rowid) {
                                if (document.getElementById("jqg_AssignmentGrid_" + rowid).checked) {
                                    ManageAssignment(rowid, "A")
                                } else {
                                    ManageAssignment(rowid, "R")
                                }
                            },
                            loadComplete: function () {
                                var rows = $("#AssignmentGrid").getDataIDs();
                                for (var a = 0; a < rows.length; a++)
                                {
                                    var row = jQuery("#AssignmentGrid").getRowData(rows[a]);
                                    if (row.Assigned === "1") {
                                        jQuery('#AssignmentGrid').jqGrid('setSelection', row.RowID, false);
                                    }
                                }
                            }
                        });
                        $('#AssignmentGrid').jqGrid('filterToolbar');
                        $('#AssignmentGrid').jqGrid('navGrid', "#AssignmentGridPager", {search: false, add: false, edit: false, del: false, refresh: false});
                        $('#AssignmentGrid').navButtonAdd('#AssignmentGridPager', {
                            title: "Reload",
                            caption: "",
                            buttonicon: "glyphicon glyphicon-refresh",
                            onClickButton: function () {
                                $('#AssignmentGrid').trigger('reloadGrid');
                            }
                        });
                    });


                    function ManageAssignment(rowid, action) {
                        $.ajax({
                            url: '/Abela/Brochures/SetBrochureAssignments',
                            data: {
                                BrochureCode: function () {
                                    var Grid = $('#BrochureGrid');
                                    var selectedRowId = Grid.jqGrid('getGridParam', 'selrow');
                                    if (selectedRowId === null) {
                                        return '0'
                                    }
                                    var cellValue = Grid.jqGrid('getCell', selectedRowId, 'BrochureCode');
                                    return cellValue;
                                },
                                AssignmentCode: function () {
                                    var cellValue = $('#AssignmentGrid').jqGrid('getCell', rowid, 'AssignmentCode');
                                    return cellValue;
                                },
                                AssignmentType: function () {
                                    var cellValue = $('#AssignmentGrid').jqGrid('getCell', rowid, 'AssignmentType');
                                    return cellValue;
                                },
                                Action: function () {
                                    return action;
                                }
                            },
                            dataType: 'text',
                            type: 'GET',
                            success: function (data) {
                                var Display = "Added To ";
                                if (action === "R") {
                                    Display = "Removed From "
                                }
                                new PNotify({
                                    title: 'Success. ',
                                    text: 'Assignment ' + Display + 'Database.',
                                    type: 'success',
                                    delay: 1500
                                });
                            },
                            error: function (request, error) {
                                new PNotify({
                                    title: 'Network Error. ',
                                    text: 'An Error Has Occured ! Please Try Again Later. ',
                                    type: 'error',
                                    delay: 1500
                                });
                            }
                        })
                    }




                </script>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" onclick="" >Close</button>
            </div>
        </div>
    </div>
</div>


<div class="row" id = "ContentDivMod">
    <div class="col-md-12 col-sm-12 col-xs-12" style="padding:0px; margin: 0px">
        <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
            <div id="BrochureListGrid">
                <table id="BrochureGrid"></table>
                <div id="BrochureGridPager"></div>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id = "ModalBrochure">
        <div class="modal-dialog modal-content">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel"></h4>
                </div>
                <div class="modal-body" id = "PopupModal">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"  onclick="$('#PopupModalSave').unbind('click');">Close</button>
                    <button type="button" class="btn btn-primary" id="PopupModalSave">Save changes</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    // Brochure Grid !
    $(document).ready(function () {
        $("#BrochureGrid").jqGrid({
            url: '/Abela/Brochures/GetBrochuresData',
            editurl: "/Abela/Brochures/SetBrochure",
            mtype: "GET",
            styleUI: 'Bootstrap',
            datatype: "json",
            autowidth: true,
            shrinkToFit: true,
            colModel: [
                {id: "BrochureCode", label: 'Code', name: 'BrochureCode', key: true, width: 70, align: "left", hidden: false},
                {id: "BrochureName", label: 'Brochure Name', name: 'BrochureName', width: 150, align: "left", hidden: false, editable: true},
                {id: "ItemCode", label: 'Item Code', name: 'ItemCode', width: 70, align: "left", hidden: false, editable: false},
                {id: "ItemName", label: 'Item Name', name: 'ItemName', width: 150, align: "left", hidden: false, editable: false},
                {id: "File", label: 'File', name: 'File', width: 20, align: "left", hidden: false, editable: false}
            ],
            viewrecords: true,
            height: GetGridHeight(),
            rowNum: 20,
            pager: "#BrochureGridPager",
            multiselect: false,
            multiboxonly: false
        });
        $('#BrochureGrid').jqGrid('filterToolbar');
        $('#BrochureGrid').jqGrid('navGrid', "#BrochureGridPager", {search: false, add: false, edit: false, del: false, refresh: false});
<?php
if ($CanAdd) {
    ?>
            $('#BrochureGrid').navButtonAdd('#BrochureGridPager', {
                caption: "",
                title: "Add",
                buttonicon: "glyphicon glyphicon-plus",
                onClickButton: function () {
                    AddBrochureAction()
                }
            }).navSeparatorAdd("#BrochureGridPager");
    <?php
}
?>

<?php
if ($CanEdit) {
    ?>
            $('#BrochureGrid').navButtonAdd('#BrochureGridPager', {
                caption: "",
                title: "Edit",
                buttonicon: "glyphicon glyphicon-pencil",
                onClickButton: function () {
                    $("#PopupModalSave").unbind("click");
                    EditBrochureAction()
                    $("#ModalBrochure").modal("show");
                }
            }).navSeparatorAdd("#BrochureGridPager");
    <?php
}
?>


<?php
if ($CanDelete) {
    ?>
            $('#BrochureGrid').navButtonAdd('#BrochureGridPager', {
                caption: "",
                title: "Add",
                buttonicon: "glyphicon glyphicon-trash",
                onClickButton: function () {
                    $("#PopupModalSave").unbind("click");
                    DeleteBrochureAction()
                    $("#ModalBrochure").modal("show");
                }
            }).navSeparatorAdd("#BrochureGridPager");
    <?php
}
?>

        $('#BrochureGrid').navButtonAdd('#BrochureGridPager', {
            title: "Assign",
            caption: "",
            buttonicon: "glyphicon glyphicon-user",
            onClickButton: function () {
                $("#PopupModalSave").unbind("click");
                var Grid = $('#BrochureGrid');
                var selectedRowId = Grid.jqGrid('getGridParam', 'selrow');

                if (selectedRowId === null) {
                    var ErrorString = "<h4>Cannot Perform This Action.</h4>";
                    ErrorString += "<p>- Please Select A Row To Assign A Brochure !</p>";
                    $('#myModalLabel').html("Error: ");
                    $('#PopupModal').html(ErrorString);
                    $('#PopupModalSave').hide();
                    $("#ModalBrochure").modal("show");
                    return;
                }
                $('#AssignmentGrid').trigger('reloadGrid');
                $("#ModalAssign").modal("show");

                setTimeout(function () {
                    var width = $("#AssignGrid").width();
                    $("#AssignmentGrid").jqGrid('setGridWidth', width);
                    $("#cb_AssignmentGrid").hide()

                }, 200)

            }
        }).navSeparatorAdd("#BrochureGridPager");


        $('#BrochureGrid').navButtonAdd('#BrochureGridPager', {
            title: "Reload",
            caption: "",
            buttonicon: "glyphicon glyphicon-refresh",
            onClickButton: function () {
                $('#BrochureGrid').trigger('reloadGrid');
            }
        });
    });
    AdjustGrid('#BrochureListGrid', '#BrochureGrid');

    function AddBrochureAction() {
        document.location.href = "/Abela/Brochures/AddBrochure";
    }

    function EditBrochureAction() {
        var Grid = $('#BrochureGrid');
        var selectedRowId = Grid.jqGrid('getGridParam', 'selrow');
        if (selectedRowId === null) {
            var ErrorString = "<h4>Cannot Perform This Action.</h4>";
            ErrorString += "<p>- Please Select A Row To Edit A Brochure !</p>";
            $('#myModalLabel').html("Error: ");
            $('#PopupModal').html(ErrorString);
            $('#PopupModalSave').hide();
            return;
        }

        var ErrorString = "<h4>You Are About To Edit Core Informations.</h4>";
        ErrorString += "<p>Are You sure You Want To Edit This Brochure ?</p> Click Edit To Continue !";
        $('#myModalLabel').html("Warning: ");
        $('#PopupModal').html(ErrorString);
        $('#PopupModalSave').html("Edit !");
        $('#PopupModalSave').show();
        $("#PopupModalSave").click(function () {
            $("#ModalBrochure").modal("hide");
            Grid.jqGrid('editGridRow', Grid.jqGrid('getGridParam', 'selrow'), {recreateForm: true, closeAfterAdd: true});
        });

    }

    function DeleteBrochureAction() {
        var Grid = $('#BrochureGrid');
        var selectedRowId = Grid.jqGrid('getGridParam', 'selrow');

        if (selectedRowId === null) {
            var ErrorString = "<h4>Cannot Perform This Action.</h4>";
            ErrorString += "<p>- Please Select A Row To Edit A Brochure !</p>";
            $('#myModalLabel').html("Error: ");
            $('#PopupModal').html(ErrorString);
            $('#PopupModalSave').hide();
            return;
        }

        var ErrorString = "<h4>You Are About To Delete Core Informations.</h4>";
        ErrorString += "<p>Are You sure You Want To Delete This Brochure ?</p> Click Edit To Continue !";
        $('#myModalLabel').html("Warning: ");
        $('#PopupModal').html(ErrorString);
        $('#PopupModalSave').html("Delete !");
        $('#PopupModalSave').show();
        $("#PopupModalSave").click(function () {
            $.ajax({
                url: '/Abela/Brochures/DelBrochuresData',
                data: {
                    BrochureCode: selectedRowId,
                },
                dataType: 'text',
                type: 'GET',

                success: function (data) {
                    $('#BrochureGrid').trigger('reloadGrid');
                    $("#ModalBrochure").modal("hide");
                },
                error: function (request, error) {
                    $("#ModalBrochure").modal("hide");
                    new PNotify({
                        title: 'Network Error. ',
                        text: 'An Error Has Occured ! Please Try Again Later. ',
                        type: 'error',
                        delay: 1500
                    });
                }
            })
        });
    }
</script>

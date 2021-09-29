
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
                    Assign Event !
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
                            url: '/Abela/Events/GetEventAssignments',
                            postData: {
                                EventCode: function () {
                                    var Grid = $('#EventGrid');
                                    var selectedRowId = Grid.jqGrid('getGridParam', 'selrow');
                                    if (selectedRowId === null) {
                                        return '0'
                                    }
                                    var cellValue = Grid.jqGrid('getCell', selectedRowId, 'EventCode');
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
                            editurl: "/Abela/Events/AssignEvent",
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
                            url: '/Abela/Events/SetEventAssignments',
                            data: {
                                EventCode: function () {
                                    var Grid = $('#EventGrid');
                                    var selectedRowId = Grid.jqGrid('getGridParam', 'selrow');
                                    if (selectedRowId === null) {
                                        return '0'
                                    }
                                    var cellValue = Grid.jqGrid('getCell', selectedRowId, 'EventCode');
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

                    $(document).ready(function () {
                        setTimeout(function () {
                            document.getElementById("gs_RegistrationDate").disabled = true;
                            document.getElementById("gs_EventDate").disabled = true;
                        }, 100)

                    });



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
            <div id="EventListGrid">
                <table id="EventGrid"></table>
                <div id="EventGridPager"></div>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id = "ModalEvent">
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
    // Event Grid !
    $(document).ready(function () {
        $("#EventGrid").jqGrid({
            url: '/Abela/Events/GetEventsData',
            mtype: "GET",
            styleUI: 'Bootstrap',
            datatype: "json",
            autowidth: true,
            shrinkToFit: true,
            editurl: "/Abela/Events/SetEventsData",
            colModel: [
                {id: "EventCode", label: 'Code', name: 'EventCode', key: true, width: 70, align: "left", hidden: false, editable: true, editrules: {required: false}, editoptions: {maxlength: 18}},
                {id: "EventName", label: 'Event Name', name: 'EventName', width: 150, align: "left", hidden: false, editable: true, editrules: {required: true}, editoptions: {maxlength: 200}},
                {id: "EventLocation", label: 'Event Location', name: 'EventLocation', width: 150, align: "left", editable: true, editoptions: {maxlength: 50, editoptions: {maxlength: 200}}},
                {id: "EventDate", label: 'Event Date', name: 'EventDate', width: 150, align: "left", hidden: false, editable: true, editrules: {required: true}},
                {id: "Presenters", label: 'Presenters', name: 'Presenters', width: 150, align: "left", hidden: false, editable: true, editoptions: {maxlength: 400}},
                {id: "RegistrationDate", label: 'Registration Date', name: 'RegistrationDate', width: 150, align: "left", editable: true, editrules: {required: true}},
                {id: "ParticipantsNumber", label: 'Participants Number', name: 'ParticipantsNumber', width: 150, align: "left", editable: true, editrules: {required: true, number: true}},
                {id: "Expenses", label: 'Expenses', name: 'Expenses', width: 150, align: "left", editable: true},
                {id: "Currency", label: 'Currency', name: 'Currency', width: 150, align: "left", editable: true, stype: "select", searchoptions: {value: ":All;<?php echo($Currencies); ?>"}, edittype: "select", editoptions: {value: "<?php echo($Currencies); ?>"}},
                {id: "ExpectCost", label: 'Expect Cost', name: 'ExpectCost', width: 150, align: "left", hidden: true, editable: false, editoptions: {maxlength: 100}},
                {id: "AttendanceCost", label: 'Attendance Cost', name: 'AttendanceCost', width: 150, align: "left", hidden: true, editable: false, editrules: {required: true, number: true}},
                {id: "ActualCost", label: 'Actual Cost', name: 'ActualCost', width: 150, align: "left", hidden: true, editable: false, editrules: {required: true, number: true}}
            ],
            viewrecords: true,
            height: GetGridHeight(),
            rowNum: 20,
            pager: "#EventGridPager",
            multiselect: false,
            multiboxonly: false
        });
        $('#EventGrid').jqGrid('filterToolbar');
        $('#EventGrid').jqGrid('navGrid', "#EventGridPager", {search: false, add: false, edit: false, del: false, refresh: false});
<?php
if ($CanAdd) {
    ?>
            $('#EventGrid').navButtonAdd('#EventGridPager', {
                caption: "",
                title: "Add",
                buttonicon: "glyphicon glyphicon-plus",
                onClickButton: function () {
                    AddEventAction()
                }
            }).navSeparatorAdd("#EventGridPager");
    <?php
}
if ($CanEdit) {
    ?>


            $('#EventGrid').navButtonAdd('#EventGridPager', {
                caption: "",
                title: "Edit",
                buttonicon: "glyphicon glyphicon-pencil",
                onClickButton: function () {
                    $("#PopupModalSave").unbind("click");
                    EditEventAction()
                    $("#ModalEvent").modal("show");
                }
            }).navSeparatorAdd("#EventGridPager");
    <?php
}

if ($CanDelete) {
    ?>


            $('#EventGrid').navButtonAdd('#EventGridPager', {
                caption: "",
                title: "Delete",
                buttonicon: "glyphicon glyphicon-trash",
                onClickButton: function () {
                    $("#PopupModalSave").unbind("click");
                    DeleteEventAction()
                    $("#ModalEvent").modal("show");
                }
            }).navSeparatorAdd("#EventGridPager");
    <?php
}
?>
        $('#EventGrid').navButtonAdd('#EventGridPager', {
            title: "Assign",
            caption: "",
            buttonicon: "glyphicon glyphicon-user",
            onClickButton: function () {
                $("#PopupModalSave").unbind("click");
                var Grid = $('#EventGrid');
                var selectedRowId = Grid.jqGrid('getGridParam', 'selrow');
                if (selectedRowId === null) {
                    var ErrorString = "<h4>Cannot Perform This Action.</h4>";
                    ErrorString += "<p>- Please Select A Row To Assign An Event !</p>";
                    $('#myModalLabel').html("Error: ");
                    $('#PopupModal').html(ErrorString);
                    $('#PopupModalSave').hide();
                    $("#ModalEvent").modal("show");
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
        }).navSeparatorAdd("#EventGridPager");

        $('#EventGrid').navButtonAdd('#EventGridPager', {
            title: "Export",
            caption: "",
            buttonicon: "glyphicon glyphicon-export",
            onClickButton: function () {
                var URL = "/Excel/Reports/ExportEvents.php";
                window.open(URL, '_blank').focus();
            }
        }).navSeparatorAdd("#EventGridPager");

        $('#EventGrid').navButtonAdd('#EventGridPager', {
            title: "Reload",
            caption: "",
            buttonicon: "glyphicon glyphicon-refresh",
            onClickButton: function () {
                $('#EventGrid').trigger('reloadGrid');
            }
        });
    });
    AdjustGrid('#EventListGrid', '#EventGrid');

    function AddEventAction() {
        var Grid = $('#EventGrid');
        $('#EventGrid').jqGrid('editGridRow', "new", {recreateForm: true, closeAfterAdd: true});
        setTimeout(function () {

            document.getElementById("EventDate").readOnly = true;
            document.getElementById("RegistrationDate").readOnly = true;
            document.getElementById("EventCode").readOnly = true;

            $("#EventDate").daterangepicker({
                locale: {format: 'YYYY-MM-DD'},
                singleDatePicker: true,
                showDropdowns: true,
                calender_style: "picker_2"
            }, function (start, end, label) {
                var D = $("#EventDate").val( ).split("/")
                $("#EventDate").val(D[2] + "-" + D[0] + "-" + D[1])
                return false;
            });

            $("#RegistrationDate").daterangepicker({
                locale: {format: 'YYYY-MM-DD'},
                singleDatePicker: true,
                showDropdowns: true,
                calender_style: "picker_2"
            }, function (start, end, label) {
                var D = $("#RegistrationDate").val( ).split("/")
                $("#RegistrationDate").val(D[2] + "-" + D[0] + "-" + D[1])
                return false;
            });

        }, 100)
    }

    function EditEventAction() {
        var Grid = $('#EventGrid');
        var selectedRowId = Grid.jqGrid('getGridParam', 'selrow');
        if (selectedRowId === null) {
            var ErrorString = "<h4>Cannot Perform This Action.</h4>";
            ErrorString += "<p>- Please Select A Row To Edit An Event !</p>";
            $('#myModalLabel').html("Error: ");
            $('#PopupModal').html(ErrorString);
            $('#PopupModalSave').hide();
            return;
        }

        var ErrorString = "<h4>You Are About To Edit Core Informations.</h4>";
        ErrorString += "<p>Are You sure You Want To Edit This Event ?</p> Click Edit To Continue !";
        $('#myModalLabel').html("Warning: ");
        $('#PopupModal').html(ErrorString);
        $('#PopupModalSave').html("Edit !");
        $('#PopupModalSave').show();
        $("#PopupModalSave").click(function () {
            Grid.jqGrid('editGridRow', Grid.jqGrid('getGridParam', 'selrow'), {recreateForm: true, closeAfterAdd: true});
            setTimeout(function () {

                document.getElementById("EventDate").readOnly = true;
                document.getElementById("RegistrationDate").readOnly = true;
                document.getElementById("EventCode").readOnly = true;




                $("#EventDate").daterangepicker({
                    locale: {format: 'yyy-mm-dd'},
                    singleDatePicker: true,
                    showDropdowns: true,
                    calender_style: "picker_2"
                }, function (start, end, label) {
                    var D = $("#EventDate").val( ).split("/")
                    $("#EventDate").val(D[2] + "-" + D[0] + "-" + D[1])
                    return false;
                });

                $("#RegistrationDate").daterangepicker({
                    locale: {format: 'yyy-mm-dd'},
                    singleDatePicker: true,
                    showDropdowns: true,
                    calender_style: "picker_2"
                }, function (start, end, label) {
                    var D = $("#RegistrationDate").val( ).split("/")
                    $("#RegistrationDate").val(D[2] + "-" + D[0] + "-" + D[1])
                    return false;
                });

            }, 100)
            $("#ModalEvent").modal("hide");
        });

    }

    function DeleteEventAction() {
        var Grid = $('#EventGrid');
        var selectedRowId = Grid.jqGrid('getGridParam', 'selrow');


        if (selectedRowId === null) {
            var ErrorString = "<h4>Cannot Perform This Action.</h4>";
            ErrorString += "<p>- Please Select A Row To Delete An Event !</p>";
            $('#myModalLabel').html("Error: ");
            $('#PopupModal').html(ErrorString);
            $('#PopupModalSave').hide();
            return;
        }

        var EventCode = Grid.jqGrid('getCell', selectedRowId, 'EventCode');

        var ErrorString = "<h4>You Are About To Edit Core Informations.</h4>";
        ErrorString += "<p>Are You sure You Want To Delete This Event ?</p> Click Delete To Continue !";
        $('#myModalLabel').html("Warning: ");
        $('#PopupModal').html(ErrorString);
        $('#PopupModalSave').html("Delete !");
        $('#PopupModalSave').show();
        $("#PopupModalSave").click(function () {
            $.ajax({
                url: '/Abela/Events/DelEventsData',
                data: {
                    EventCode: EventCode,
                },
                dataType: 'text',
                type: 'GET',
                success: function (data) {
                    $('#EventGrid').trigger('reloadGrid');
                    $("#ModalEvent").modal("hide");
                },
                error: function (request, error) {
                    $("#ModalEvent").modal("hide");
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

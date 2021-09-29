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


<div class="row" id = "ContentDivMod">
    <div class="col-md-12 col-sm-12 col-xs-12" style="padding:0px; margin: 0px">
        <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
            <div id="ReasonListGrid">
                <table id="ReasonGrid"></table>
                <div id="ReasonGridPager"></div>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id = "ModalReason">
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
    // Reason Grid !
    $(document).ready(function () {
        $("#ReasonGrid").jqGrid({
            url: '/Abela/Reasons/GetReasonsData',
            mtype: "GET",
            styleUI: 'Bootstrap',
            datatype: "json",
            autowidth: true,
            shrinkToFit: true,
            editurl: "/Abela/Reasons/SetReasonsData",
            colModel: [
                {id: "ReasonCode", label: 'Code', name: 'ReasonCode', key: true, width: 70, align: "left", hidden: false, editable: true, editrules: {required: true}, editoptions: {maxlength: 10}},
                {id: "ReasonName", label: 'Name', name: 'ReasonName', width: 150, align: "left", hidden: false, editable: true, editrules: {required: true}, editoptions: {maxlength: 50}},
                {id: "ReasonAltName", label: 'Alt Name', name: 'ReasonAltName', width: 150, align: "left", editable: true, editoptions: {maxlength: 50}},
                {id: "ReasonDisplaySequence", label: 'Sequence', name: 'ReasonDisplaySequence', width: 150, align: "left", hidden: false, editable: true, editrules: {required: true, number: true}},
                {id: "ReasonAffectStock", label: 'Affect Stock', name: 'ReasonAffectStock', width: 150, align: "left", hidden: false, editable: true, editoptions: {maxlength: 5}},
                {id: "ReasonTypeName", label: 'Type', name: 'ReasonTypeName', width: 150, align: "left", editable: true, stype: "select", searchoptions: {value: ":All;<?php echo($TypeFilter); ?>"}, edittype: "select", editoptions: {value: "<?php echo($TypeFilter); ?>"}},
            ],
            viewrecords: true,
            height: GetGridHeight(),
            rowNum: 20,
            pager: "#ReasonGridPager",
            multiselect: false,
            multiboxonly: false
        });
        $('#ReasonGrid').jqGrid('filterToolbar');
        $('#ReasonGrid').jqGrid('navGrid', "#ReasonGridPager", {search: false, add: false, edit: false, del: false, refresh: false});
<?php
if ($CanAdd) {
    ?>
            $('#ReasonGrid').navButtonAdd('#ReasonGridPager', {
                caption: "",
                title: "Add",
                buttonicon: "glyphicon glyphicon-plus",
                onClickButton: function () {
                    AddReasonAction()
                }
            }).navSeparatorAdd("#ReasonGridPager");
    <?php
}
if ($CanEdit) {
    ?>


            $('#ReasonGrid').navButtonAdd('#ReasonGridPager', {
                caption: "",
                title: "Edit",
                buttonicon: "glyphicon glyphicon-pencil",
                onClickButton: function () {
                    $("#PopupModalSave").unbind("click");
                    EditReasonAction()
                    $("#ModalReason").modal("show");
                }
            }).navSeparatorAdd("#ReasonGridPager");
    <?php
}
?>
        $('#ReasonGrid').navButtonAdd('#ReasonGridPager', {
            title: "Reload",
            caption: "",
            buttonicon: "glyphicon glyphicon-refresh",
            onClickButton: function () {
                $('#ReasonGrid').trigger('reloadGrid');
            }
        });
    });
    AdjustGrid('#ReasonListGrid', '#ReasonGrid');


    function AddReasonAction() {
        var Grid = $('#ReasonGrid');
        $('#ReasonGrid').jqGrid('editGridRow', "new", {recreateForm: true, closeAfterAdd: true});
        setTimeout(function () {
            document.getElementById("ReasonCode").readOnly = false;
        }, 100)
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
            setTimeout(function () {
                document.getElementById("ReasonCode").readOnly = true;
            }, 1000);
            $("#ModalReason").modal("hide");
        });

    }






</script>

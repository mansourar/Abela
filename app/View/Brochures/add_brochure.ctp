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

<form method="POST" id="BrochuresForm" class="form-horizontal form-label-left" onsubmit="return ValidateAddBrochure(this)" enctype="multipart/form-data">
    <input type="hidden" name ="SelectedItem" id="SelectedItem"/>
    <div class="row" id = "ContentDivMod">
        <div class="col-md-12 col-sm-12 col-xs-12" style="padding:0px; margin: 0px">
            <div class="row x_title">
                <div class="col-md-6">
                    <h3>Brochures - <small>Add New Brochure</small></h3>
                </div>
                <div class="col-md-6">
                    <!--ENTER CENTER TITLE-->
                </div>
            </div>
            <div class="col-md-10 col-sm-10 col-xs-10 col-lg-10">
                <div class="row">
                    <div style="padding-top: 3px" class="x_panel">
                        <div class="x_content">
                            <div class="item form-group">
                                <label class="control-label col-md-1 col-sm-2 col-xs-1 col-lg-1">
                                    Name
                                    <span class="required">*</span>
                                </label>
                                <div class="col-md-4 col-sm-4 col-xs-4 col-lg-4">
                                    <input 
                                        id="BrochureName" 
                                        name="BrochureName" 
                                        class="form-control col-md-4 col-sm-4 col-xs-4 col-lg-4" 
                                        data-validate-length-range="6" 
                                        data-validate-words="2" 
                                        placeholder="Brochure Name" 
                                        maxlength="50"
                                        type="text" />
                                    <span class="fa fa-file form-control-feedback right" aria-hidden="true"></span>
                                </div>
                                <label class="control-label col-md-2 col-sm-2 col-xs-2 col-lg-2 " for="FirstName">
                                    Upload File
                                    <span class="required">*</span>
                                </label>
                                <div class="col-md-4 col-sm-4 col-xs-4 col-lg-4">
                                    <input 
                                        id="FileToUpload" 
                                        name="FileToUpload" 
                                        class="form-control col-md-4 col-sm-4 col-xs-4 col-lg-4" 
                                        style="border: none"
                                        data-validate-length-range="6" 
                                        data-validate-words="2" 
                                        type="file" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12" style="padding:0px; margin: 0px">
                        <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
                            <div id="ItemListGrid">
                                <table id="ItemGrid"></table>
                                <div id="ItemGridPager"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2 col-sm-2 col-xs-2 col-lg-2">
                <div class="row">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Actions</h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li>
                                    <a class="collapse-link">
                                        <i class="fa fa-chevron-up"></i>
                                    </a>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <button type="button" class="btn btn-danger" style="width:100%" id="EditEntityButton" onclick="window.location.href = '/Abela/Brochures/index';" data-toggle="modal" data-target=".bs-example-modal-lg">Cancel</button>
                            <input type="submit" class="btn btn-success" style="width:100%" value="Save"  onclick=""/>
                        </div>
                        <?php echo($UploadError); ?>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</form>


<script lang="javascript">

    function ValidateAddBrochure() {
        var BrochureName = document.getElementById("BrochureName");
        if (BrochureName.value.trim() === "") {
            new PNotify({
                title: 'Validation Error.',
                text: "Invalid Brochure Name, Brochure Name Is Required !",
                type: 'error',
                delay: 1500
            });
            return false;
        }
        var myGrid = $('#ItemGrid');
        var selectedRowId = myGrid.jqGrid('getGridParam', 'selrow');
        if (selectedRowId === null) {
            new PNotify({
                title: 'Validation Error.',
                text: "Invalid Item Selection, Select An Item First !",
                type: 'error',
                delay: 1500
            });
            return false;
        }
        return true;
    }



    OpenNavigation("Brochures_Menu", "Brochures_Sub");

    // Item Grid !
    $(document).ready(function () {
        $("#ItemGrid").jqGrid({
            url: '/Abela/Items/GetItemsData',
            mtype: "GET",
            styleUI: 'Bootstrap',
            datatype: "json",
            autowidth: true,
            shrinkToFit: true,
            editurl: "/Abela/Items/SetItemsData",
            colModel: [
                {id: "ItemCode", label: 'Code', name: 'ItemCode', key: true, width: 70, align: "left", hidden: false, editable: false, editrules: {required: true}, editoptions: {maxlength: 10}},
                {id: "ItemName", label: 'Name', name: 'ItemName', width: 150, align: "left", hidden: false, editable: false, editrules: {required: true}, editoptions: {maxlength: 50}},
                {id: "ItemAltName", label: 'Alt Name', name: 'ItemAltName', width: 150, align: "left", editable: false, editoptions: {maxlength: 50}},
                {id: "ItemStatus", label: 'Status', name: 'ItemStatus', width: 50, align: "left", editable: false, editoptions: {maxlength: 50}, stype: "select", searchoptions: {value: ":All;1:Yes;2:No"}},
            ],
            viewrecords: true,
            height: GetGridHeight() - 140,
            rowNum: 20,
            pager: "#ItemGridPager",
            multiselect: false,
            multiboxonly: false,
            onSelectRow: function (rowid) {
                $("#SelectedItem").val(rowid);
            },
        });
        $('#ItemGrid').jqGrid('filterToolbar');
        $('#ItemGrid').jqGrid('navGrid', "#ItemGridPager", {search: false, add: false, edit: false, del: false, refresh: false});
        $('#ItemGrid').navButtonAdd('#ItemGridPager', {
            title: "Reload",
            caption: "",
            buttonicon: "glyphicon glyphicon-refresh",
            onClickButton: function () {
                $('#ItemGrid').trigger('reloadGrid');
            }
        });
    });

    AdjustGrid('#ItemListGrid', '#ItemGrid');

    setTimeout(function () {
        $("#cb_ItemGrid").hide();
    }, 100)




</script>

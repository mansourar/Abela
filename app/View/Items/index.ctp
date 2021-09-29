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

<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12">
    <div id="ItemListGrid">
        <table id="ItemGrid"></table>
        <div id="ItemGridPager"></div>
    </div>
</div>

<script lang="javascript">
    // Item Grid
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
            height: GetGridHeight(),
            rowNum: 20,
            pager: "#ItemGridPager",
            multiselect: false,
            multiboxonly: false
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

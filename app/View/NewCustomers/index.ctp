
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
            <div id="NewCustomerListGrid">
                <table id="NewCustomerGrid"></table>
                <div id="NewCustomerGridPager"></div>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id = "ModalNewCustomer">
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
    // NewCustomer Grid !
    $(document).ready(function () {

        $("#NewCustomerGrid").jqGrid({
            url: '/Abela/NewCustomers/GetNewCustomersData',
            mtype: "GET",
            styleUI: 'Bootstrap',
            datatype: "json",
            autowidth: true,
            shrinkToFit: false,
            colModel: [
                {id: "ClientCode", label: 'Code', name: 'ClientCode', key: true, width: 120, align: "left", hidden: false, editable: false, key: true},
                {id: "UserCode", label: 'User Code', name: 'UserCode', key: true, width: 120, align: "left", hidden: false, editable: false},
                {id: "UserName", label: 'User Name', name: 'UserName', key: true, width: 170, align: "left", hidden: false, editable: false},
                {id: "ClientName", label: 'Customer Name', name: 'ClientName', width: 170, align: "left", hidden: false, editable: false},
                {id: "Specialty", label: 'Specialty', name: 'Specialty', width: 120, align: "left", editable: false},
                {id: "Classification", label: 'Classification', name: 'Classification', width: 120, align: "left", hidden: false, editable: false},
                {id: "Frequency", label: 'Frequency', name: 'Frequency', width: 100, align: "left", hidden: false, editable: false},
                {id: "Date", label: 'Date', name: 'Date', width: 170, align: "left", hidden: false, editable: false},
                {id: "ClientAddress", label: 'Address', name: 'ClientAddress', width: 170, align: "left", hidden: false, editable: false},
                {id: "Phone", label: 'Phone', name: 'Phone', width: 150, align: "left", hidden: false, editable: false},
                {id: "Area", label: 'Area', name: 'Area', width: 150, align: "left", hidden: false},
                {id: "Email", label: 'Email', name: 'Email', width: 150, align: "left", hidden: false},
                {id: "Notes", label: 'Notes', name: 'Notes', width: 200, align: "left", hidden: false},
                {id: "IsPharmacy", label: 'Is Pharmacy', name: 'IsPharmacy', width: 100, align: "left", hidden: false, stype: "select", searchoptions: {value: ":All;Yes:Yes;No:No", defaultValue: ''}},
                {id: "Status", label: 'Status', name: 'Status', width: 100, align: "left", hidden: false, stype: "select", searchoptions: {value: "Pending:Pending;Approved:Approved;Rejected:Rejected", defaultValue: 'Pending'}},
            ],
            viewrecords: true,
            height: GetGridHeight(),
            rowNum: 20,
            pager: "#NewCustomerGridPager",
            multiselect: false,
            multiboxonly: false
        });
        $('#NewCustomerGrid').jqGrid('filterToolbar');
        $('#NewCustomerGrid').jqGrid('navGrid', "#NewCustomerGridPager", {search: false, add: false, edit: false, del: false, refresh: false});

<?php
if ($CanAdd) {
    ?>
            $('#NewCustomerGrid').navButtonAdd('#NewCustomerGridPager', {
                caption: "",
                title: "Approve",
                buttonicon: "glyphicon glyphicon-ok",
                onClickButton: function () {
                    ApproveNewCustomerAction();
                }
            }).navSeparatorAdd("#NewCustomerGridPager");
    <?php
}
if ($CanEdit) {
    ?>

            $('#NewCustomerGrid').navButtonAdd('#NewCustomerGridPager', {
                caption: "",
                title: "Reject",
                buttonicon: "glyphicon glyphicon-remove",
                onClickButton: function () {
                    RejectNewCustomerAction()
                }
            }).navSeparatorAdd("#NewCustomerGridPager");
    <?php
}
?>

        $('#NewCustomerGrid').navButtonAdd('#NewCustomerGridPager', {
            title: "Export",
            caption: "",
            buttonicon: "glyphicon glyphicon-export",
            onClickButton: function () {
                var URL = "/Excel/Reports/ExportNewClients.php";
                window.open(URL, '_blank').focus();
            }
        }).navSeparatorAdd("#NewCustomerGridPager");


        $('#NewCustomerGrid').navButtonAdd('#NewCustomerGridPager', {
            title: "Reload",
            caption: "",
            buttonicon: "glyphicon glyphicon-refresh",
            onClickButton: function () {
                $('#NewCustomerGrid').trigger('reloadGrid');
            }
        });

    });
    AdjustGrid('#NewCustomerListGrid', '#NewCustomerGrid');


    function ApproveNewCustomerAction() {
        $("#PopupModalSave").unbind("click");
        var Grid = $('#NewCustomerGrid');
        var selectedRowId = Grid.jqGrid('getGridParam', 'selrow');

        if (selectedRowId === null) {
            var ErrorString = "<h4>Cannot Perform This Action.</h4>";
            ErrorString += "<p>- Please Select A Row To Approve !</p>";
            $('#myModalLabel').html("Error: ");
            $('#PopupModal').html(ErrorString);
            $('#PopupModalSave').hide();
            return;
        }
        var ClientCode = Grid.jqGrid('getCell', selectedRowId, 'ClientCode');
        var Status = Grid.jqGrid('getCell', selectedRowId, 'Status');
        if (Status !== "Pending") {
            new PNotify({
                title: 'Form Error. ',
                text: 'Invalid Selection ! Client Status Must be Pending. ',
                type: 'error',
                delay: 1500
            });
            return
        }


        var ErrorString = "<h4>You Are About To Edit Core Informations.</h4>";
        ErrorString += "<p>Are You sure You Want To Approve  ?</p> Click Approve To Continue !";
        $('#myModalLabel').html("Warning: ");
        $('#PopupModal').html(ErrorString);
        $('#PopupModalSave').html("Approve !");
        $('#PopupModalSave').show();
        $("#PopupModalSave").click(function () {

            $.ajax({
                url: '/Abela/NewCustomers/ApproveNewCustomer',
                data: {
                    ClientCode: function () {
                        return ClientCode;
                    },
                },
                dataType: 'text',
                type: 'GET',

                success: function (data) {
                    $('#NewCustomerGrid').trigger('reloadGrid');
                    $("#ModalNewCustomers").modal("hide");
                    if (data !== "Done") {
                        new PNotify({
                            title: 'Network Error. ',
                            text: data,
                            type: 'error',
                            delay: 1500
                        });
                    } else {
                        $('#NewCustomerGrid').trigger('reloadGrid');
                    }
                },
                error: function (request, error) {
                    $("#ModalNewCustomers").modal("hide");
                    new PNotify({
                        title: 'Network Error. ',
                        text: 'An Error Has Occured ! Please Try Again Later. ',
                        type: 'error',
                        delay: 1500
                    });
                }
            })

            $("#ModalNewCustomer").modal("hide");
        });
        $("#ModalNewCustomer").modal("show");
    }

    function RejectNewCustomerAction() {
        $("#PopupModalSave").unbind("click");
        var Grid = $('#NewCustomerGrid');
        var selectedRowId = Grid.jqGrid('getGridParam', 'selrow');
        if (selectedRowId === null) {
            var ErrorString = "<h4>Cannot Perform This Action.</h4>";
            ErrorString += "<p>- Please Select A Row To Reject !</p>";
            $('#myModalLabel').html("Error: ");
            $('#PopupModal').html(ErrorString);
            $('#PopupModalSave').hide();
            return;
        }
        var ClientCode = Grid.jqGrid('getCell', selectedRowId, 'ClientCode');
        var Status = Grid.jqGrid('getCell', selectedRowId, 'Status');

        if (Status !== "Pending") {
            new PNotify({
                title: 'Form Error. ',
                text: 'Invalid Selection ! Client Status Must be Pending. ',
                type: 'error',
                delay: 1500
            });
            return
        }

        var ErrorString = "<h4>You Are About To Edit Core Informations.</h4>";
        ErrorString += "<p>Are You sure You Want To Reject ?</p> Click Reject To Continue !";
        $('#myModalLabel').html("Warning: ");
        $('#PopupModal').html(ErrorString);
        $('#PopupModalSave').html("Reject !");
        $('#PopupModalSave').show();
        $("#PopupModalSave").click(function () {
            $.ajax({
                url: '/Abela/NewCustomers/RejectNewCustomer',
                data: {
                    ClientCode: function () {
                        return ClientCode;
                    },
                },
                dataType: 'text',
                type: 'GET',

                success: function (data) {
                    $('#NewCustomerGrid').trigger('reloadGrid');
                    $("#ModalNewCustomers").modal("hide");
                    if (data !== "Done") {
                        new PNotify({
                            title: 'Network Error. ',
                            text: data,
                            type: 'error',
                            delay: 1500
                        });
                    } else {
                        $('#NewCustomerGrid').trigger('reloadGrid');
                    }
                },
                error: function (request, error) {
                    $("#ModalNewCustomers").modal("hide");
                    new PNotify({
                        title: 'Network Error. ',
                        text: 'An Error Has Occured ! Please Try Again Later. ',
                        type: 'error',
                        delay: 1500
                    });
                }
            })
            $("#ModalNewCustomer").modal("hide");
        });
        $("#ModalNewCustomer").modal("show");
    }

    $(document).ready(function () {
        setTimeout(function () {
            document.getElementById("gs_Date").disabled = true;
        }, 100)
    });






</script>

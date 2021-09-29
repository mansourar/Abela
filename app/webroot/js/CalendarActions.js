function FormatDate(Date) {
    var mm = Date.getMonth() + 1; // getMonth() is zero-based
    var dd = Date.getDate();

    return [Date.getFullYear(),
        (mm > 9 ? '' : '0') + mm,
        (dd > 9 ? '' : '0') + dd
    ].join('-') + " " + Date.getHours() + ":" + Date.getMinutes() + ":" + Date.getSeconds() + ":" + Date.getMilliseconds();
}

function addMinutes(date, minutes) {
    return moment.utc(date).add(minutes, 'm');
}

function ValidateCalendarClick(date, jsEvent, view) {

    var ClickedDate = new Date(date).toISOString();
    var LocalDate = new Date();
    var NewClickedDateString = ClickedDate.split("T")[0] + " " + ClickedDate.split("T")[1].split("Z")[0];
    var NewLocalDateString = FormatDate(LocalDate);
    var ClickedDate = new Date(NewClickedDateString);
    var LocalDate = new Date(NewLocalDateString);
    var view = $('#calendar').fullCalendar('getView');

    if (view.name === 'month') {
        return false;
    }

    if (ClickedDate < LocalDate) {
        $('#ModalPassedDay').modal("show");
        return false;
    }

    if ($("#SelectedUserType").val() === "") {
        $('#ModalSelectUserVal').modal("show");
        return false;
    }



    var count = 0;
    $('#calendar').fullCalendar('clientEvents', function (event) {
        if (event.start < date && event.end > date) {
            console.log(event);
            count++;
        }
    });
    if (count > 0) {
        return false;
    }
    return true;
}

function AddNewAppointment() {

    var ClientCode = document.getElementById('SelectedClientID').value;
    if ($("#SelectedEventID").val() !== '') {
        ClientCode = $("#SelectedClientEdit").val();
    }

    if (ClientCode === "Select A Client") {
        new PNotify({
            title: 'Input Error. ',
            text: 'An Error Has Occured ! Select A Client First. ',
            type: 'error',
            delay: 1500
        });
        return false;
    }


    var UserCode = $("#SelectedUserCode").val();
    if ($("#SelectedEventID").val() !== '') {
        UserCode = "Same"
    }

    if (UserCode === "") {
        new PNotify({
            title: 'Input Error. ',
            text: 'An Error Has Occured ! Select A User First. ',
            type: 'error',
            delay: 1500
        });
        return false;
    }


    var ClientAddress = $("#SelectedClientAddress").val();
    if (ClientAddress === "null") {
        new PNotify({
            title: 'Input Error. ',
            text: 'An Error Has Occured ! Select Client Address First. ',
            type: 'error',
            delay: 1500
        });
        return false;
    }


    var Guarantor = $("#SelectedGuarantorID").val();
    var GuarantorAddress = $("#SelectedGuarantorAddress").val();
    if (Guarantor !== "") {
        if (GuarantorAddress === "") {
            new PNotify({
                title: 'Input Error. ',
                text: 'An Error Has Occured ! Select Guarantor Address First. ',
                type: 'error',
                delay: 1500
            });
            return false;
        }
    }

    var SelectedReason = $("#SelectedReason").val();
    $.ajax({
        url: "/Vitas/Routes/AddNewAppointment",
        dataType: "text",
        type: 'POST',
        async: false,
        data: {
            EventID: function () {
                return document.getElementById('SelectedEventID').value;
            },
            ClientCode: function () {
                return ClientCode;
            },
            UserCode: function () {
                return UserCode;
            },
            ClientAddress: function () {
                return ClientAddress;
            },
            Guarantor: function () {
                return Guarantor;
            },
            GuarantorAddress: function () {
                return GuarantorAddress;
            },
            SelectedReason: function () {
                return SelectedReason;
            },
            StartDate: function () {
                return moment.utc(document.getElementById("ClickedDate").value).format("YYYY-MM-DD HH:mm:ss");
            },
            EndDate: function () {
                var ClickedDate = document.getElementById("ClickedDate").value;
                var End = addMinutes(ClickedDate, 29)
                return  moment.utc(End).format("YYYY-MM-DD HH:mm:ss")
            }
        },
        success: function (data) {
            if (data === "DONE") {
                $('#calendar').fullCalendar('refetchEvents');
                $("#ModalAddAppointment").modal("hide");
            }
            else {
                new PNotify({
                    title: 'Network Error. ',
                    text: 'An Error Has Occured ! Please Try Again Later. ',
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
}

function SelectClientChanged() {

    var ClickedDate = document.getElementById("ClickedDate").value;
    var ClientName = document.getElementById("SelectedClientID").options[document.getElementById("SelectedClientID").selectedIndex].text;

    var Title = document.getElementById("AddAppTitle").value;
    var Body = document.getElementById("AddAppBody").value;

    var BodyMod = Body.replace(/\[ClientName\]/g, ClientName);
    BodyMod = BodyMod.replace(/\[NewAppointment\]/g, ClickedDate);
    BodyMod = BodyMod.replace("GMT+0000", "");

    var TitleMod = Title.replace(/\[ClientName\]/g, ClientName);
    TitleMod = TitleMod.replace(/\[NewAppointment\]/g, ClickedDate);
    TitleMod = TitleMod.replace("GMT+0000", "");


    document.getElementById('SelectedClientID').disabled = false;
    $("#AddAppMessageID").hide();
}

function EventClicked(event) {
    if (event["title"] === 'Out Of Schedule') {
        $('#ModalOutOfSchedule').modal('show');
        return false;
    }
    else {
        if (event["title"] === 'Day Off') {
            $('#ModalDayOff').modal('show');
            return false;
        }
        else {
            if (event["title"] === 'Lunch Break') {
                $('#ModalLunchBreak').modal('show');
                return false;
            }
            else {
                if (event["color"] === '#ffd4cc') {
                    $('#ModalHoliday').modal('show');
                    return false;
                }
            }
        }
    }

    $("#SelectedClientID").hide();
    $.ajax({
        url: "/Vitas/Routes/GetCallInfo",
        dataType: "text",
        type: 'POST',
        async: false,
        data: {
            EventID: function () {
                return document.getElementById('SelectedEventID').value;
            }
        },
        success: function (data) {
            $("#ClientAdd").hide();
            $("#ClientEdit").show();
            var Select = $("#SelectedClientEdit")
            $('#SelectedClientEdit')
                    .find('option')
                    .remove();

            var DataArr = data.split("_");
            Select.append($("<option />").val(DataArr[0]).text(DataArr[1]));

            GetClientAddresses(DataArr[0]);
            $("#SelectedClientAddress").val(DataArr[2]);



            $("#SelectedGuarantorID").val(DataArr[3]);
            GetGuarantorsAddresses();
            $("#SelectedGuarantorAddress").val(DataArr[4]);
            $("#SelectedReason").val(DataArr[5]);
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


    $('#ModalAddAppointment').modal('show');



}

function DeleteEvent() {
    $.ajax({
        url: "/Vitas/Routes/DeleteCall",
        dataType: "text",
        type: 'POST',
        async: false,
        data: {
            EventID: function () {
                return document.getElementById('SelectedEventID').value;
            },
        },
        success: function (data) {
            $('#ModalAddAppointment').modal('hide');
            $('#calendar').fullCalendar('refetchEvents')
            new PNotify({
                title: 'Success',
                text: 'Appointment Successfully Deleted. ',
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
    });
}

function AppRejAppCheckbox() {
    if (document.getElementById("AppRejNotifications").checked) {
        $("#AppRejAppMessageID").show(400);
    }
    else {
        $("#AppRejAppMessageID").hide(400);
    }
}

function ResAppCheckbox() {
    if (document.getElementById("RescheduleNotifications").checked) {
        $("#ResAppMessageID").show(400);
    }
    else {
        $("#ResAppMessageID").hide(400);
    }
}

function EditEventEndDate(NewEndDate, AppointmentID, revertFunc) {
    $.ajax({
        url: "/Vitas/Routes/ResizeAppointment",
        dataType: "json",
        type: 'POST',
        async: false,
        data: {
            EventID: function () {
                return AppointmentID;
            },
            EndDate: function () {
                return NewEndDate;
            }
        },
        success: function (data) {
            new PNotify({
                title: 'Success',
                text: 'Appointment Successfully Edited. ',
                type: 'success',
                delay: 1500
            });
        },
        error: function (request, error) {
            revertFunc();
            new PNotify({
                title: 'Network Error. ',
                text: 'An Error Has Occured ! Please Try Again Later. ',
                type: 'error',
                delay: 1500
            });
        }
    });
}

function EditEventStartDate(NewStartDate, NewEndDate, AppointmentID, revertFunc) {

    $.ajax({
        url: "/Vitas/Routes/DragAndDropAppointment",
        dataType: "json",
        type: 'POST',
        async: false,
        data: {
            EventID: function () {
                return AppointmentID;
            },
            StartDate: function () {
                return NewStartDate;
            },
            EndDate: function () {
                return NewEndDate;
            }
        },
        success: function (data) {
            new PNotify({
                title: 'Success',
                text: 'Appointment Successfully Edited. ',
                type: 'success',
                delay: 1500
            });
        },
        error: function (request, error) {
            revertFunc();
            new PNotify({
                title: 'Network Error. ',
                text: 'An Error Has Occured ! Please Try Again Later. ',
                type: 'error',
                delay: 1500
            });
        }
    });
}

function GetClientAddresses(CCode) {
    var ClientCode = document.getElementById('SelectedClientID').value;
    if (CCode !== "") {
        ClientCode = CCode;
    }


    var Select = $("#SelectedClientAddress");

    $('#SelectedClientAddress')
            .find('option')
            .remove();


    $.ajax({
        url: "/Vitas/Routes/GetClientAddresses",
        dataType: "json",
        type: 'POST',
        async: false,
        data: {
            ClientCode: function () {
                return ClientCode;
            },
        },
        success: function (data) {
            var SelectAdd = "";
            for (var i = 0; i < data.result.length; i++) {
                var AddressID = data.result[i].AddressID;
                var Address = data.result[i].Address;
                var IsPrimary = data.result[i].IsPrimary;
                if (i === 0) {
                    SelectAdd = AddressID
                }

                if (IsPrimary === "1") {
                    SelectAdd = AddressID
                }

                Select.append($("<option />").val(AddressID).text(Address));
            }
            $('#SelectedClientAddress').val(SelectAdd);
            GetClientGuarantors();
            ClientHasDues();
        },
        error: function (request, error) {
            revertFunc();
            new PNotify({
                title: 'Network Error. ',
                text: 'An Error Has Occured ! Please Try Again Later. ',
                type: 'error',
                delay: 1500
            });
        }
    });
    SetReason()
}

function GetGuarantorsAddresses() {
    var ClientCode = document.getElementById('SelectedGuarantorID').value;
    var Select = $("#SelectedGuarantorAddress");

    $('#SelectedGuarantorAddress')
            .find('option')
            .remove();


    $.ajax({
        url: "/Vitas/Routes/GetClientAddresses",
        dataType: "json",
        type: 'POST',
        async: false,
        data: {
            ClientCode: function () {
                return ClientCode;
            },
        },
        success: function (data) {
            var SelectAdd = "";

            for (var i = 0; i < data.result.length; i++) {
                var AddressID = data.result[i].AddressID;
                var Address = data.result[i].Address;
                var IsPrimary = data.result[i].IsPrimary;
                if (i === 0) {
                    SelectAdd = AddressID;
                }

                if (IsPrimary === "1") {
                    SelectAdd = AddressID;
                }

                Select.append($("<option />").val(AddressID).text(Address));
            }

            $('#SelectedGuarantorAddress').val(SelectAdd);

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

}

function GetClientGuarantors() {
    var ClientCode = document.getElementById('SelectedClientID').value;
    if ($("#SelectedEventID").val() !== '') {
        ClientCode = $("#SelectedClientEdit").val();
    }
    var Select = $("#SelectedGuarantorID");

    $('#SelectedGuarantorID')
            .find('option')
            .remove();

    $.ajax({
        url: "/Vitas/Routes/GetClientGuarantors",
        dataType: "json",
        type: 'POST',
        async: false,
        data: {
            ClientCode: function () {
                return ClientCode;
            },
        },
        success: function (data) {
            Select.append($("<option />").val("").text("Select A Guarantor"));
            for (var i = 0; i < data.result.length; i++) {
                var ClientCode = data.result[i].ClientCode;
                var ClientName = data.result[i].ClientName;
                Select.append($("<option />").val(ClientCode).text(ClientName));
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

}

function ClientHasDues() {
    var ClientCode = document.getElementById('SelectedClientID').value;
    $.ajax({
        url: "/Vitas/Routes/GetClientHasDues",
        dataType: "text",
        type: 'POST',
        async: false,
        data: {
            ClientCode: function () {
                return ClientCode;
            },
            SelectedDate: function () {
                return moment.utc(document.getElementById("ClickedDate").value).format("YYYY-MM-DD HH:mm:ss");
            },
        },
        success: function (data) {
            if (data === '1') {
                $("#ClientHasDues").val("1");
            }
            else {
                $("#ClientHasDues").val("0");
            }
            SetReason();
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

}

function SetReason() {

    if ($("#SelectedUserType").val() === "4") {
        $("#SelectedReason").val("4");
    }
    if ($("#SelectedUserType").val() === "3") {
        if ($("#ClientHasDues").val() === '1') {
            $("#SelectedReason").val("1");
        }
        else {
            $("#SelectedReason").val("2");
        }
    }

}

function ReloadCalendar() {
    $("#BtnUsers").text("Users");
    $("#SelectedUserCode").val("");
    $("#SelectedUserType").val("");
    $('#calendar').fullCalendar('refetchEvents')
}

function FilterUser() {
    var myGrid = $('#UserGrid');
    var selectedRowId = myGrid.jqGrid('getGridParam', 'selrow');
    var cellValue = myGrid.jqGrid('getCell', selectedRowId, 'UserName');
    var UserCode = myGrid.jqGrid('getCell', selectedRowId, 'UserCode');
    var UserType = myGrid.jqGrid('getCell', selectedRowId, 'UserTypeID');
    $("#BtnUsers").text(cellValue);
    $("#SelectedUserCode").val(UserCode);
    $("#SelectedUserType").val(UserType);
    $("#SelectedUser").val(cellValue);

    $('#calendar').fullCalendar('refetchEvents')
    $("#ModalSelectUser").modal("hide");
}

function FilterNewClient(Type) {
    var myGrid = $('#ClientNewGrid');
    var selectedRowId = myGrid.jqGrid('getGridParam', 'selrow');
    var cellValue = myGrid.jqGrid('getCell', selectedRowId, 'ClientName');
    var UserCode = myGrid.jqGrid('getCell', selectedRowId, 'ClientCode');
    if (Type == "1") {
        $("#GuarantorName").val(cellValue);
        $("#GuarantorCode").val(UserCode);
    } else if (Type == "2") {
        $("#ClientName").val(cellValue);
        $("#ClientCode").val(UserCode);


        $("#GuarantorName").val("");
        $("#GuarantorCode").val("");
    }
    $("#ModalSelectNewClient").modal("hide");
}

function SelectNewClient() {
    $("#ModalSelectNewClient").modal("show");
    setTimeout(function () {
        AdjustGrid('#NewClientListGrid', '#ClientNewGrid');
    }, 50);
}

function SelectUser() {
    $("#ModalSelectUser").modal("show");
    setTimeout(function () {
        AdjustGrid('#UserListGrid', '#UserGrid');
        clearSearchOptionsUsers()
        
    }, 50);
}

function SelectClient() {
    if ($('#SelectedUserCode').val() === "") {
        new PNotify({
            title: 'Error. ',
            text: 'Please Select A User First. ',
            type: 'error',
            delay: 1500
        });
        return false;
    }


    $("#ModalSelectClient").modal("show");
    setTimeout(function () {
        AdjustGrid('#ClientListGrid', '#ClientGrid');
        clearSearchOptionsClients();
    }, 50);
}

function EditRouteAction() {

    var Grid = $('#RoutesGrid');
    var selectedRowId = Grid.jqGrid('getGridParam', 'selrow');
    if (selectedRowId === null) {
        var ErrorString = "<h4>Cannot Perform This Action.</h4>";
        ErrorString += "<p>- Please Select A Row To Edit A Client !</p>";
        $('#myModalLabel').html("Error: ");
        $('#PopupModal').html(ErrorString);
        $('#PopupModalSave').hide();
    }
    else {

        $("#ModalEditRecord").show();

    }

}


function FilterEditRouteFields() {
    var Type = $('#TypeDescription').val();
    $('#tr_ClientCode').hide();
    $('#tr_GuarantorCode').hide();
    $('#tr_PlannedDate').hide();
    if (Type == "1") {
        $('#tr_GuarantorName').show();
        $('#tr_OtherName').hide();
        $('#tr_OtherAddress').hide();
    } else if (Type == "2") {
        $('#tr_GuarantorName').hide();
        $('#tr_OtherName').hide();
        $('#tr_OtherAddress').hide();
    } else {
        $('#tr_GuarantorName').hide();
        $('#tr_OtherName').show();
        $('#tr_OtherAddress').show();
    }
}
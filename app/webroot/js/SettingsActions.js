function ValidateRepportingSettings() {
    $.ajax({
        url: "/Vitas/EntitySettings/UpdateCompanyProfile",
        dataType: "json",
        type: 'POST',
        async: false,
        data: {
            CompanyName: function () {
                return document.getElementById("CompanyName").value;
            },
            CompanyAltName: function () {
                return document.getElementById("CompanyAltName").value;
            },
            Phone: function () {
                return document.getElementById("Phone").value;
            },
            Mobile: function () {
                return document.getElementById("Mobile").value;
            },
            Fax: function () {
                return document.getElementById("Fax").value;
            },
            POBox: function () {
                return document.getElementById("POBox").value;
            },
            Website: function () {
                return document.getElementById("Website").value;
            },
            Email: function () {
                return document.getElementById("Email").value;
            },
            Address: function () {
                return document.getElementById("Address").value;
            }
            
        },
        success: function (data) {
            new PNotify({
                title: 'Success. ',
                text: 'Settings Data Saved Successfully. ',
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


function ValidateMessagingSettings(ContinueFlag) {
    $.ajax({
        url: "/Vitas/EntitySettings/SetMessagingSettings",
        dataType: "json",
        type: 'POST',
        async: false,
        data: {
            BirthDayTitle : function (){
                return $("#BirthDayMessageTitle").val();
            },
            BirthDayBody : function (){
                return $("#BirthDayMessageBody").val();
            },
            AddAppointmentTitle : function (){
                return $("#AddAppointmentTitle").val();
            },
            AddAppointmentBody : function (){
                return $("#AddAppointmentBody").val();
            },
            ApproveAppointmentTitle : function (){
                return $("#ApproveAppointmentTitle").val();
            },
            ApproveAppointmentBody : function (){
                return $("#ApproveAppointmentBody").val();
            },
            RejectAppointmentTitle : function (){
                return $("#RejectAppointmentTitle").val();
            },
            RejectAppointmentBody : function (){
                return $("#RejectAppointmentBody").val();
            },
            RescheduleAppointmentTitle : function (){
                return $("#RescheduleAppointmentTitle").val();
            },
            RescheduleAppointmentBody : function (){
                return $("#RescheduleAppointmentBody").val();
            },
            DeleteAppointmentTitle : function (){
                return $("#DeleteAppointmentTitle").val();
            },
            DeleteAppointmentBody : function (){
                return $("#DeleteAppointmentBody").val();
            }
        },
        success: function (data) {
            new PNotify({
                title: 'Success. ',
                text: 'Settings Data Saved Successfully. ',
                type: 'success',
                delay: 1500
            });
            if (ContinueFlag === 1) {
                $('.nav-tabs a[href="#Logo"]').tab('show');
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
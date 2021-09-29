function EableDisableSlider(Side, Slider, Cell, Checkbox, BreakSlider, BreakCB) {
    if (Checkbox.checked) {
        if (Side === 'Work') {
            $.ajax({
                url: '/Vitas/WorkingDays/EnableDisableDay',
                dataType: "json",
                type: 'POST',
                async: false,
                data: {
                    Day: function () {
                        return Slider.replace("WorkSlider", "");
                    },
                    Operation: function () {
                        return "Enable"
                    },
                    EntityID: function () {
                        return document.getElementById("EntitySelector").value;
                    }
                },
                success: function (data) {
                    PopulatePageLoadData();
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
        if (Side === 'Break') {
            $.ajax({
                url: '/Vitas/WorkingDays/EnableDisableBreak',
                dataType: "json",
                type: 'POST',
                async: false,
                data: {
                    Day: function () {
                        return Slider.replace("BreakSlider", "");
                    },
                    Operation: function () {
                        return "Enable"
                    },
                    EntityID: function () {
                        return document.getElementById("EntitySelector").value;
                    }
                },
                success: function (data) {
                    PopulatePageLoadData();
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
    }
    else {
        if (Side === 'Work') {
            $.ajax({
                url: '/Vitas/WorkingDays/EnableDisableDay',
                dataType: "json",
                type: 'POST',
                async: false,
                data: {
                    Day: function () {
                        return Slider.replace("WorkSlider", "");
                    },
                    Operation: function () {
                        return "Disable";
                    },
                    EntityID: function () {
                        return document.getElementById("EntitySelector").value;
                    }
                },
                success: function (data) {
                    $('#' + Slider).hide();
                    $('#' + Cell).hide();
                    $('#' + BreakSlider).hide();
                    $('#' + BreakCB).attr('checked', false);
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
        if (Side === 'Break') {
            $.ajax({
                url: '/Vitas/WorkingDays/EnableDisableBreak',
                dataType: "json",
                type: 'POST',
                async: false,
                data: {
                    Day: function () {
                        return Slider.replace("BreakSlider", "");
                    },
                    Operation: function () {
                        return "Disable";
                    },
                    EntityID: function () {
                        return document.getElementById("EntitySelector").value;
                    }
                },
                success: function (data) {
                    $('#' + Slider).hide();
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
    }
}

function PopulatePageLoadData() {
    
    $.ajax({
        url: "/Vitas/WorkingDays/GetWorikngHoursData",
        dataType: "json",
        data: {
            EntityID: function () {
                return document.getElementById("EntitySelector").value;
            }
        },
        type: 'POST',
        async: false,
        success: function (data) {
            moment.locale("en-GB");
            var start = moment("00:00", "HH:mm");
            var end = moment("24:00", "HH:mm");
            for (var i = 0; i < data.length; i++) {
                var row = data [i][0];
                var SliderName = row['Day'] + "WorkSlider";
                var Cell = row['Day'] + "BreakCell";
                var BreakSlider = row['Day'] + "BreakSlider";
                var BreakSliderCB = row['Day'] + "BreakCB";
                var WorkSliderCB = row['Day'] + "WorkCB";

                $('#' + SliderName).show();
                $('#' + Cell).show();
                $('#' + WorkSliderCB).attr('checked', true);

                var WorkStart = moment(row['WorkStart'], "HH:mm");
                var WorkEnd = moment(row['WorkEnd'], "HH:mm");

                $("#" + row['Day'] + "Working").ionRangeSlider({
                    type: "double",
                    grid: true,
                    step: 900000,
                    min: start.format("x"),
                    max: end.format("x") - 1000,
                    from: WorkStart.format("x"),
                    to: WorkEnd.format("x"),
                    prettify: function (num) {
                        return moment(num, 'x').format("h:mm A");
                    },
                    onFinish: function (data) {
                        $.ajax({
                            url: '/Vitas/WorkingDays/SetWorkHours',
                            dataType: "json",
                            type: 'POST',
                            async: false,
                            data: {
                                Day: function () {
                                    var inputname = data.input.context.name;
                                    return inputname.replace('Working', '');
                                },
                                From: function () {
                                    return moment(data.from, 'x').format("HH:mm A")
                                },
                                To: function () {
                                    return moment(data.to, 'x').format("HH:mm A")
                                },
                                EntityID: function () {
                                    return document.getElementById("EntitySelector").value;
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
                });

                if (row['Splitted'] === "1") {
                    var SplitStart = moment(row['SplitStart'], "HH:mm");
                    var SplitEnd = moment(row['SplitEnd'], "HH:mm");
                    $('#' + BreakSlider).show();
                    $('#' + BreakSliderCB).attr('checked', true);
                    $("#" + row['Day'] + "Break").ionRangeSlider({
                        type: "double",
                        grid: true,
                        min: start.format("x"),
                        max: end.format("x"),
                        from: SplitStart.format("x"),
                        to: SplitEnd.format("x"),
                        step: 900000,
                        prettify: function (num) {
                            return moment(num, 'x').format("h:mm A");
                        },
                        onFinish: function (data) {
                            console.log(data.input.context.name);
                            $.ajax({
                                url: '/Vitas/WorkingDays/SetBreakHours',
                                dataType: "json",
                                type: 'POST',
                                async: false,
                                data: {
                                    Day: function () {
                                        var inputname = data.input.context.name;
                                        return inputname.replace('Break', '');
                                    },
                                    From: function () {
                                        return moment(data.from, 'x').format("HH:mm A")
                                    },
                                    To: function () {
                                        return moment(data.to, 'x').format("HH:mm A")
                                    },
                                    EntityID: function () {
                                        return document.getElementById("EntitySelector").value;
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
                        },
                    });
                }
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
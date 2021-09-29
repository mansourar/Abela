function PermissionError() {
    new PNotify({
        title: "Permission Denied. ",
        text: "Sorry You Don't Have Enough Rights To Access This Module,",
        type: "error"
    });
}

function GetGridHeight() {
    var window_size = $(window).height() - 225;
    return window_size;
}

function GetGridHeightPopup() {
    var window_size = $(window).height() - 400;
    return window_size;
}

function GetGridHeightWorkingDays() {
    var window_size = $(window).height() - 180;
    return window_size;
}

function GetGridHeightRoutes() {
    var window_size = $(window).height() - 180;
    return window_size;
}

function OpenNavigation(MenuName,SubsName) {
    setTimeout(function () {
        $("#"+MenuName).click();
        document.getElementById(SubsName).className = 'current-page'
    }, 100)
}



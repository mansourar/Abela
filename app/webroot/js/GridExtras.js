function AdjustGrid(GridContainer, GridID) {

    $(window).bind('resize', function () {
        var width = $(GridContainer).width();
        $(GridID).setGridWidth(width);

    });

    $('#menu_toggle').click(function () {
        setTimeout(function () {
            $(GridID).setGridWidth($(GridContainer).width());
        }, 5);
    });


}




function AdjustGridParam(GridContainer, GridID, Padding) {
    $(window).bind('resize', function () {
        var width = $(GridContainer).width() - Padding;
        $(GridID).setGridWidth(width);

    });

    $('#menu_toggle').click(function () {
        setTimeout(function () {
            $(GridID).setGridWidth($(GridContainer).width());
        }, 5);
    });

}


function AdjustGridPopup(GridContainer, GridID) {
    var width = $(GridContainer).width();
    $(GridID).setGridWidth(width);
    $(window).bind('resize', function () {
        var width = $(GridContainer).width();
        $(GridID).setGridWidth(width);
    });
}

function AdjustGridPopupPar(GridContainer, GridID, Val) {
    var width = $(GridContainer).width() - Val;
    $(GridID).setGridWidth(width);
    $(window).bind('resize', function () {
        var width = $(GridContainer).width() - Val;
        $(GridID).setGridWidth(width);
    });
}
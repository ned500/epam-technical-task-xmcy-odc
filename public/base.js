$(document).ready(function(){
    $(".date-fields").datepicker({
        dateFormat: "yy-mm-dd",
    });

    const startDate = $("#main_startDate");
    const endDate = $("#main_endDate");
    startDate.datepicker("option", "maxDate", new Date());
    endDate.datepicker("option", "maxDate", new Date());
    startDate.change(() => endDate.datepicker("option", "minDate", startDate.val()));
    endDate.change(() => startDate.datepicker("option", "maxDate", endDate.val()));
});

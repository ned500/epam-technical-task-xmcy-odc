$(document).ready(function() {
    const dateFields = $(".date-fields");
    dateFields.datepicker({
        dateFormat: "yy-mm-dd",
    });

    const startDate = $("#main_startDate");
    const endDate = $("#main_endDate");
    $('#main_submit').click(() => {if (dateFields.filter((i, f) => !checkDate(f)).length || new Date(startDate.val()).getTime() > new Date(endDate.val()).getTime()) {
        alert('Invalid date!')
       return false;
    }});
    startDate.datepicker("option", "maxDate", new Date());
    endDate.datepicker("option", "maxDate", new Date());
    startDate.change(() => endDate.datepicker("option", "minDate", startDate.val()));
    endDate.change(() => startDate.datepicker("option", "maxDate", endDate.val()));
});

const checkDate = (field) => {
    const dateArray = $(field).val().split('-');
    const year = Number(dateArray[0]);
    const month = Number(dateArray[1]) - 1; // JS use month 0-11
    const day = Number(dateArray[2]);
    const newDate = new Date(year, month, day);
    return year === newDate.getFullYear()
        && month === newDate.getMonth()
        && day === newDate.getDate()
        && newDate.getTime() <= new Date().getTime();
}

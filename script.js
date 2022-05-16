let firstDate = new Date();
let lastDate = new Date();
lastDate.setDate(lastDate.getDate() + 30);

$(window).load(function() {
    $.ajax({
        url: 'script.php',
        type: 'POST',
        data: ({ firstDate: firstDate }),
        dataType: 'html',
        success: function (data) {
            console.log(data)
        }
    });

    $('input').glDatePicker({
        showAlways: true,
        allowYearSelect: false,
        todayDate: new Date(),
        selectableDateRange: [{ from: firstDate, to: lastDate }],
        dowOffset: 1,

        onClick: (function(el, cell, date, data) {
            el.val(date.toLocaleDateString());
        })
    });
});

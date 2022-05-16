var date = new Date();
date.setDate(date.getDate() + 30);

$(window).load(function() {
    $.ajax({
        url: 'script.php',
        method: 'GET',
        data: {
            'firstDate': new Date(),
            'lastDate': date
        },
        success: function (data) {
            console.log(data)
        }
    });

    $('input').glDatePicker({
        showAlways: true,
        allowYearSelect: false,
        todayDate: new Date(),
        selectableDateRange: [{ from: new Date(), to: new Date(date) }],
        dowOffset: 1,

        onClick: (function(el, cell, date, data) {
            el.val(date.toLocaleDateString());
        })
    });
});

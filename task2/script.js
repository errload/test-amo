let D = new Date(); // сегодняшняя дата
const N = 5;
let dateArr = {}; // результирующий объект
let dateCalendar = []; // для вывода дат в календарь

$(window).load(function() {
    fetch('script.php')
        .then(res => res.json())
        .then(data => {

            // перезаисываем массив с новыми значениями
            for (let key in data) {
                // обрезаем время для дальнейшего сравнения
                let date = new Date(key).toLocaleDateString();

                if (data[key] < N) dateArr[date] = 1;
                else dateArr[date] = 0;
            }

            /*
             * заускаем цикл на 30 дней-дат от сегодняшней
             * и проверяем с наличием даты в исходном объекте
             */
            for (let i = 0; i < 30; i++) {
                let date = D.toLocaleDateString(); // обрезаем время

                // если есть такая дата в объекте
                if (date in dateArr) {
                    // и ее значение равно 1, добавляем в массив для календаря
                    if (dateArr[date] === 1) dateCalendar.push({ from: new Date(D), to: new Date(D) });
                // иначе такой даты нет и просто добавляем в массив
                } else dateCalendar.push({ from: new Date(D), to: new Date(D) });

                D.setDate(D.getDate() + 1); // увеличиваем дату на 1 день
            }

            $('input').glDatePicker({
                showAlways: true,
                allowYearSelect: false,
                todayDate: new Date(),
                selectableDateRange: dateCalendar,

                dowOffset: 0,

                onClick: (function(el, cell, date, data) {
                    el.val(date.toLocaleDateString());
                })
            });
        });
});
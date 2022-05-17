// widgetNameIntr - уникальное имя вашего виджета, должно коррелировать с его назначением
widgetColorEdit = function() {
    var widget = this;
    this.code = null;

    this.yourVar = {};
    this.yourFunc = function() {};

    // вызывается один раз при инициализации виджета, в этой функции мы вешаем события на $(document)
    this.bind_actions = function(){
        //пример $(document).on('click', 'selector', function(){});
    };

    // вызывается каждый раз при переходе на страницу
    this.render = function() {
        this.init();
    };

    // вызывается один раз при инициализации виджета, в этой функции мы загружаем нужные данные, стили и.т.п
    this.init = function(){
        // элемент по умолчанию, который ищем
        let threeItem = 2;
        // находим все блоки
        let pipelineStatus = document.querySelectorAll('.pipeline_status');

        // перебираем блоки для поиска нужного
        pipelineStatus.forEach((item, index) => {
            /*
            * на некоторых страницах есть скрытый элемент
            * на них красится второй по счету вместо третьего
            * поэтому здесь указываем какой красить, если находим скрытый
            */
            if (item.classList.contains('h-hidden')) threeItem = 3;
            // если наш по счету не совпадает, пропускаем
            if (index != threeItem) return;

            /*
            * когда попали на нужным нам
            * спускаемся внутрь пока не дойдем до надписи и цвета линии
            */

            // надпись
            let title = item.querySelector('div div div .block-selectable');
            // цвет линии
            let color = item.querySelector('div div .pipeline_status__head_line');

            // меняем цвет надписи на цвет линии
            title.style.color = color.style.color;
        });
    };

    // метод загрузчик, не изменяется
    this.bootstrap = function(code) {
        widget.code = code;
        // если frontend_status не задан, то считаем что виджет выключен
        // var status = yadroFunctions.getSettings(code).frontend_status;
        var status = 1;

        if (status) {
            widget.init();
            widget.render();
            widget.bind_actions();
            $(document).on('widgets:load', function () {
                widget.render();
            });
        }
    }
};
// создание экземпляра виджета и регистрация в системных переменных Yadra
// widget-name - ИД и widgetNameIntr - уникальные названия виджета
yadroWidget.widgets['widget-color'] = new widgetColorEdit();
yadroWidget.widgets['widget-color'].bootstrap('widget-color');

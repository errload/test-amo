// widgetNameIntr - уникальное имя вашего виджета, должно коррелировать с его назначением
widgetSearchInfo = function() {
    var widget = this;
    this.code = null;

    this.yourVar = {};

    this.addMenu = function() {
        // находим поля с телефонами и email адресами
        let fieldPei = document.querySelectorAll('.linked-form__field.linked-form__field-pei');

        // добавляем к каждому в меню новый пункт
        fieldPei.forEach(item => {
            // значение поля
            let fieldValue = item.querySelector('.linked-form__field__value');
            // меню поля
            let jsTip = fieldValue.querySelector('div div .tips__inner.custom-scroll.js-tip-items');
            // если элемент не найден, пропускаем шаг
            if (!jsTip) return;
            // если в элементе уже есть div с таким классом, пропускаем
            if (jsTip.querySelector('.addSearchGoogle')) return;

            // создаем пункт меню
            let div = document.createElement('div');
            div.classList.add('tips-item');
            div.classList.add('js-tips-item');
            div.classList.add('js-cf-actions-item');
            div.classList.add('addSearchGoogle'); // для обработчика
            div.innerHTML = '' +
                '<span class="tips-icon" style="margin-right: 18px;">' +
                '<svg class="list-top-search__icon svg-icon svg-common--filter-search-dims">' +
                '<use xlink:href="#common--filter-search"></use>' +
                '</svg>' +
                '</span>' +
                'Нагуглить';

            div.onclick = function (e) {
                // поднимаемся до элемента со значением поля
                let elem = e.target.parentElement.parentElement.parentElement;
                let value = elem.querySelector('div input').value;

                // открываем страницы поиска
                window.open('http://letmegooglethat.com/?q=' + value, '_blank');
                window.open('https://yandex.ru/search/?text=' + value, '_blank');
            }

            // добавляем в конец меню новый элемент
            jsTip.appendChild(div);
        });
    };

    // this.menuBlank = function () {
    //     // проходим по всем созанным нами классам и вешаем обработчик события на поиск
    //     document.querySelectorAll('.addSearchGoogle').forEach(item => {
    //         item.addEventListener('click', (e) => {
    //             // поднимаемся до элемента со значением поля
    //             let elem = e.target.parentElement.parentElement.parentElement;
    //             let value = elem.querySelector('div input').value;
    //
    //             // открываем страницы поиска
    //             window.open('http://letmegooglethat.com/?q=' + value, '_blank');
    //             window.open('https://yandex.ru/search/?text=' + value, '_blank');
    //
    //             item.removeEventListener('click', this.menuBlank, false);
    //         });
    //     });
    // }

    // вызывается один раз при инициализации виджета, в этой функции мы вешаем события на $(document)
    this.bind_actions = function() {
        // this.menuBlank();
    };

    // вызывается каждый раз при переходе на страницу
    this.render = function() {
        this.addMenu();
        // this.menuBlank();
    };

    // вызывается один раз при инициализации виджета, в этой функции мы загружаем нужные данные, стили и.т.п
    this.init = function() {
        this.addMenu();
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
yadroWidget.widgets['widget-search-info'] = new widgetSearchInfo();
yadroWidget.widgets['widget-search-info'].bootstrap('widget-search-info');

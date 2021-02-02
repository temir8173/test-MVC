Тестовое задание BeeJee - реализация mvc

Суть этого тестового - решить поставленную задачу минимально необходимым набором сущностей. Вам не нужно писать собственный фреймворк. Чересчур сложная архитектура не приветствуется, хотя и не штрафуется. Фактически, хватит пары контроллеров, одной модели (Task или ToDoItem. При желании можно также заявить User), нескольких шаблонов и роутера.

Аккуратность кода - это важно. Убедитесь, что в нем не осталось мусора, отладочных инструкций, закомментированного кода.
Называйте переменные и объекты, чтобы по одному названию был понятен их тип и предназначение.
См. Стив Макконнелл "Совершенный Код" (глава 11): Ссылка на книгу

Должны быть заявлены основные элементы MVC (Model, View, Controller). Количество моделей = количеству логических сущностей.
Обращения к GET/POST или SESSION могут быть только в контроллерах.
Из базы должны выбираться только те записи, с которыми планируем работать. Нельзя выбирать всю таблицу целиком.
Модель должна принимать отфильтрованные данные из контроллера. Также нельзя передавать ей GET или POST целиком.
В шаблонах не должно быть inline стилей и скриптов.
Защита от SQL-инъекций - один из самых "дорогих" пунктов. Обязательно ипользовать параметризированные запросы или ручное экранирование данных пользовательского ввода. Обратите внимание на сортировку.
Любой повторяющийся код - зло. (См. "Предотвращение дублирования кода" раздел 7.1 Макконнелла).
В приложении должна быть одна точка входа. Желательно также разделить код приложения и публичные файлы. Это значит, что нельзя сделать запрос вида domain.name/controllers/MyController.php т.к. исходники лежат выше чем HTTP ROOT.
При написании роутера учтите, что приложение может находиться не в корне сайта (например точкой входа может являться путь some-domain.com/coding-challenges/some-developer-name/index.php).
Ну, и обязательно используйте автозагрузку вместо ручного подключения файлов через require. Автозагрузку можно написать самому, или взять готовый метод.

# News

Агрегатор новостей:
1. Агрегировать новости с нескольких новостных сайтов к примеру ixbt, 3dnews.
-Разбивать их по категориям
-Не учитывать одинаковые новости
2. Добавлять новые фиды (по апи и через консоль)
3. Регистрация пользователя. Добавить возможность настройки избранных категорий и возможность откладывать новости.
4. Апи для получения новостей с фильтрами и разбивкой за сегодня, за неделю или за месяц. Выводить новости из источников в зависимости от популярности у пользователя.
5. Полнотекстовой поиск с учетом морфологии и релевантности. (Sphinx)


# Commands
php artisan command:add_site {url : url сайта с новостями} {name : название сайта}
php artisan command:add_feed {url : url rss страницы} {category : id or string to create} {site : newssite url or id}
php artisan command:parse_rss парсинг новостей с feed'ов

# API
/api/feed/list - список фидов
/api/feed/add - Добавить фид

/api/news/list - список новостей с фильтрами для сортировки (категория, сайт, источник, дата, заголовок, текст)
/api/news/search - список новостей с фильтрами для сортировки (категория, сайт, источник, дата, заголовок, текст + частичное совпадение в заголовке, или в тексте[Sphinx])

/api/user/add - добавление пользователя
/api/user/add/category - добавление(привязка) категории к пользователю
/api/user/favorite - список избранных новостей для пользователя
/api/user/delayed - список отложенных новостей для пользователя







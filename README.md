# GravitLauncher-TextureProvider (JSON)

![PHP 8.3+](https://img.shields.io/badge/PHP-8.3+-blue)
![Gravit Launcher](https://img.shields.io/badge/Gravit%20Launcher-v5.2.9+-brightgreen)

✔ Выдача по USERNAME, UUID, (id пользователя, хеша sha1 и sha256) из БД.

✔ Поддеркжа выдачи из файловой системы, либо по USERNAME с Mojang

✔ Возможность выдавать рандомный скин пользователям, которые ещё не установили его сами

✔ Выдача скина и плаща по умолчанию, если не обнаружен в файловой системе, Mojang и выключено получение скина из рандомной коллекции скинов

✔ Работает с любыми общепринятыми размерами скинов и плащей

✔ Автоматическое обнаружение SLIM типов скинов (тонкие руки)

✔ Встроенный TextureLoader

<p align="center">
    <img src="https://i.imgur.com/q0nkKNj.png" alt="demo" width="642">
</p>

# Поддерживаемые методы

- **`normal`** Отдаёт только из файловой системы, рандомной коллекции скинов (если оное включено), скинов и плащей по умолчанию.
- **`mojang`** Отдаёт текстуры с Mojang
  - Использование в вызове скрипта: **`&method=mojang`**
- **`hybrid`** = **`normal`** + **`mojang`**
  - Использование в вызове скрипта: **`&method=hybrid`**
- **ОБЩЕЕ**
  - Отдача скинов из рандомной коллекции, при отсутствии установленных пользователями. Если включено
  - Отдача скинов и плащей по умолчанию. Если включено

# Требования

- GravitLauncher 5.2.9+
- Консольный доступ SSH к хостингу. Для развёртывания библиотек
- Поддержка Docker (поддерживается всем современным железом, включая примерно архитектуры с 2007-2008 года)

# Установка
## Установка в Docker контейнер:
<img src="https://img.shields.io/badge/docker-2496ED?style=for-the-badge&logo=docker&logoColor=2496ED&label=%D1%83%D1%81%D1%82%D0%B0%D0%BD%D0%BE%D0%B2%D0%BA%D0%B0%20%D0%B8%20%D0%BD%D0%B0%D1%81%D1%82%D1%80%D0%BE%D0%B9%D0%BA%D0%B0%20%D1%81%20%D0%BF%D0%BE%D0%BC%D0%BE%D1%89%D1%8C%D1%8E&labelColor=white" alt="Docker" height="50"/>

- Является более оптимальным вариантом установки, так как все модули и сам php будет установлен в изолированных контейнерах

### Предварительная настройка | Установка Docker
<img src="https://img.shields.io/badge/docker-2496ED?style=for-the-badge&logo=docker&logoColor=2496ED&label=%D1%83%D1%81%D1%82%D0%B0%D0%BD%D0%BE%D0%B2%D0%BA%D0%B0&labelColor=white" alt="Docker" height="35"/>

- Выполнение команд от sudo (Перейти в root, если является пользователем не по умолчанию):
```bash
sudo -s
```
- Следующая команда:
  - Обновляет зависимости
  - Установка утилит
  - Скачивает скрипт установки Docker
  - Выдача прав запуска скрипта и запуск установки Docker
  - Запуск службы
```bash
apt update ;
apt install gnupg2 apt-transport-https curl -y;
curl -fsSL https://get.docker.com -o get-docker.sh ;
chmod +x get-docker.sh ;
./get-docker.sh ;
service docker start
```
<img src="https://img.shields.io/badge/git-F05032?style=for-the-badge&logo=github&logoColor=181717&label=%D0%9A%D0%BB%D0%BE%D0%BD%D0%B8%D1%80%D0%BE%D0%B2%D0%B0%D0%BD%D0%B8%D0%B5%20%D1%80%D0%B5%D0%BF%D0%BE%D0%B7%D0%B8%D1%82%D0%BE%D1%80%D0%B8%D1%8F&labelColor=white" alt="Git" height="35"/>

### Выбираем где будет располагаться скрипт, лучше всего вне сайта. И устанавливаем texture-provider
```bash
git clone --branch new https://github.com/microwin7/GravitLauncher-TextureProvider.git texture-provider
```
```bash
cd texture-provider
```
<img src="https://img.shields.io/badge/docker-2496ED?style=for-the-badge&logo=docker&logoColor=2496ED&label=%D0%98%D0%BD%D0%B8%D1%86%D0%B8%D0%B0%D0%BB%D0%B8%D0%B7%D0%B0%D1%86%D0%B8%D1%8F%20%D0%BA%D0%BE%D0%BD%D1%82%D0%B5%D0%B9%D0%BD%D0%B5%D1%80%D0%BE%D0%B2&labelColor=white" alt="Docker" height="35"/>

### Скачивание слоёв, компиляция и запуск контейнеров php-fpm и nginx:
```bash
docker compose up -d --build
```
### Остановка контейнеров:
```bash
docker compose stop
```
### Запуск контейнеров / Перезапуск (если изменился файл docker-compose.yml):
```bash
docker compose up -d
```
### <img src="https://img.shields.io/badge/NGINX-009639?style=for-the-badge&logo=nginx&logoColor=009639&label=%D0%9D%D0%90%D0%A1%D0%A2%D0%A0%D0%9E%D0%99%D0%9A%D0%90%20%D0%B2&labelColor=white" alt="NGINX" height="35"/>

#### Для установки на сайт:
- Над разделом server {...}
```nginx
upstream dockerTextureProvider {
    server 127.0.0.1:29300;
}
```
- А в разделе server {...}
```nginx
    location /texture-provider/ {
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-Proto $scheme;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header Env-Vendor null;
        proxy_pass http://dockerTextureProvider/;
    }
```
- Изменить **APP_URL** в `.env` - ссылка на домен
#### Для установки на под-домен:
- Пример **`/etc/nginx/conf.d/textures.conf`**:
```nginx
upstream dockerTextureProvider {
    server 127.0.0.1:29300;
}
server {
    listen 80;
    server_name textures.ВАШ_ДОМЕН;
    charset utf-8;

    location / {
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-Proto $scheme;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header Env-Vendor null;
        proxy_pass http://dockerTextureProvider/;
    }
}
```
- Изменить **ВАШ_ДОМЕН**
- Изменить в `.env` домен и протокол `APP_URL=http://127.0.0.1/`
- Изменить в `.env` путь от корня `APP_URL` в `SCRIPT_PATH`
##### Настройка публичного ключа доступа для загрузки скинов и плащей из лаунчера:
- Перейдите в папку лаунчсервера, далее в папку `.keys`. Она может быть скрыта
- Скопируйте себе на ПК файл `ecdsa_id.pub`
- Через сайт [**[ base64.guru ]**](https://base64.guru/converter/encode/file) преобразуйте файл в строку Base64
- Изменить в `.env` `LAUNCH_SERVER_ECDSA256_PUBLIC_KEY_BASE64` строку
##### Настройка использования API загрузки, установите токен в условии обращения к /api
- Изменить в `.env` `BEARER_TOKEN` и в конфиге лаунчсервера в разделе текстур провидера
- Пример использования API:
  - API загрузки скина: /api/upload/SKIN
  - API загрузки плаща: /api/upload/CAPE
```
curl -X POST http://127.0.0.1:29300/api/upload/SKIN \
-H "Authorization: Bearer BEARER_TOKEN" \
-F "username=microwin7" \
-F "uuid=36fdaf1d-c064-4d12-b1c6-ff0fd83636dd" \
-F "hd_allow=false" \
-F "file=@/tmp/phpJVBzIM"
```
  - `BEARER_TOKEN` - заменить на указанный в `.env`
  - Параметр `hd_allow` не обязательный, по умолчанию **true**, можно переобределить в `.env` установив `HD_TEXTURES_ALLOW=false`
##### Смена скина/плаща по умолчанию через API
- Выполнить запрос
```
curl -X POST http://127.0.0.1:29300/api/upload/SKIN \
-H "Authorization: Bearer BEARER_TOKEN" \
-F "username=DEFAULT" \
-F "uuid=00000000-0000-0000-0000-000000000000" \
-F "hd_allow=false" \
-F "file=@/путь/к/скину"
```
Пример заполненного запроса с моего компьютера на хост:
```
curl -X POST https://gravit-support.ru/texture-provider-dev/api/upload/SKIN \
-H "Authorization: Bearer ТОКЕН" \
-F "username=DEFAULT" \
-F "uuid=00000000-0000-0000-0000-000000000000" \
-F "hd_allow=false" \
-F "file=@microwin7.png"
```
- Из полученного ответа скопируйте содержимое digest подписи. Установите новые значения в **.env**
  - Пример значений по умолчанию:
  ```
  SKIN_DEFAULT_SHA256=98805f6ab41575b7ff4af11b70c074773c5bcc210f2429f6b5513150d746e4cd
  CAPE_DEFAULT_SHA256=f2072fdfff5302b7c13672e54fdc8895dc75b3f675be3a43245de6894f971e38
  ```
  - Перезагрузите контейнера. Выполнить в папке `texture-provider`:
  ```
  docker compose restart
  ```

- Подпись домена вы можете выполнить через [**[ CertBot ]**](https://certbot.eff.org/)
#### Перезагрузить NGINX
```bash
service nginx restart
```

# НАСТРОЙКА СКРИПТА
## Описание TextureStorageType's

1. **STORAGE**
  - Локальное файловое хранилище скинов и плащей
  - Имеет 5 типов для определения имени хранимого файла, они же **StorageType**'s:
    - **USERNAME** - [username.png] DEPRECATED
      - Поиск происходит вне зависимости от регистра, если файл не будет найден
    - **UUID** - [uuid.png] DEPRECATED
    - **DB_USER_ID** - [user_id.png] DEPRECATED
    - **DB_SHA1** - [sha1.png] DEPRECATED
    - **DB_SHA256** - [sha256.png] ПО УМОЛЧАНИЮ
  - Текущий API работает с базой данных и хешами скинов и плащей, но на получение самих скинов и плащей для сайта, можно использовать в запросе ник или uuid
    - Пример:
    ```
    https://gravit-support.ru/texture-provider-dev/SKIN/microwin7
    ```
    ```
    https://gravit-support.ru/texture-provider-dev/SKIN/36fdaf1d-c064-4d12-b1c6-ff0fd83636dd
    ```
    ```
    https://gravit-support.ru/texture-provider-dev/CAPE/microwin7
    ```
    ```
    https://gravit-support.ru/texture-provider-dev/CAPE/36fdaf1d-c064-4d12-b1c6-ff0fd83636dd
    ```
    - При этом правильной ссылкой остаётся обращение всё же по установленной хеш-сумме к файлу и нагрузка на API меньше
2. **MOJANG**
  - Поиск текстур в Mojang по **USERNAME**
  - Для использования только этого типа хранения
    - В конце запроса добавьте **`&method=mojang`**
  - Для использования этого типа хранения, вместе со всеми другими
    - В конце запроса добавьте **`&method=hybrid`**
    - Cперва будет поиск по локальному файловому хранилищу, потом Mojang
3. **COLLECTION**
  - Выдава скина из коллекции рандомных скинов, созданную администратором.
  - Последние 12 символов от UUID переводяться в DEC и деляться на количество скинов в коллекции
  - после чего остаток и будет являться номером из коллекции.
  - Включение хранилища в `.env`: **GIVE_FROM_COLLECTION=true**
4. **DEFAULT**
  - Выдача скинов и плащей по умолчанию, если не найдены ни в локальном хранилище, ни в Mojang, ни в коллекции скинов.
  - Включение в `.env`: **GIVE_DEFAULT_SKIN=true** и **GIVE_DEFAULT_CAPE=true**. По умолчанию скины отдаются всегда

## Ссылка на скрипт
- Протокол и `ДОМЕН`/`IP` `.env` константа: **APP_URL**
- Путь от корня домена `.env` константа: **SCRIPT_PATH**. Сделайть пустой **SCRIPT_PATH=** если используете под-домен
## Хранилище текстур
- Хранилище от корня сайта в конфиге `.env`: **STORAGE_DIR**. По умолчанию: storage
### Если у вас своя папка storage
  - Удалите текущую папку storage
  ```bash
  rm -rf storage
  ```
  - Создайте ссылку на папку storage. ПРИМЕР для Azuriom:
  ```bash
  ln -s /var/www/html/Azuriom_SITE/storage/app/public storage
  ```
- Пути от корня хранилища в конфиге `.env`: **TEXTURE_{ТИП_ТЕКСТУРЫ}_PATH**. Примеры есть в `.env.example`
### Для включения поддержки версий 5.2.9-5.4.x
- Включите изменение хеша для старых версий в `.env`: **LEGACY_DIGEST**

## Настройка LaunchServer'a
### При использовании в сайте:
```json
      "textureProvider": {
        "url": "https://example.com/texture-provider/%username%/%uuid%",
        "bearerToken": null,
        "type": "json"
      },
      "mixes": {
        "textureLoader": {
          "urls": {
            "SKIN": "https://example.com/texture-provider/upload/SKIN",
            "CAPE": "https://example.com/texture-provider/upload/CAPE"
          },
          "slimSupportConf": "SERVER",
          "type": "uploadAsset"
        }
      },
```
### При использовании на под-домене:
```json
      "textureProvider": {
        "url": "https://textures.example.com/%username%/%uuid%",
        "bearerToken": null,
        "type": "json"
      },
      "mixes": {
        "textureLoader": {
          "urls": {
            "SKIN": "https://textures.example.com/upload/SKIN",
            "CAPE": "https://textures.example.com/upload/CAPE"
          },
          "slimSupportConf": "SERVER",
          "type": "uploadAsset"
        }
      },
```
## Настройка публичного ключа доступа для загрузки скинов и плащей из лаунчера:
- Перейдите в папку лаунчсервера, далее в папку `.keys`. Она может быть скрыта
- Скопируйте себе на ПК файл `ecdsa_id.pub`
- Через сайт [**[ base64.guru ]**](https://base64.guru/converter/encode/file) преобразуйте файл в строку Base64
- В файле `.env` в корне текстур провидера, выставите переменную, по примеру:
```env
LAUNCH_SERVER_ECDSA256_PUBLIC_KEY_BASE64=MFkwEwYHKoZIzj0CAQYIKoZIzj0DAQcDQgAEJDi51DKs5f6ERSrDDjns00BkI963L9OS9wLA2Ak/nACZCgQma+FsTsbYtZQm4nk+rtabM8b9JgzSi3sPINb8fg==
```

# Описание глобальных переменных для настройки скрипта
- Все доступные параметры находятся в `.env.example` - файл пример, с значениями по умолчанию в текстур провидере и библиотеке. За исключением:
  - `config/php-utils/^1.7.0/MainConfig.php` в котором осталась:
    - Настройка баз данных и таблиц с именами столбцов
    - Настройка списка серверов (Не требуется для текстур провидера)
    - Настройка параметров подключения к бд
  - `config/php-utils/^1.7.0/TextureConfig.php` в котором настраивается:
    - **SKIN_SIZE** - доступные размеры для загрузки обычный скинов
    - **CAPE_SIZE** - доступные размеры для загрузки обычный плащей
    - **SKIN_SIZE_HD** - доступные размеры для загрузки HD скинов
    - **CAPE_SIZE_HD** - доступные размеры для загрузки HD плащей
  - P.S. При использовании Docker, после изменений конфигов, помимо `.env` выполните `up -d --build` снова
```env
## Global Settings
APP_URL=https://gravit-support.ru/ - Ссылка на сайт
ROOT_FOLDER=/var/www/html - Указания корня сайта/корня текстур провидера
SCRIPT_PATH=texture-provider - Указание URL location для ссылок при генерации JSON для лаунчсервера(лаунчера)
## DataBase Settings
DB_HOST=localhost - Хост базы данных (БД)
DB_NAME=test - Имя базы данных
DB_USER=test - Имя пользователя для подключения к БД
DB_PASS=test - Имя пользователя для подключения к БД
# 0-65535 - допустимые порты
DB_PORT=3306 - Порт для подключения к БД
# mysql/pgsql
DB_SUD_DB=mysql - Тип драйвера для работы с БД
DB_PREFIX_SERVERS=server_ - Префикс для баз данных серверов (Не используется для текстур провидера)
DB_DEBUG=true - Включение записи логов запросов и ошибок, которые вызываются в скрипте и поступают в БД
# Logs SQL and Errors
DB_LOG_FOLDER=/var/www/db_logs - Путь для хранения логов

BEARER_TOKEN=null - Токен доступа для ограничения запросов (Выключено)
PRIVATE_API_KEY= - Похожее что и выше (Не используется для текстур провидера)

## SENTRY Settings
SENTRY_ENABLE=true
SENTRY_DSN=https://dce66e1d574a0066589421bda5c36b2f@o484368.ingest.us.sentry.io/4506491534573568

LAUNCH_SERVER_ECDSA256_PUBLIC_KEY_BASE64=MFkwEwYHKoZIzj0CAQYIKoZIzj0DAQcDQgAEJDi51DKs5f6ERSrDDjns00BkI963L9OS9wLA2Ak/nACZCgQma+FsTsbYtZQm4nk+rtabM8b9JgzSi3sPINb8fg==

## ItemShop - Для модуля на сайт DLE (Не используется для текстур провидера)
SITE_TEMPLATES_FOLDER=templates/имя_шаблона
ITEM_SHOP_PATH_IN_TEMPLATES=images/item_shop

## Storage Textures - Настройка Хранилища для текстур провидера
STORAGE_DIR=storage
# SKIN|CAPE|AVATAR|FRONT|FRONT_CAPE|FRONT_WITH_CAPE|BACK|
# BACK_CAPE|BACK_WITH_CAPE|CAPE_RESIZE|MOJANG|COLLECTION
TEXTURE_SKIN_PATH=skins
TEXTURE_CAPE_PATH=capes
TEXTURE_AVATAR_PATH=avatars
TEXTURE_FRONT_PATH=fronts
TEXTURE_FRONT_CAPE_PATH=front_capes
TEXTURE_FRONT_WITH_CAPE_PATH=front_with_capes
TEXTURE_BACK_PATH=backs
TEXTURE_BACK_CAPE_PATH=back_capes
TEXTURE_BACK_WITH_CAPE_PATH=back_with_capes
TEXTURE_CAPE_RESIZE_PATH=cape_resizes
TEXTURE_MOJANG_PATH=mojang
TEXTURE_COLLECTION_PATH=collection
# .png
TEXTURE_EXTENSTION=png - Расширения для хранимых файлов

LEGACY_DIGEST=false - Подпись хеш-сумма файлов, старого образца с версии 5.2.9 до 5.4.x
MAX_SIZE_BYTES=2M - Максимальный размер загружаемого файла, так же изменить в nginx и php-fpm контейнере, если используете Docker. В папке config/
SKIN_DEFAULT_SHA256=98805f6ab41575b7ff4af11b70c074773c5bcc210f2429f6b5513150d746e4cd - SHA256 хеш-сумма скина
CAPE_DEFAULT_SHA256=f2072fdfff5302b7c13672e54fdc8895dc75b3f675be3a43245de6894f971e38 - SHA256 хеш-сумма плаща

## Texture-Provider - Настройки провидера (Описание можно найти в файле src/Config.php)
ROUTERING=true
MINIMIZE_ENUM_REQUEST=false
# null|80-512 Default: 128px
AVATAR_CANVAS=null
# USERNAME - [username.png]
# UUID - [uuid.png]
# DB_USER_ID - [user_id.png] работает только с связью с БД
# DB_SHA1 - [sha1.png] работает только с связью с БД
# DB_SHA256 - [sha256.png] работает только с связью с БД
USER_STORAGE_TYPE=UUID
GIVE_FROM_COLLECTION=false
TRY_REGENERATE_CACHE=true
GIVE_DEFAULT_SKIN=true
GIVE_DEFAULT_CAPE=false
SKIN_RESIZE=true
### Texture-Provider Loader
HD_TEXTURES_ALLOW=true
LUCKPERMS_USE_PERMISSION_HD_SKIN=false
# Min 0
LUCKPERMS_MIN_WEIGHT=10
### Texture-Provider Returner
BLOCK_CANVAS=128
CAPE_CANVAS=16
BOUND_WIDTH_CANVAS=512
# Min 10 sec
IMAGE_CACHE_TIME=null
```
# ПРОЧЕЕ...
### Удаление чересстрочной развёртки. И сжатие
Предупреждения:
- (Эти предупреждения связаны с библиотекой libpng, которая используется для работы с изображениями в формате PNG)
1. `libpng warning: Interlace handling should be turned on when using png_read_image` - Это предупреждение говорит о том, что вам следует включить межстрочное сканирование (interlace) при использовании функции `png_read_image`. Межстрочное сканирование позволяет пошагово загружать изображение, что может быть полезным для оптимизации процесса отображения.

Способ починить:
```bash
apt install optipng
```
- Команды приведены от самой долгой по обратоке к самой быстрой
- Вызывать в папке с скинами или плащами
```bash
find . -type f -iname '*.png' -exec optipng -i0 -o7 -zm1-9 {} \;
find . -type f -iname '*.png' -exec optipng -i0 -o1 -zm1-9 {} \;
find . -type f -iname '*.png' -exec optipng -i0 -o1 {} \;
```

### Починка профиля изображения
Предупреждения:
- (Эти предупреждения связаны с библиотекой libpng, которая используется для работы с изображениями в формате PNG)
1. `libpng warning: iCCP: known incorrect sRGB profile` - Это предупреждение указывает на то, что профиль цвета sRGB в изображении не соответствует ожидаемому или считается некорректным. Возможно, изображение содержит нестандартный профиль цвета, и это может повлиять на отображение цветов.

2. `libpng warning: iCCP: cHRM chunk does not match sRGB` - Это предупреждение также связано с профилем цвета и указывает на то, что информация о цветовом пространстве (cHRM chunk) не соответствует ожидаемому для sRGB. Это может также привести к неправильному отображению цветов.

Способ починить:
```bash
apt install pngcrush
```
- Вызывать в папке с скинами или плащами
```bash
find . -type f -iname '*.png' -exec pngcrush -ow -rem allb -reduce {} \;
```

##  ...БУДЕТ ДОПОЛНЕНО...

- Предположительно команда для использования на PRODUCTION, будет проверяться
  - Оптимизирует импорты и кеширует классы автозагрузчика, если включен OpCache
composer install -n -v -o -a --no-dev
# Битрикс профайлер. Бандл для кастомных вариантов Битрикс + Symfony

Экспериментальное решение частной задачи - прикручивание, взятого за основу [WebProfilerBundle](https://github.com/symfony/web-profiler-bundle)
к кастомным вариантам Битрикс + Symfony (или Wordpress + Symfony)

***INTERNAL***

В процессе разработки.

## Установка

Composer 2.0:

```bash
composer require proklung/web-profilier-bundle
```

Composer 1.0:

composer.json:

```json
    "repositories": [
{
"type": "git",
"url": "https://github.com/proklung/web-profilier-bundle"
}
]
```

## Конфигурирование

Файл `custom-web-profiler.yaml` в `/local/config/packages`

```yaml
custom-web-profiler:
  enabled: true
  profiler_cache_path: '%kernel.cache_dir%/profiler.json'
  # Дополнительный путь к твиговским шаблонам профайлера и коллекторов
  profiler_twig_templates_path:
    - '%kernel.project_dir%/twig/view'
  # Игнорируемые url (частичное совпадение)
  ignoring_url:
    - '/bitrix/admin'
    - 'public_session.php'
    - '/bitrix/urlrewrite.php'
    - '/_profiler'
  
  # Отключение некоторых профайлеров из стандартного набора по коду. Если false, то будет отрублен.
  # По умолчанию они все включены.
  profilers:
    json_response: false
```

### Подключение собственных data collectors

### Эксплуатация

Работает только, если пользователь залогинен админом. Не рекомендуется использовать на проде.

При включении бандла в `standalone_bundles.php` появляется страница `/_profilier/`, где выводятся данные о происходящем
в текущей сессии.

Если [бандл](https://github.com/ProklUng/BitrixSymfonyRouterBundle) не установлен, то профайлер находится по адресу
`/bitrix/admin/_profilier.php` (при инициализации будет единожды скопирован туда).

### Нюансы

Чтобы отслеживать роуты Symfony роутер должен соблюсти два условия:

1) После обработки роута, но до определения, что это роут нативный или симфонический, нужно запустить кастомное событие
   `kernel.after_handle_request`, передав ему в качестве параметра класс, отнаследованный от `Symfony\Contracts\EventDispatcher\Event`
   и реализущий два метода - `getRequest` и `getResponse`.

2) Перед выходом из скрипта, после обработки симфонического роутера должно инициироваться битриксовое событие `OnAfterEpilog`,
   где в качестве параметров передается `response` и `request`.

В моем изыскательном [бандле](https://github.com/ProklUng/BitrixSymfonyRouterBundle) все это поддерживается.

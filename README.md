Strona wyświetla się pod adresem http://task-viceversa.local dlatego należy dodać go do hostsów

```
127.0.0.1 task-viceversa.local
```
### Lokalizacja pliku hosts
Windows:
```
C:\Windows\System32\drivers\etc\hosts
```

Linux/Mac:
```
/etc/hosts
```
# Plik .env
Wartość do dodania do pliku .env.local do połączenia z bazą
```
DATABASE_URL="mysql://viceversa:password@db:3306/viceversa-books?serverVersion=9.0.1-MariaDB&charset=utf8mb4"
```

# Instalacja 

## Utworzenie kontenerów dockerowych (uruchamiane z głównego folderu projektu)
```
docker compose build
docker compose up -d
```
Pozostałe polecenia wykonywane są już z kontenera `php_web`

## Composer
```
composer i
```

## Migracje
```
php bin/console doctrine:migrations:migrate
```
## Uruchomienie fixtur
```
php bin/console doctrine:fixtures:load -vv --no-debug
```
## Uruchamianie testów
```
php bin/phpunit
```

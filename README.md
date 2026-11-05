# Instrukcja uruchomienia projektu

Aplikacja w Laravel do zarządzania klientami, produktami i zamówieniami z generowaniem wydruków PDF oraz kodów QR dla pozycji magazynowych.

---

## 1. Wymagania środowiskowe

- **PHP** >= 8.2 (wymagane rozszerzenia: `pdo_sqlite`, `mbstring`, `openssl`, `xml`, `ctype`, `curl`)
- **Composer** >= 2.x
- **Node.js** >= 18.x oraz **NPM**
- **SQLite 3**

---

## 2. Krok po kroku: Instalacja i uruchomienie


### Krok 1: Instalacja zależności backendowych (Composer)
```bash
composer install
```

### Krok 2: Konfiguracja pliku środowiskowego `.env`
Skopiuj plik `.env.example` do `.env`:
```bash
cp .env.example .env
```
Upewnij się, że w pliku `.env` ustawione są parametry bazy SQLite oraz portu:
```ini
APP_NAME="Aplikacja w Laravel do zarządzania klientami, zamówieniami i produktami"
APP_ENV=local
APP_URL=http://127.0.0.1:8010

DB_CONNECTION=sqlite
```

### Krok 3: Wygenerowanie klucza aplikacji
```bash
php artisan key:generate
```

### Krok 4: Migracje bazy danych i zasilenie danymi testowymi (Seedery)
Wykonaj migracje wraz z wypełnieniem bazy przykładowymi danymi:
```bash
php artisan migrate:fresh --seed
```

### Krok 5: Instalacja i kompilacja assetów frontendowych (Vite / Tailwind CSS)
```bash
npm install
npm run build
```

### Krok 6: Uruchomienie lokalnego serwera deweloperskiego
```bash
php artisan serve --host=127.0.0.1 --port=8010
```

Aplikacja będzie dostępna w przeglądarce pod adresem:
**http://127.0.0.1:8010**

---

## 3. Dane logowania konta testowego (Administrator)

- **Adres E-mail:** `admin@example.com`
- **Hasło:** `password123`

---

## 4. Główne adresy URL w aplikacji

- **Ekran powitalny:** `http://127.0.0.1:8010/`
- **Formularz logowania:** `http://127.0.0.1:8010/login`
- **Pulpit główny:** `http://127.0.0.1:8010/dashboard`
- **Zarządzanie klientami (CRUD):** `http://127.0.0.1:8010/clients`
- **Zarządzanie produktami (CRUD):** `http://127.0.0.1:8010/products`
- **Zarządzanie zamówieniami (CRUD):** `http://127.0.0.1:8010/orders`
- **Generowanie wydruku PDF`{id_zamowienia}|{id_produktu}`:** `http://127.0.0.1:8010/orders/{id}/print`

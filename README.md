# CRM Loyihasi

Bu loyiha Vue.js frontend va Symfony backend'dan iborat CRM tizimi.

## Talablar

- Node.js 18+ va npm
- PHP 8.2+
- Composer
- MySQL yoki PostgreSQL

## O'rnatish

### Backend (Symfony)

```bash
cd crm-api
composer install
cp .env .env.local
# .env.local faylida DATABASE_URL ni sozlash kerak
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
php bin/console app:create-admin
php -S localhost:8000 -t public
```

### Frontend (Vue.js)

```bash
npm install
npm run dev
```

Frontend `http://localhost:3000` da ishga tushadi.

## Foydalanish

1. Admin foydalanuvchi yaratish:
   - Email: `admin@crm.local`
   - Parol: `Admin12345`

2. Tizimga kirish va foydalanuvchilar, kompaniyalar va mijozlarni boshqarish.

## API Endpoints

- `/api/users` - Foydalanuvchilar
- `/api/companies` - Kompaniyalar
- `/api/clients` - Mijozlar
- `/api/login` - Tizimga kirish


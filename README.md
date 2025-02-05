# Laravel Project Setup

## Prerequisites
- Docker
- Docker Compose
- Git

## Quick Setup

1. Clone the repository:
```bash
git clone https://github.com/tc-vlad-iacovenco/NotePadExtra
cd NotePadExtra
```

2. Copy environment file:
```bash
cp .env.example .env
```

3. Start Docker containers:
```bash
docker-compose up -d
```

4. Install dependencies:
```bash
docker-compose exec php_fpm composer install
```

5. Generate application key:
```bash
docker-compose exec php_fpm php artisan key:generate
```

6. Run migrations and seeders:
```bash
docker-compose exec php_fpm php artisan migrate --seed
```

# Clean PHP Smarty Blog

Simple blog project on clean PHP, Smarty and MySQL.

## Run docker

Start containers:

```bash
docker compose up -d --build
```

Create tables:

```bash
docker compose exec -T mysql mysql -ublog -pblog_password blog < database/schema.sql
```

Add test data:

```bash
docker compose exec php php database/seed.php
```

Open:

```text
http://localhost:8080
```

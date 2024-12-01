<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

Make sure you have Docker and Laravel installed locally before running the project.

### Steps to run  the project

- Create `.env` and copy all the contents of `.env.example` to it
- Install dependencies `composer install`
- Generate key `php artisan key:generate`
- Run migrations `php artisan migrate`
- Create Docker network for inter-container communication `docker network create app_network`
- Start the project `docker-compose up --build`

### Optional

1. If you need to reset and re-run all migrations `php artisan migrate:fresh`

2. Create test user

`php artisan tinker`
`User::create(['name' => 'Test User', 'email' => 'test@example.com', 'password' => bcrypt('password123')]);`

### Test API

```bash
# Login

curl -X POST http://localhost:8000/api/login \
-H "Content-Type: application/json" \
-d '{"email":"test@example.com","password":"password123"}'

# Register

```bash
curl -X POST http://localhost:8000/api/register \
-H "Content-Type: application/json" \
-d '{
  "name": "Test User",
  "email": "test@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}'
```

### Pages

```bash
# List all pages
curl -X GET http://localhost:8000/api/v1/pages

# Get single page
curl -X GET http://localhost:8000/api/v1/pages/1

# Create page
curl -X POST http://localhost:8000/api/v1/pages \
-H "Content-Type: application/json" \
-d '{"title":"Test Page","body":"Test content"}'

# Update page
curl -X PUT http://localhost:8000/api/v1/pages/1 \
-H "Content-Type: application/json" \
-d '{"title":"Updated Title","body":"Updated content"}'

# Delete page
curl -X DELETE http://localhost:8000/api/v1/pages/1
```

### Media

```bash
# List all media
curl -X GET http://localhost:8000/api/v1/media

# Upload media
curl -X POST http://localhost:8000/api/v1/media \
-F "file=@/path/to/file.jpg" \
-F "slug=custom-slug"

# Get single media
curl -X GET http://localhost:8000/api/v1/media/1

# Delete media
curl -X DELETE http://localhost:8000/api/v1/media/1

# Add slug to media
curl -X POST http://localhost:8000/api/v1/media/1/slug \
-H "Content-Type: application/json" \
-d '{"slug":"new-slug"}'

# Get media by slug
curl -X GET http://localhost:8000/api/v1/media/slug/custom-slug
```

### Page-Media Relations

```bash
# Attach media to page
curl -X POST http://localhost:8000/api/v1/pages/1/media \
-H "Content-Type: application/json" \
-d '{"media_id":1}'

# Detach media from page
curl -X DELETE http://localhost:8000/api/v1/pages/1/media \
-H "Content-Type: application/json" \
-d '{"media_id":1}'
```

### Dashboard stats

```bash
# Get dashboard stats
curl -X GET http://localhost:8000/api/v1/dashboard/stats
```

Note: For authenticated endpoints, add the Authorization header:
```bash
-H "Authorization: Bearer YOUR_TOKEN"
```

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

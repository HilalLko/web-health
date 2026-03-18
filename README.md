# Website Health Monitor

A comprehensive, real-time website health monitoring tool built with Laravel 12, Inertia.js, Vue 3, and Tailwind CSS. Monitor HTTP status, SSL certificates, DNS resolution, security headers, performance metrics, and more—all without requiring user registration.

![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=flat-square&logo=laravel)
![Vue.js](https://img.shields.io/badge/Vue.js-3.x-4FC08D?style=flat-square&logo=vue.js)
![Tailwind CSS](https://img.shields.io/badge/Tailwind-4.x-38B2AC?style=flat-square&logo=tailwind-css)
![Pest](https://img.shields.io/badge/Pest-3.x-00C58E?style=flat-square)

## ✨ Features

### 🔍 Comprehensive Health Checks
- **HTTP Status & Performance**: Status codes, response times, and uptime monitoring
- **SSL Certificate Validation**: Expiration dates, issuers, and validity checks
- **DNS Resolution**: Resolution time, record types (A, AAAA, MX, NS), and error detection
- **Security Headers Analysis**: CSP, X-Frame-Options, HSTS, X-Content-Type-Options, Referrer-Policy, Permissions-Policy
- **Lighthouse Audits**: Performance, Accessibility, Best Practices, and SEO scores via Google PageSpeed Insights API
- **Core Web Vitals**: LCP, FID, CLS, FCP, TTI, and TBT metrics
- **Broken Links Scanner**: Detects broken links on the homepage (limited to 50 links)

### 🎨 Premium UI/UX
- Modern glassmorphism design with gradient backgrounds
- Dark mode support
- Smooth animations and transitions
- Fully responsive (mobile-first design)
- Real-time updates via Laravel Reverb broadcasting

### 📊 Advanced Features
- **Real-Time Monitoring**: Optional periodic checks every 5 minutes
- **PDF Report Generation**: Download comprehensive health reports
- **Session-Based History**: View recent checks (no database required)
- **Rate Limiting**: 5 requests per IP per minute for abuse prevention
- **SSRF Protection**: Blocks local and private IP addresses

### 🔒 Security
- URL validation (HTTP/HTTPS only)
- SSRF protection (blocks 127.0.0.1, localhost, 10.x.x.x, 192.168.x.x, 172.16-31.x.x)
- Rate limiting (5 requests/minute per IP)
- No data persistence (cache/session only, expires after 1 hour)

## 📋 Requirements

- PHP 8.2 or higher
- Composer 2.x
- Node.js 18.x or higher
- npm 9.x or higher
- Redis (for queues, cache, and sessions)
- Google PageSpeed Insights API key (free tier available)

## 🚀 Installation

### 1. Clone the Repository

```bash
git clone <repository-url>
cd website-health-monitor
```

### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install
```

### 3. Environment Configuration

```bash
# Copy the example environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Configure Environment Variables

Edit `.env` and configure the following:

```env
# Application
APP_NAME="Website Health Monitor"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

# Database (SQLite for minimal setup)
DB_CONNECTION=sqlite

# Cache, Session, and Queue (Redis)
CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# Broadcasting (Reverb)
BROADCAST_CONNECTION=reverb

REVERB_APP_ID=<your-app-id>
REVERB_APP_KEY=<your-app-key>
REVERB_APP_SECRET=<your-app-secret>
REVERB_HOST=localhost
REVERB_PORT=8080
REVERB_SCHEME=http

VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME="${REVERB_SCHEME}"

# Google PageSpeed Insights API
PAGESPEED_API_KEY=<your-api-key>
```

### 5. Get Google PageSpeed Insights API Key

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project or select an existing one
3. Enable the **PageSpeed Insights API**
4. Create credentials (API Key)
5. Copy the API key to your `.env` file

### 6. Build Assets

```bash
# Development
npm run dev

# Production
npm run build
```

### 7. Start Services

You'll need to run multiple services. The easiest way is to use the built-in development script:

```bash
composer run dev
```

This starts:
- Laravel development server (http://localhost:8000)
- Queue worker
- Reverb WebSocket server
- Vite dev server

**Or start each service manually:**

```bash
# Terminal 1: Laravel server
php artisan serve

# Terminal 2: Queue worker
php artisan queue:work

# Terminal 3: Reverb server
php artisan reverb:start

# Terminal 4: Vite dev server
npm run dev
```

## 🧪 Testing

Run the test suite with Pest:

```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Unit
php artisan test --testsuite=Feature

# Run with coverage
php artisan test --coverage
```

## 📖 Usage

### Basic Health Check

1. Navigate to `http://localhost:8000`
2. Enter a valid URL (e.g., `https://example.com`)
3. Click "Analyze Website"
4. View comprehensive results including:
   - HTTP status and response time
   - SSL certificate details
   - DNS resolution metrics
   - Security headers analysis
   - Lighthouse scores
   - Core Web Vitals
   - Broken links (if any)

### Real-Time Monitoring

1. Check the "Enable Real-Time Monitoring" checkbox
2. Submit the form
3. The system will automatically re-check the URL every 5 minutes
4. Results update in real-time via WebSocket

### Download PDF Report

1. After running a health check, click "Download PDF Report"
2. A comprehensive PDF with all metrics will be generated

## 🏗️ Architecture

### Backend

- **Controllers**: `HealthMonitorController` handles HTTP requests
- **Services**: `HealthCheckService` performs all health checks
- **Jobs**: `PerformHealthCheckJob` processes checks asynchronously
- **Events**: `HealthCheckUpdated` broadcasts results via Reverb
- **Cache**: Results stored in Redis (1-hour expiration)

### Frontend

- **Inertia.js**: SPA-like experience without API complexity
- **Vue 3**: Reactive components with Composition API
- **Tailwind CSS**: Utility-first styling with custom design system
- **Laravel Echo**: Real-time WebSocket communication

### Queue System

- **Driver**: Redis
- **Jobs**: Health checks run asynchronously to avoid blocking UI
- **Real-Time**: Delayed jobs for periodic monitoring

### Broadcasting

- **Driver**: Laravel Reverb (WebSocket server)
- **Channels**: Public channels (no authentication required)
- **Events**: Real-time health check updates

## 🔧 Configuration

### Rate Limiting

Modify `routes/web.php` to adjust rate limits:

```php
Route::middleware(['web', 'throttle:5,1'])->group(function () {
    // 5 requests per minute
});
```

### Cache Expiration

Modify `app/Services/HealthCheckService.php`:

```php
Cache::put($cacheKey, $results, 3600); // 1 hour
```

### Broken Links Limit

Modify `app/Services/HealthCheckService.php`:

```php
if ($linkCount >= 50) break; // Limit to 50 links
```

## 🚀 Deployment

### Production Setup

1. **Environment**:
   ```bash
   APP_ENV=production
   APP_DEBUG=false
   ```

2. **Optimize**:
   ```bash
   composer install --optimize-autoloader --no-dev
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   npm run build
   ```

3. **Queue Worker** (use Supervisor):
   ```ini
   [program:website-health-monitor-worker]
   process_name=%(program_name)s_%(process_num)02d
   command=php /path/to/artisan queue:work --sleep=3 --tries=3
   autostart=true
   autorestart=true
   user=www-data
   numprocs=2
   redirect_stderr=true
   stdout_logfile=/path/to/storage/logs/worker.log
   ```

4. **Reverb Server** (use Supervisor):
   ```ini
   [program:website-health-monitor-reverb]
   command=php /path/to/artisan reverb:start
   autostart=true
   autorestart=true
   user=www-data
   redirect_stderr=true
   stdout_logfile=/path/to/storage/logs/reverb.log
   ```

5. **Web Server**: Configure Nginx/Apache to serve `public/index.php`

## 📝 API Reference

### Routes

| Method | URI | Description |
|--------|-----|-------------|
| GET | `/` | Home page |
| POST | `/check` | Submit URL for health check |
| GET | `/download/{urlHash}` | Download PDF report |

### Broadcasting Channels

| Channel | Event | Description |
|---------|-------|-------------|
| `health-check.{sessionId}` | `HealthCheckUpdated` | Real-time health check results |

## 🤝 Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📄 License

This project is open-source software licensed under the [MIT license](LICENSE).

## 🙏 Acknowledgments

- [Laravel](https://laravel.com) - The PHP Framework
- [Inertia.js](https://inertiajs.com) - The Modern Monolith
- [Vue.js](https://vuejs.org) - The Progressive JavaScript Framework
- [Tailwind CSS](https://tailwindcss.com) - Utility-First CSS Framework
- [Pest](https://pestphp.com) - Elegant PHP Testing Framework
- [Google PageSpeed Insights](https://developers.google.com/speed/docs/insights/v5/get-started) - Performance Metrics API

## 📧 Support

For issues, questions, or suggestions, please [open an issue](https://github.com/your-repo/issues).

---

Made with ❤️ using Laravel, Vue.js, and Tailwind CSS

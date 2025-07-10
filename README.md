# Music Player App

A modular Music Player built with:
- Vuew Inertia Laravel Tailwind using Jetstream Package `laravel/jetstream`
- Vue 3 + Tailwind CSS (frontend)
- Laravel Modular Components `nwidart/laravel-modules`

---

## Feature Releases & Incremental Delivery

### MVP (v1.0)
- Users can register and login
- Lists all songs from the database
- Basic player controls:
  - Play / Pause (state only)
  - Next / Previous

---

### Shuffle & History (v1.1)
- Toggle shuffle mode
- Plays random unplayed song, ensuring no repeats until all are played
- Previous button retrieves from full database history

---

### Advanced Play Order (v1.2)
- Guarantees each song plays once before repeating
- Partial safeguard to avoid same artist in shuffle



### Testing
Unit tests using PHPUnit 10+ include:

 - Play adds song to history
 - Next plays only unplayed songs, resets after all are played
 - Previous restores last song
 - Shuffle toggles state and plays random song

Run tests with:
```
    php artisan test --testsuite=Modules
```


### Testing
Install & Run
```
    composer install
    npm install
    php artisan migrate --seed
    npm run dev
    php artisan serve
```

### Things To Do

## Code Quality & Analysis
 - Use Rector for auto-upgrades & type improvements
 - PHPStan for static analysis (level 8 recommended)
 - ECS (Easy Coding Standard) for PSR-12 style enforcement
# Example
``` 
    vendor/bin/phpstan analyse
    vendor/bin/ecs check src
    vendor/bin/rector process
```

## Security & Vulnerabilities
Use `composer audit` and `npm audit` to scan dependencies

## AWS Deployment (Beanstalk-ready) 
Not yet deployed due to AWS free tier and inactive credit card, but fully prepared for CI/CD pipeline deployment.

## CLI Deployment Plan
```
    pip install awsebcli --upgrade
    aws configure
    eb --version

    # Initialize application
    eb init
    # Choose region, enter application name, select platform (PHP)

    # Create environment and deploy
    eb create music-env
    eb deploy

    # Configure .ebextensions for environment variables & DB configs

```

## CI Pipeline Ready
 - Typical pipeline tasks:
 - Run PHPStan, ECS, Rector, Tests
 - Run composer audit and npm audit
 - Build assets (npm run build)
 - Deploy with eb deploy on success


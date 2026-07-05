# Deployment Guide

This document covers how to deploy SimplyFact to a production environment. The recommended and tested approach is **Laravel Cloud**.
Docker and GitHub Container Registry are also documented as alternative options.

## Before You Deploy — Optimization

Before deploying, optimize the application to clear compiled files and reduce the project's footprint:

```bash
php artisan optimize
```

To clear all previously cached files instead:

```bash
php artisan optimize:clear
```

## Option 1 — Laravel Cloud (Recommended)

Laravel Cloud is a fully managed, auto-scaling deployment platform built specifically for Laravel applications.

**Key advantage:** Once linked to your GitHub account, it automatically redeploys on every push to the `main` branch — no manual steps required after the initial setup.

→ [Laravel Cloud documentation](https://laravel.com/cloud)

### Environment variables on Laravel Cloud

Set all required environment variables directly in the Laravel Cloud dashboard. Pay particular attention to:

- `APP_ENV=production` and `APP_DEBUG=false`
- Your database connection settings
  (Don't forget to change this line in `config/database.php`)

```php
'default' => env('DB_CONNECTION', 'mysql'),
```

- Your S3 credentials (see the S3 section in the README)
- Your production mail provider credentials

> Mailpit is a local development tool and is **not compatible** with production environments. You must configure a transactional email provider (e.g. Mailgun, Amazon SES, or Resend) before deploying.

## Option 2 — Docker

Docker can be used to containerize and deploy the application on any infrastructure that supports containers.

### Recommended folder structure

Organize your Docker configuration as follows:

```
├── docker/
│   ├── common/
│   │   └── php-fpm/
│   │       ├── Dockerfile
│   │       └── conf.d/
│   │           └── 20-status-path.conf
│   ├── development/
│   └── production/
│       ├── php-fpm/
│       │   └── entrypoint.sh
│       ├── workspace/
│       │   └── Dockerfile
│       └── nginx/
│           ├── Dockerfile
│           └── nginx.conf
├── compose.dev.yaml
├── compose.prod.yaml
└── .dockerignore
```

### What to configure

- A **PHP Dockerfile** that builds an optimized image of the application.
- A **Docker Compose file** (`compose.prod.yaml`) that defines services (PHP-FPM, Nginx, database), volumes, and networks.

### Build and run

```bash
docker compose -f compose.prod.yaml up --build -d
```

→ [Laravel + Docker guide](https://docs.docker.com/guides/frameworks/laravel/)

## Option 3 — GitHub + GHCR (GitHub Container Registry)

If the project is already versioned on GitHub, GitHub Container Registry (GHCR) provides an integrated way to manage Docker images alongside your repository.

Combined with **GitHub Actions**, this approach enables a full Continuous Deployment pipeline: each push to `main` automatically builds a new Docker image and deploys it, with no additional tooling required.

→ [GHCR documentation](https://docs.github.com/en/packages/working-with-a-github-packages-registry/working-with-the-container-registry)

## Deployment Checklist

Before going live, verify the following:

- `APP_ENV=production` and `APP_DEBUG=false` are set
- `php artisan optimize` has been run
- Database migrations are up to date (`php artisan migrate --force`)
- S3 bucket and IAM credentials are configured
- A production mail provider is configured (Mailpit must not be used)
- `APP_KEY` is set and not exposed publicly

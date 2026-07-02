# Deploy

To deploy this solution a few things to know :

## Optimization

First off, to be able to hide a few file that you won't want public and assure yourself that the project is the smallest possible, run the command :

```
php artisan optimize
```

or to remove all old cache files :

```
php artisan optimize:clear
```

## Laravel Cloud

To deploy this project you can use Laravel Cloud, a fully-managed, auto-scalling deployment platform.

One of it's strong point is that, linked to your github account, it will self deploy when a push is made on the main branch allowing instant deployment as soon as possible.

[Laravel Cloud](https://laravel.com/cloud)

## Docker

To deploy this project, you can also use Docker (compose and Dockerfile)

If you choose this solution, be carefull to first set up a specialized folder for docker :

```
── docker/
│   ├── common/
│   │   └── php-fpm/
│   │       ├── Dockerfile
│   │       └── conf.d/
│   │           └── 20-status-path.conf
│   ├── development/
│   ├── production/
│   │   ├── php-fpm/
│   │   │   └── entrypoint.sh
│   │   ├── workspace/
│   │   │   └── Dockerfile
│   │   └── nginx
│   │       ├── Dockerfile
│   │       └── nginx.conf
├── compose.dev.yaml
├── compose.prod.yaml
├── .dockerignore
```

Then create a Dockerfile for PHP, that will create an optimized image of your project. And then create a Docker Compose configuration to define services, volumes and networks as well as an environnement where your project will work perfectly.

Then run :

```
 docker compose -f compose.prod.yaml up --build -d
```

> [source](https://docs.docker.com/guides/frameworks/laravel/)

## GitHub

Another solution would be to use Github.
While the project is on line and versionned on GitHub, a good practice would be to use GitHub's own Docker managing service : **GHCR**

[GHCR](https://docs.github.com/en/packages/working-with-a-github-packages-registry/working-with-the-container-registry)

It basically is the same as a Docker Image manager but associated with GitHub and GitHub Actions help for a more comprehensive Continu Deployement as each push on the main branch would then create and then deploy a new Image.

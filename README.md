# StandUp Bot ![Develop](https://github.com/FriendsOfAgile/standUpBot/workflows/Develop/badge.svg)

## Project setup


```
# Install Symfony CLI
curl -sS https://get.symfony.com/cli/installer | bash

# Install all component depencies
composer install

# Install Node depencies
npm install

# Copy .env example
cp .env.dist .env

# Edit at least DATABASE_URL at `.env` file
```

### Compiles and hot-reloads for development
```
# Run symfony server in background
symfony server:start -d

# Run webpack watcher
npm run dev-server
```

### Compiles and minifies for production
```
npm run build
```

### Lints and fixes files
```
npm run lint
```
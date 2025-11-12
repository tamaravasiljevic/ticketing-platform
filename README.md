# Ticketing Platform

A ticketing platform built with Laravel
Author: Tamara Vasiljevic

## Table of Contents

* [Overview](#overview)
* [Features](#features)
* [Architecture & Technologies](#architecture--technologies)
* [Getting Started](#getting-started)

    * [Prerequisites](#prerequisites)
    * [Installation](#installation)
    * [Configuration](#configuration)
    * [Running the application](#running-the-application)
* [Usage](#usage)
* [Testing](#testing)
* [Contributing](#contributing)
* [License](#license)
* [Changelog](#changelog)

## Overview

This project is a **ticketing platform** built with Laravel. It provides a web-based system for managing tickets, events, or support issues using a modern PHP framework, Dockerized setup, and frontend tools.

## Features

* User registration, authentication & roles (admin / user)
* Ticket creation & tracking
* Event or ticket listing, filtering, status updates
* Dockerized deployment environment
* Database migrations & seeders
* Frontend build with modern tooling (React)
* Tests included

## Architecture & Technologies

* **Backend**: Laravel (PHP)
* **Frontend**: React
* **Containerization**: Docker, docker-compose
* **Web Server**: Nginx
* **Database**: MySQL/Postgres
* Standard Laravel project structure

## Getting Started

### Prerequisites

* Docker & Docker Compose
* PHP
* Node.js & npm/yarn
* Composer

### Installation

1. Clone the repo:

   ```bash
   git clone https://github.com/tamaravasiljevic/ticketing-platform.git
   cd ticketing-platform
   ```
2. Copy environment file:

   ```bash
   cp .env.example .env
   ```
3. Install backend dependencies:

   ```bash
   composer install
   ```
4. Install frontend dependencies & build assets:

   ```bash
   npm install
   npm run dev
   ```
5. Bring up containers:

   ```bash
   docker-compose up -d
   ```
6. Generate application key:

   ```bash
   php artisan key:generate
   ```
7. Run migrations & seeders:

   ```bash
   php artisan migrate --seed
   ```

### Configuration

Update `.env` with database credentials and app configuration:

* `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
* `APP_URL`, `APP_ENV`, `APP_DEBUG`

### Running the application

* Docker: visit `http://localhost:8080`
* Local: `docker compose upm -d`
* Ensure frontend assets are served via Vite dev server or static build

## Usage

* Register a new user or login as seeded admin
* Create, view, edit, or update tickets
* Admin can manage users, tickets, events

## Testing

Run backend tests with PHPUnit:

```bash
php artisan test
```

## Contributing

1. Fork the repository
2. Create a branch: `git checkout -b feature/my-feature`
3. Commit changes
4. Push branch
5. Open a Pull Request

## License

MIT License (confirm in LICENSE file)

## Changelog

See `CHANGELOG.md` for updates and improvements.

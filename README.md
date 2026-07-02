# SimplyFact

> A web application for managing expense reports and reimbursements, built for the French Speleology Federation (FFS).

## Academic Context

This project was developed as part of several applied courses during the first semester of 2026. It served as a hands-on opportunity to put the following disciplines into practice:

- Web application development
- Laravel + PHP with Inertia.js
- File storage with S3, local email testing with Mailpit, and PDF generation
- Unit, integration, functional, and end-to-end testing
- Deployment on Laravel Cloud

## What is SimplyFact?

The French Speleology Federation (FFS) needs to process expense reports and reimbursements submitted by all of its affiliated associations. Until now, this was handled through an Excel file — an approach that was both hard to access and difficult to use for most people.

SimplyFact is the web-based solution built to address that problem. It guides users through a step-by-step flow — selecting expenses, following a guided path, and confirming their submission — making the process as straightforward as possible for every member.

> _Photos used on the home and end page are the property of the FFS._

## Tech Stack

| Layer         | Technologies                                 |
| ------------- | -------------------------------------------- |
| Frontend      | React, TypeScript, Tailwind CSS, Material UI |
| Backend       | Laravel (PHP), Inertia.js                    |
| Storage       | Amazon S3 (signed URLs)                      |
| Email (local) | Mailpit                                      |
| Output        | PDF generation                               |

## Project Structure

```
├── app/          # Backend application logic
├── database/     # Migrations and factories
├── docs/         # Design and planning documents (group work artifacts)
├── lang/         # Translation into different language (French included for this project)
├── resources/    # Frontend source
├── routes/    # API routes
└── tests/        # All test suites (unit, integration, feature, E2E)
```

## Installation

### Prerequisites

Make sure the following are installed on your machine:

- PHP >= 8.2
- Composer
- Node.js & npm
- A configured database (MySQL or SQLite for local development)

### Steps

**1. Clone the repository**

```bash
git clone <repository-url>
cd simplyfact
```

**2. Install PHP dependencies**

```bash
composer install
```

**3. Install JavaScript dependencies**

```bash
npm install
```

**4. Set up your environment file**

```bash
cp .env.example .env
php artisan key:generate
```

Then open `.env` and update the following values to match your local environment:

```env
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=simplyfact
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

**5. Run database migrations**

```bash
php artisan migrate
```

**6. Start the development server**

```bash
composer run dev
```

The application will be available at [http://localhost:8000](http://localhost:8000).

## Email — Local Testing with Mailpit

Mailpit is used to intercept and inspect outgoing emails during local development. It does **not** replace a production mail provider.

**Install Mailpit:** https://mailpit.axllent.org/docs/install/

**Update your `.env`:**

```env
MAIL_MAILER=smtp
MAIL_SCHEME=null
MAIL_HOST=127.0.0.1
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_FROM_ADDRESS="noreply@simplyfact.fr"
MAIL_FROM_NAME="${APP_NAME}"
MAIL_TO_ACCOUNTANT="comptable@ffs.fr"
```

**Start Mailpit:**

```bash
mailpit
```

The Mailpit UI will be available at [http://localhost:8025](http://localhost:8025).

## File Storage — Amazon S3

SimplyFact uses Amazon S3 with signed URLs to securely handle uploaded files.

**Setup steps:**

1. Create an S3 bucket on AWS.
2. Create an IAM user with the appropriate permissions for that bucket.
3. Fill in the following variables in your `.env`:

```env
AWS_ACCESS_KEY_ID=your_access_key
AWS_SECRET_ACCESS_KEY=your_secret_access_key
AWS_DEFAULT_REGION=eu-west-3
AWS_BUCKET=simplyfact
AWS_USE_PATH_STYLE_ENDPOINT=false
```

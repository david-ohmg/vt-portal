# OHMG Voice Talent Portal

A Laravel + Livewire web portal that gives OHMG's voice talent a self-service
place to review assigned work, upload finished audio, and manage their files —
while keeping OHMG's internal batch-tracking system as the source of truth.

## What it does

- **Batch review** — talent log in and see their assigned script batches and
  AA (automated attendant) batches, pulled live from the OHMG API.
- **File upload** — talent upload finished recordings for a batch directly to
  S3-compatible storage; uploads are recorded in the portal database and
  linked back to the batch.
- **File management** — talent can browse, and manage files they've
  previously uploaded.
- **Archive** — completed/archived batches are viewable separately from
  active work.
- **Profile** — basic account/profile info for the logged-in talent.
- **Email notifications** — uploading files triggers a notification email
  (via `PortalMail`) to the studio contact and the talent, cc'd, with the
  uploaded files attached.

## Architecture

- **Framework**: Laravel 12, with [Livewire](https://livewire.laravel.com/) v4
  powering the portal pages (`resources/views/pages/portal/⚡*.blade.php`).
  Livewire full-page components are registered via `Route::livewire(...)` in
  `routes/web.php`.
- **Auth**: Session-based auth against the local `users` table
  (`App\Http\Controllers\AuthController`). Each user has a `vt_id` that maps
  them to a voice-talent/female-VT record in the external OHMG system; this
  is stashed in an `ohmg-vt-id` cookie on login.
- **External API**: `App\Services\OhmgApiService` talks to OHMG's
  batch-tracking API (token auth) to fetch/update script batches
  (`batches/batches`) and AA batches (`aa-tracking/aa-tracking`), including
  their archived variants.
- **File storage**: `App\Services\FileUploadService` streams uploads to an
  S3-compatible disk (`filesystems.disks.s3`), storing script-batch files
  under `voice-files/` and AA-batch files under `aa-files/{batch_id}/`, and
  records each upload in the local `my_files` table (`App\Models\MyFiles`).
- **Notifications**: `App\Services\UploadNotificationService` builds and
  sends the upload-confirmation email through `App\Mail\PortalMail`.
  Outbound mail can be sent via SMTP or SendGrid (the `sendgrid/sendgrid`
  package is included as a dependency).
- **Local models**: alongside `User` and `MyFiles`, there are local mirrors
  of OHMG batch/item data (`AABatch`, `AAFile`, `AAItem`, `ScriptBatch`,
  `ScriptFile`, `ScriptItem`) used for relations and portal-side bookkeeping.

## Requirements

- PHP 8.2+
- Composer
- Node.js / npm
- An S3-compatible bucket (AWS S3 or equivalent)
- Access to the OHMG batch-tracking API (URL + auth token)
- A mail sender (SMTP or SendGrid)

## Setup

```bash
composer run setup   # installs PHP/JS deps, copies .env, generates app key,
                      # runs migrations, and builds frontend assets
```

This runs, in order: `composer install`, copy `.env.example` to `.env`,
`php artisan key:generate`, `php artisan migrate --force`, `npm install`,
`npm run build`.

Then configure the environment variables below in `.env` before logging in.

### Environment variables

In addition to the standard Laravel/database/mail variables in
`.env.example`, this app requires:

| Variable | Purpose |
| --- | --- |
| `OHMG_API_URL` | Base URL of the OHMG batch-tracking API |
| `OHMG_AUTH_TOKEN` | Token used to authenticate requests to that API |
| `AWS_ACCESS_KEY_ID` / `AWS_SECRET_ACCESS_KEY` | S3 credentials for file uploads |
| `AWS_DEFAULT_REGION` / `AWS_BUCKET` | S3 bucket/region for uploads |
| `AWS_URL` / `AWS_ENDPOINT` / `AWS_USE_PATH_STYLE_ENDPOINT` | Optional, for S3-compatible providers |

## Development

```bash
composer run dev
```

Runs the PHP dev server, queue listener, log tailer (`pail`), and Vite dev
server concurrently.

## Testing

```bash
php artisan test
```

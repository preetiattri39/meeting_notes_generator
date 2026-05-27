# AI Meeting Notes Generator

AI Meeting Notes Generator is an AI-powered Laravel 12 application for turning meeting audio and video into structured meeting intelligence. It supports transcription, summarization, action item extraction, decision tracking, speaker highlights, exports, team sharing, audit logs, and realtime processing updates.

## Stack

- Laravel 12
- PHP 8.2
- MySQL
- Tailwind CSS 4
- Livewire 4
- OpenAI API
- Laravel Reverb / Echo
- DOMPDF and PHPWord for exports

## Features

- Secure authentication and user management
- Audio/video upload pipeline
- Queue-based AI transcription and summarization
- Action items, decisions, and highlights extraction
- Search and filter meeting history
- PDF and DOCX export
- Team collaboration and meeting sharing
- Realtime processing status updates
- Notifications when summaries are ready
- Admin analytics dashboard
- Audit logging

## Setup

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
```

Set these values in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=notebuddy
DB_USERNAME=root
DB_PASSWORD=

OPENAI_API_KEY=
OPENAI_BASE_URL=https://api.openai.com/v1
OPENAI_TRANSCRIPTION_MODEL=gpt-4o-transcribe-diarize
OPENAI_SUMMARY_MODEL=gpt-4.1-mini

BROADCAST_CONNECTION=reverb
QUEUE_CONNECTION=database
REVERB_APP_ID=workpulse
REVERB_APP_KEY=workpulse-key
REVERB_APP_SECRET=workpulse-secret
REVERB_HOST=localhost
REVERB_PORT=8080
REVERB_SCHEME=http
REVERB_SERVER_HOST=0.0.0.0
REVERB_SERVER_PORT=8080
VITE_REVERB_APP_KEY=${REVERB_APP_KEY}
VITE_REVERB_HOST=${REVERB_HOST}
VITE_REVERB_PORT=${REVERB_PORT}
VITE_REVERB_SCHEME=${REVERB_SCHEME}
```

## Run

```bash
php artisan serve
php artisan queue:work
php artisan reverb:start
npm run dev
```

## Notes

- If `OPENAI_API_KEY` is missing, the app uses a safe fallback summary/transcript so the pipeline remains demoable.
- The migration order has been adjusted for MySQL foreign key compatibility.

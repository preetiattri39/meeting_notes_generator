# AI Meeting Notes Generator

AI Meeting Notes Generator is an AI-powered SaaS application that converts meeting audio and video recordings into organized meeting notes, summaries, and actionable insights. The platform automatically transcribes speech, extracts key discussion points, generates summaries, identifies action items, and highlights important decisions using advanced AI models.

## Features

* Audio and video upload support
* AI speech-to-text transcription
* AI-generated meeting summaries
* Action item extraction
* Key discussion highlights
* Speaker identification
* Meeting history management
* Search and filter meetings
* Download notes as PDF/DOCX
* Team collaboration and sharing
* Real-time processing updates
* Notifications system
* Secure authentication
* Responsive SaaS-style dashboard
* Admin panel and analytics

## Tech Stack

* Laravel 12
* PHP 8.2
* MySQL
* Tailwind CSS
* Vue.js / Livewire
* OpenAI API
* Laravel Sanctum
* Laravel Reverb/WebSockets
* REST APIs

## Installation

### Clone Repository

```bash id="vhh1ji"
git clone https://github.com/yourusername/ai-meeting-notes-generator.git
cd ai-meeting-notes-generator
```

### Install Dependencies

```bash id="7a2zcg"
composer install
npm install
```

### Configure Environment

```bash id="3r2v0n"
cp .env.example .env
php artisan key:generate
```

Update database settings in `.env`

```env id="63u5x7"
DB_DATABASE=meeting_notes
DB_USERNAME=root
DB_PASSWORD=
```

Add OpenAI API Key:

```env id="j4ukn4"
OPENAI_API_KEY=your_api_key
```

### Run Migrations

```bash id="78e8wl"
php artisan migrate
```

### Start Application

```bash id="v16zji"
php artisan serve
npm run dev
```

## Core Modules

### User Features

* Upload meeting recordings
* View transcripts
* Access AI summaries
* Download meeting notes
* Search previous meetings
* Manage profile and settings

### Admin Features

* User management
* Meeting analytics
* AI usage monitoring
* System reports
* File management

## Future Enhancements

* Multi-language transcription
* AI meeting insights
* Calendar integration
* Zoom/Google Meet integration
* Voice-based AI assistant
* Mobile application

## Author

Preeti Attri

## License

Private Project – All Rights Reserved.

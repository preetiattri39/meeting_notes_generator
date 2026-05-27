<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $meeting->title }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #0f172a; font-size: 12px; }
        h1, h2 { margin-bottom: 8px; }
        .section { margin-top: 24px; }
        .pill { display: inline-block; background: #e2e8f0; padding: 4px 8px; border-radius: 999px; margin-right: 6px; font-size: 10px; }
        li { margin-bottom: 6px; }
    </style>
</head>
<body>
    <h1>{{ $meeting->title }}</h1>
    <p>{{ $meeting->category }} | {{ $meeting->status }} | {{ $meeting->created_at->format('M d, Y H:i') }}</p>
    <div>
        @foreach ($meeting->tags as $tag)
            <span class="pill">{{ $tag->name }}</span>
        @endforeach
    </div>

    <div class="section">
        <h2>Summary</h2>
        <p>{!! nl2br(e($meeting->summary_markdown ?: 'No summary generated.')) !!}</p>
    </div>

    <div class="section">
        <h2>Action Items</h2>
        <ul>
            @foreach ($meeting->actionItems as $item)
                <li>{{ $item->description }} @if($item->assignee_name) - {{ $item->assignee_name }} @endif</li>
            @endforeach
        </ul>
    </div>

    <div class="section">
        <h2>Decisions</h2>
        <ul>
            @foreach ($meeting->decisions as $decision)
                <li>{{ $decision->decision }}</li>
            @endforeach
        </ul>
    </div>

    <div class="section">
        <h2>Highlights</h2>
        <ul>
            @foreach ($meeting->highlights as $highlight)
                <li>{{ $highlight->content }}</li>
            @endforeach
        </ul>
    </div>
</body>
</html>

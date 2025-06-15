<!DOCTYPE html>
<html>
<head>
    <title>All Courses</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>All Courses</h2>
    @foreach($courses as $course)
        <div class="card mb-4">
            <div class="card-header">
                <h4>{{ $course['title'] }}</h4>
                <small>Category: {{ $course['category'] ?? 'N/A' }}</small>
            </div>
            <div class="card-body">
                <p>{{ $course['description'] }}</p>

                <h5>Modules:</h5>
                @foreach($course['modules'] as $module)
                    <div class="border p-2 mb-2">
                        <strong>{{ $module['title'] }}</strong>
                        <ul class="mt-2">
                            @foreach($module['contents'] as $content)
                                <li>
                                    <strong>{{ ucfirst($content['type']) }}:</strong>
                                    @if($content['type'] === 'image')
                                        <br>
                                        <img src="{{ Storage::url($content['data']) }}" width="150" />
                                    @else
                                        {{ $content['data'] }}
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
</div>
</body>
</html>

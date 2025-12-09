@extends('layouts.app')

@section('title', 'Service Logs')
@section('page-title', 'Service Logs')
@section('page-description')
    Viewing logs for: <strong>{{ $service }}</strong>
@endsection

@section('page-actions')
    <a href="{{ route('services.index') }}" class="btn btn-outline-secondary me-2">
        <i class="bi bi-arrow-left me-2"></i> Back
    </a>
    <button class="btn btn-outline-primary" onclick="location.reload()">
        <i class="bi bi-arrow-clockwise me-2"></i> Refresh
    </button>
@endsection

@section('content')
<div class="container-fluid py-4">

    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <span><i class="bi bi-terminal me-2"></i> Journal Logs (Last 100 lines)</span>
            <div class="btn-group btn-group-sm">
                <a href="{{ route('services.logs', ['service' => $service, 'lines' => 50]) }}" class="btn btn-sm btn-outline-light">50</a>
                <a href="{{ route('services.logs', ['service' => $service, 'lines' => 100]) }}" class="btn btn-sm btn-outline-light active">100</a>
                <a href="{{ route('services.logs', ['service' => $service, 'lines' => 200]) }}" class="btn btn-sm btn-outline-light">200</a>
                <a href="{{ route('services.logs', ['service' => $service, 'lines' => 500]) }}" class="btn btn-sm btn-outline-light">500</a>
            </div>
        </div>
        <div class="card-body p-0">
            <pre class="log-viewer mb-0"><code>{{ $logs }}</code></pre>
        </div>
    </div>
</div>

<style>
.log-viewer {
    background: #1e1e1e;
    color: #d4d4d4;
    padding: 1.5rem;
    border-radius: 0 0 0.375rem 0.375rem;
    max-height: 600px;
    overflow-y: auto;
    font-size: 0.85rem;
    line-height: 1.6;
    font-family: 'Courier New', monospace;
}

.log-viewer::-webkit-scrollbar {
    width: 8px;
}

.log-viewer::-webkit-scrollbar-track {
    background: #2d2d2d;
}

.log-viewer::-webkit-scrollbar-thumb {
    background: #555;
    border-radius: 4px;
}

.log-viewer::-webkit-scrollbar-thumb:hover {
    background: #777;
}
</style>
@endsection

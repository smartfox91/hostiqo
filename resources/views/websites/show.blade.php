@extends('layouts.app')

@section('title', $website->name . ' - Git Webhook Manager')
@section('page-title', $website->name)
@section('page-description', ucfirst($website->project_type) . ' Website Details')

@section('page-actions')
    <a href="{{ route('websites.index', ['type' => $website->project_type]) }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Back to List
    </a>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <!-- Basic Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Basic Information</h5>
                </div>
                <div class="card-body">
                    
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Name:</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $website->name }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Domain:</strong>
                        </div>
                        <div class="col-md-8">
                            <code>{{ $website->domain }}</code>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Project Type:</strong>
                        </div>
                        <div class="col-md-8">
                            <span class="badge bg-{{ $website->project_type_badge }}">
                                {{ $website->project_type === 'php' ? 'PHP' : 'Node.js' }}
                            </span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Version:</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $website->version_display }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Status:</strong>
                        </div>
                        <div class="col-md-8">
                            <span class="badge bg-{{ $website->status_badge }}">
                                {{ $website->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Nginx Status:</strong>
                        </div>
                        <div class="col-md-8">
                            <span class="badge bg-{{ $website->nginx_status_badge }}">
                                {{ ucfirst($website->nginx_status) }}
                            </span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>SSL Enabled:</strong>
                        </div>
                        <div class="col-md-8">
                            @if($website->ssl_enabled)
                                <span class="badge bg-success">
                                    <i class="bi bi-shield-check me-1"></i> Yes
                                </span>
                            @else
                                <span class="badge bg-secondary">
                                    <i class="bi bi-shield-x me-1"></i> No
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>SSL Status:</strong>
                        </div>
                        <div class="col-md-8">
                            <span class="badge bg-{{ $website->ssl_status_badge }}">
                                {{ ucfirst($website->ssl_status) }}
                            </span>
                        </div>
                    </div>

                    @if(config('services.cloudflare.enabled'))
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <strong>CloudFlare DNS Status:</strong>
                            </div>
                            <div class="col-md-8">
                                <span class="badge bg-{{ $website->dns_status_badge }}">
                                    {{ ucfirst($website->dns_status) }}
                                </span>
                                @if($website->dns_status === 'active' && $website->server_ip)
                                    <small class="text-muted ms-2">→ {{ $website->server_ip }}</small>
                                @endif
                                @if($website->dns_error)
                                    <div class="alert alert-danger alert-sm mt-2 mb-0">
                                        <small>{{ $website->dns_error }}</small>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if($website->project_type === 'node')
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <strong>PM2 Status:</strong>
                            </div>
                            <div class="col-md-8">
                                <span class="badge bg-{{ $website->pm2_status_badge }}">
                                    {{ ucfirst($website->pm2_status) }}
                                </span>
                                @if(config('app.env') === 'local')
                                    <small class="text-muted ms-2">(Control via webhook post-deploy)</small>
                                @endif
                            </div>
                        </div>
                    @endif

                    <hr class="my-4">

                    <!-- Path Configuration -->
                    <h5 class="card-title mb-4">Path Configuration</h5>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Root Path:</strong>
                        </div>
                        <div class="col-md-8">
                            <code>{{ $website->root_path }}</code>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>{{ $website->project_type === 'php' ? 'Working Directory:' : 'Run opt:' }}</strong>
                        </div>
                        <div class="col-md-8">
                            <code>{{ $website->working_directory ?? $website->root_path }}</code>
                        </div>
                    </div>

                    @if($website->project_type === 'node' && $website->port)
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <strong>Port:</strong>
                            </div>
                            <div class="col-md-8">
                                {{ $website->port }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Timestamps -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-clock me-2"></i>Timestamps</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Created At:</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $website->created_at->format('d M Y, h:i A') }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Last Updated:</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $website->updated_at->format('d M Y, h:i A') }}
                        </div>
                    </div>
                </div>
            </div>

            @if($website->project_type === 'node' && config('app.env') !== 'local')
                <!-- PM2 Process Control -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-hdd-rack me-2"></i>PM2 Process Control
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <p class="mb-2">
                                    <strong>Application:</strong> <code>{{ str_replace('.', '-', $website->domain) }}</code>
                                </p>
                                <p class="mb-2">
                                    <strong>Config:</strong> <code>/etc/pm2/ecosystem.{{ str_replace('.', '-', $website->domain) }}.config.js</code>
                                </p>
                                <p class="mb-0">
                                    <strong>Current Status:</strong>
                                    <span class="badge bg-{{ $website->pm2_status_badge }} ms-2">
                                        {{ ucfirst($website->pm2_status) }}
                                    </span>
                                </p>
                            </div>
                            <div class="col-md-6 text-end">
                                <div class="btn-group" role="group">
                                    <form action="{{ route('websites.pm2-start', $website) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success" title="Start or restart PM2 application">
                                            <i class="bi bi-play-circle me-1"></i> Start
                                        </button>
                                    </form>
                                    <form action="{{ route('websites.pm2-restart', $website) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-warning" title="Restart PM2 application">
                                            <i class="bi bi-arrow-clockwise me-1"></i> Restart
                                        </button>
                                    </form>
                                    <form action="{{ route('websites.pm2-stop', $website) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-danger" title="Stop PM2 application">
                                            <i class="bi bi-stop-circle me-1"></i> Stop
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <hr class="my-3">
                        <div class="alert alert-info mb-0">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Note:</strong> These controls manage the PM2 process directly. For automated deployment, configure the post-deploy script in your webhook settings.
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-lightning me-2"></i>Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <form action="{{ route('websites.toggle-ssl', $website) }}" method="POST" class="d-grid">
                            @csrf
                            @method('POST')
                            <button type="submit" class="btn btn-{{ $website->ssl_enabled ? 'outline-success' : 'outline-primary' }}">
                                <i class="bi bi-shield-check me-2"></i> 
                                {{ $website->ssl_enabled ? 'SSL Enabled' : 'Enable SSL' }}
                            </button>
                        </form>
                        
                        @if(config('services.cloudflare.enabled'))
                            <form action="{{ route('websites.dns-sync', $website) }}" method="POST" class="d-grid">
                                @csrf
                                <button type="submit" class="btn btn-{{ $website->dns_status === 'active' ? 'outline-success' : 'outline-secondary' }}" title="Sync DNS record with Cloudflare">
                                    <i class="bi bi-hdd-network me-2"></i> 
                                    {{ $website->dns_status === 'active' ? 'DNS Synced' : 'Sync DNS' }}
                                </button>
                            </form>
                        @endif

                        <form id="redeploy-form" action="{{ route('websites.redeploy', $website) }}" method="POST" class="d-grid">
                            @csrf
                            <button type="button" 
                                    class="btn btn-warning"
                                    onclick="confirmAction('Redeploy Configuration', 'Regenerate and redeploy Nginx and PHP-FPM configurations for {{ $website->domain }}?', 'Yes, redeploy!', 'question').then(confirmed => { if(confirmed) this.closest('form').submit(); })">
                                <i class="bi bi-rocket-takeoff-fill me-2"></i> Redeploy Config
                            </button>
                        </form>

                        <a href="{{ route('websites.edit', $website) }}" class="btn btn-primary">
                            <i class="bi bi-pencil me-2"></i> Edit Website
                        </a>

                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="bi bi-trash me-2"></i> Delete Website
                        </button>
                    </div>
                </div>
            </div>

            <!-- Quick Tips -->
            <div class="card bg-light">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-lightbulb me-2"></i>Quick Tips</h5>
                </div>
                <div class="card-body">
                    @if($website->project_type === 'php')
                        <h6>PHP Website</h6>
                        <p class="small">This website uses PHP {{ $website->php_version ?? 'System Default' }} with its own PHP-FPM pool for isolated resource management.</p>
                    @else
                        <h6>Node.js Application</h6>
                        <p class="small">This Node.js app runs on port {{ $website->port }} and is managed by PM2 for automatic restarts and monitoring.</p>
                    @endif

                    <h6 class="mt-3">SSL Certificate</h6>
                    <p class="small">@if($website->ssl_enabled)SSL is enabled. Certificates auto-renew every 90 days via Let's Encrypt.@elseTo enable HTTPS, click the "Enable SSL" button. Let's Encrypt certificate will be requested automatically.@endif</p>

                    @if(config('services.cloudflare.enabled'))
                        <h6 class="mt-3">Cloudflare DNS</h6>
                        <p class="small">@if($website->dns_status === 'active')DNS A record is synced pointing to {{ $website->server_ip }}.@elseClick "Sync DNS" to create/update the DNS A record in Cloudflare.@endif</p>
                    @endif

                    <h6 class="mt-3">Redeploy</h6>
                    <p class="small">Use "Redeploy Config" if you've manually changed files or need to regenerate Nginx/PHP-FPM configurations.</p>

                    @if($website->project_type === 'node')
                        <h6 class="mt-3">PM2 Management</h6>
                        <p class="small">Control your Node.js application with Start, Restart, or Stop buttons. Check status before making changes.</p>
                    @endif

                    <h6 class="mt-3">Configuration Files</h6>
                    <p class="small">Nginx: <code>/etc/nginx/sites-available/{{ $website->domain }}</code><br>
                    @if($website->project_type === 'php')PHP-FPM: <code>/etc/php/{{ $website->php_version }}/fpm/pool.d/{{ str_replace('.', '-', $website->domain) }}.conf</code>@endif
                    @if($website->project_type === 'node')PM2: <code>/etc/pm2/ecosystem.{{ str_replace('.', '-', $website->domain) }}.config.js</code>@endif</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete <strong>{{ $website->name }}</strong>?</p>
                    <p class="text-muted small">This will only remove the configuration from the database. The actual files and directories will not be deleted.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('websites.destroy', $website) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.app')

@section('title', '1-Click WordPress Deployment - ' . $website->domain)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-2">
                        <i class="bi bi-wordpress me-2"></i> 1-Click WordPress Deployment
                    </h1>
                    <p class="text-muted mb-0">
                        <a href="{{ route('websites.show', $website) }}" class="text-decoration-none">
                            <i class="bi bi-arrow-left me-1"></i> {{ $website->domain }}
                        </a>
                    </p>
                </div>
            </div>

            <!-- Installation Status Card -->
            <div class="card mb-4" id="installationStatusCard" style="display: none;">
                <div class="card-header bg-primary text-white">
                    <i class="bi bi-hourglass-split me-2"></i> Installation in Progress
                </div>
                <div class="card-body">
                    <div class="progress mb-3" style="height: 25px;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" 
                             id="installProgress" 
                             role="progressbar" 
                             style="width: 0%">
                            0%
                        </div>
                    </div>
                    
                    <div id="installationSteps">
                        <!-- Steps will be populated dynamically -->
                    </div>
                    
                    <div id="installationResult" class="mt-3" style="display: none;">
                        <!-- Result will be shown here -->
                    </div>
                </div>
            </div>

            <!-- Installation Form -->
            <form id="wordpressInstallForm">
                @csrf
                
                <!-- Database Configuration -->
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="bi bi-database me-2"></i> Database Configuration
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="db_name" class="form-label">Database Name <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="db_name" 
                                           name="db_name" 
                                           value="{{ str_replace(['.', '-'], '_', $website->domain) }}_wp"
                                           pattern="[a-zA-Z0-9_]+"
                                           required>
                                    <div class="form-text">Alphanumeric and underscores only</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="db_user" class="form-label">Database User <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="db_user" 
                                           name="db_user" 
                                           value="{{ str_replace(['.', '-'], '_', $website->domain) }}_user"
                                           pattern="[a-zA-Z0-9_]+"
                                           maxlength="32"
                                           required>
                                    <div class="form-text">Max 32 characters</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="db_password" class="form-label">Database Password <span class="text-danger">*</span></label>
                                    <input type="password" 
                                           class="form-control" 
                                           id="db_password" 
                                           name="db_password" 
                                           minlength="8"
                                           required>
                                    <div class="form-text">Minimum 8 characters</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="db_host" class="form-label">Database Host</label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="db_host" 
                                           name="db_host" 
                                           value="localhost"
                                           placeholder="localhost">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="db_prefix" class="form-label">Table Prefix</label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="db_prefix" 
                                           name="db_prefix" 
                                           value="wp_"
                                           pattern="[a-zA-Z0-9_]+"
                                           maxlength="20">
                                    <div class="form-text">Default: wp_</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- WordPress Admin Configuration -->
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="bi bi-person-lock me-2"></i> WordPress Admin Account
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="admin_user" class="form-label">Admin Username <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="admin_user" 
                                           name="admin_user" 
                                           pattern="[a-zA-Z0-9_]+"
                                           maxlength="60"
                                           required>
                                    <div class="form-text">Avoid using "admin" for security</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="admin_email" class="form-label">Admin Email <span class="text-danger">*</span></label>
                                    <input type="email" 
                                           class="form-control" 
                                           id="admin_email" 
                                           name="admin_email" 
                                           required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="admin_password" class="form-label">Admin Password <span class="text-danger">*</span></label>
                                    <input type="password" 
                                           class="form-control" 
                                           id="admin_password" 
                                           name="admin_password" 
                                           minlength="8"
                                           required>
                                    <div class="form-text">Strong password recommended</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="site_title" class="form-label">Site Title <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="site_title" 
                                           name="site_title" 
                                           value="{{ ucwords(str_replace(['.', '-', '_'], ' ', explode('.', $website->domain)[0])) }}"
                                           required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Optimization Settings -->
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="bi bi-speedometer2 me-2"></i> Optimization & Features
                    </div>
                    <div class="card-body">
                        <div class="form-check mb-3">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   id="enable_cache" 
                                   name="enable_cache" 
                                   value="1" 
                                   checked>
                            <label class="form-check-label" for="enable_cache">
                                <strong>Enable FastCGI Cache</strong>
                                <br><small class="text-muted">High-performance page caching (Recommended)</small>
                            </label>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   id="install_plugins" 
                                   name="install_plugins" 
                                   value="1" 
                                   checked>
                            <label class="form-check-label" for="install_plugins">
                                <strong>Install Recommended Plugins</strong>
                                <br><small class="text-muted">Wordfence (Security), WP-Optimize, UpdraftPlus (Backup), Nginx Helper</small>
                            </label>
                        </div>

                        <div class="alert alert-info mb-0">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>What will be configured:</strong>
                            <ul class="mb-0 mt-2">
                                <li>✅ WordPress core (latest version)</li>
                                <li>✅ Optimized Nginx configuration with security headers</li>
                                <li>✅ Optimized PHP-FPM pool (256MB, OPcache enabled)</li>
                                <li>✅ FastCGI cache (60min TTL, 1GB max)</li>
                                <li>✅ Rate limiting on wp-login.php</li>
                                <li>✅ Block xmlrpc.php (DDoS protection)</li>
                                <li>✅ Secure file permissions</li>
                                <li>✅ Path isolation (open_basedir)</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('websites.show', $website) }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg" id="installButton">
                                <i class="bi bi-wordpress me-2"></i> Install WordPress
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('wordpressInstallForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const form = this;
    const installButton = document.getElementById('installButton');
    const statusCard = document.getElementById('installationStatusCard');
    const stepsContainer = document.getElementById('installationSteps');
    const resultContainer = document.getElementById('installationResult');
    const progressBar = document.getElementById('installProgress');
    
    // Disable form and show status card
    installButton.disabled = true;
    installButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Installing...';
    form.querySelectorAll('input, button').forEach(el => el.disabled = true);
    statusCard.style.display = 'block';
    resultContainer.style.display = 'none';
    
    // Scroll to status card
    statusCard.scrollIntoView({ behavior: 'smooth', block: 'start' });
    
    // Prepare form data
    const formData = new FormData(form);
    const data = Object.fromEntries(formData.entries());
    
    try {
        const response = await fetch('{{ route("websites.wordpress.install", $website) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            // Show success
            progressBar.style.width = '100%';
            progressBar.textContent = '100%';
            progressBar.classList.remove('progress-bar-animated');
            progressBar.classList.add('bg-success');
            
            // Display installation steps
            if (result.data.steps) {
                stepsContainer.innerHTML = result.data.steps.map(step => `
                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                        <span>${step.step}</span>
                    </div>
                `).join('');
            }
            
            // Show success message
            resultContainer.innerHTML = `
                <div class="alert alert-success">
                    <h5 class="alert-heading">
                        <i class="bi bi-check-circle me-2"></i> WordPress Installed Successfully!
                    </h5>
                    <hr>
                    <p class="mb-2"><strong>Admin URL:</strong> <a href="${result.data.admin_url}" target="_blank">${result.data.admin_url}</a></p>
                    <p class="mb-2"><strong>Admin Username:</strong> ${result.data.admin_user}</p>
                    <p class="mb-0"><strong>Admin Password:</strong> [As entered in the form]</p>
                    <hr>
                    <div class="mt-3">
                        <a href="{{ route('websites.show', $website) }}" class="btn btn-primary">
                            <i class="bi bi-arrow-left me-1"></i> Back to Website
                        </a>
                        <a href="${result.data.admin_url}" target="_blank" class="btn btn-success">
                            <i class="bi bi-box-arrow-up-right me-1"></i> Open WordPress Admin
                        </a>
                    </div>
                </div>
            `;
            resultContainer.style.display = 'block';
            
        } else {
            // Show error
            progressBar.classList.remove('progress-bar-animated');
            progressBar.classList.add('bg-danger');
            
            resultContainer.innerHTML = `
                <div class="alert alert-danger">
                    <h5 class="alert-heading">
                        <i class="bi bi-x-circle me-2"></i> Installation Failed
                    </h5>
                    <p class="mb-0">${result.message}</p>
                    ${result.errors ? '<hr><ul class="mb-0">' + Object.values(result.errors).map(err => `<li>${err}</li>`).join('') + '</ul>' : ''}
                </div>
            `;
            resultContainer.style.display = 'block';
            
            // Re-enable form
            form.querySelectorAll('input, button').forEach(el => el.disabled = false);
            installButton.disabled = false;
            installButton.innerHTML = '<i class="bi bi-wordpress me-2"></i> Retry Installation';
        }
        
    } catch (error) {
        console.error('Installation error:', error);
        
        progressBar.classList.remove('progress-bar-animated');
        progressBar.classList.add('bg-danger');
        
        resultContainer.innerHTML = `
            <div class="alert alert-danger">
                <h5 class="alert-heading">
                    <i class="bi bi-x-circle me-2"></i> Installation Error
                </h5>
                <p class="mb-0">An unexpected error occurred. Please check the logs or try again.</p>
            </div>
        `;
        resultContainer.style.display = 'block';
        
        // Re-enable form
        form.querySelectorAll('input, button').forEach(el => el.disabled = false);
        installButton.disabled = false;
        installButton.innerHTML = '<i class="bi bi-wordpress me-2"></i> Retry Installation';
    }
});

// Update progress bar during installation
function updateProgress(steps) {
    const totalSteps = 10;
    const completedSteps = steps.filter(s => s.status === 'completed').length;
    const progress = Math.round((completedSteps / totalSteps) * 100);
    
    const progressBar = document.getElementById('installProgress');
    progressBar.style.width = progress + '%';
    progressBar.textContent = progress + '%';
}
</script>
@endpush
@endsection

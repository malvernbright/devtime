<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'DevTime - Project Management')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- TinyMCE WYSIWYG Editor -->
    <script src="https://cdn.tiny.cloud/1/7djntdu3j15dfkxrgs17jd2t47v41rrcdqkd1hpbhr6wj327/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <style>
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            border-radius: 8px;
            margin: 2px 0;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
        }
        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border-radius: 10px;
        }
        .badge {
            border-radius: 6px;
        }
        .progress {
            height: 8px;
        }
        .btn {
            border-radius: 6px;
        }
        .priority-urgent { background-color: #dc3545; }
        .priority-high { background-color: #fd7e14; }
        .priority-medium { background-color: #ffc107; }
        .priority-low { background-color: #28a745; }
        
        .status-planning { background-color: #6c757d; }
        .status-in_progress { background-color: #007bff; }
        .status-on_hold { background-color: #ffc107; }
        .status-completed { background-color: #28a745; }
        .status-cancelled { background-color: #dc3545; }
        
        .status-todo { background-color: #6c757d; }
        .status-review { background-color: #17a2b8; }
        
        /* TinyMCE Editor Fixes */
        .tox-tinymce {
            border-radius: 6px !important;
        }
        
        .wysiwyg-content {
            line-height: 1.6;
        }
        
        .wysiwyg-content ul, .wysiwyg-content ol {
            padding-left: 2rem;
            margin: 1rem 0;
        }
        
        .wysiwyg-content li {
            margin: 0.5rem 0;
        }
        
        .wysiwyg-content p {
            margin: 1rem 0;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse p-3">
                <div class="position-sticky pt-3">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="text-white mb-0">
                            <i class="fas fa-rocket me-2"></i>DevTime
                        </h4>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle me-1"></i>{{ auth()->user()->name }}
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    <i class="fas fa-user me-2"></i>Profile
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('projects.*') ? 'active' : '' }}" href="{{ route('projects.index') }}">
                                <i class="fas fa-project-diagram me-2"></i>Projects
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('tasks.*') ? 'active' : '' }}" href="{{ route('tasks.index') }}">
                                <i class="fas fa-tasks me-2"></i>Tasks
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('activities.*') ? 'active' : '' }}" href="{{ route('activities.index') }}">
                                <i class="fas fa-clock me-2"></i>Activities
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('tomorrow-plans.*') ? 'active' : '' }}" href="{{ route('tomorrow-plans.index') }}">
                                <i class="fas fa-calendar-day me-2"></i>Tomorrow Plans
                            </a>
                        </li>
                        
                        <!-- Reports Dropdown -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle {{ request()->routeIs('reports.*') ? 'active' : '' }}" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-file-export me-2"></i>Reports
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('reports.daily') }}">
                                    <i class="fas fa-calendar-day me-2"></i>Daily Operations Report
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('reports.planned') }}">
                                    <i class="fas fa-calendar-alt me-2"></i>Planned Tasks Report
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('reports.status') }}">
                                    <i class="fas fa-project-diagram me-2"></i>Project Status Report
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#" onclick="showReportModal()">
                                    <i class="fas fa-cog me-2"></i>Custom Report
                                </a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Report Export Modal -->
            <div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="reportModalLabel">Export Report</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="reportForm">
                                <div class="mb-3">
                                    <label for="reportType" class="form-label">Report Type</label>
                                    <select class="form-select" id="reportType" required>
                                        <option value="">Select Report Type</option>
                                        <option value="daily">Daily Operations Report</option>
                                        <option value="planned">Planned Tasks Report</option>
                                        <option value="status">Project Status Report</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="reportDate" class="form-label">Date</label>
                                    <input type="date" class="form-control" id="reportDate" value="{{ date('Y-m-d') }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="consultant" class="form-label">Consultant Name</label>
                                    <input type="text" class="form-control" id="consultant" value="{{ auth()->user()->name }}" placeholder="Enter consultant name">
                                </div>
                                <div class="mb-3" id="locationField" style="display: none;">
                                    <label for="location" class="form-label">Location</label>
                                    <input type="text" class="form-control" id="location" value="ZAMBIA OPERATIONS" placeholder="Enter location">
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" onclick="exportReport()">
                                <i class="fas fa-download me-2"></i>Export Word Document
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">@yield('page-title', 'Dashboard')</h1>
                    @yield('page-actions')
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function showReportModal() {
            const modal = new bootstrap.Modal(document.getElementById('reportModal'));
            modal.show();
        }
        
        function exportReport() {
            const reportType = document.getElementById('reportType').value;
            const reportDate = document.getElementById('reportDate').value;
            const consultant = document.getElementById('consultant').value;
            const location = document.getElementById('location').value;
            
            if (!reportType || !reportDate) {
                alert('Please select a report type and date.');
                return;
            }
            
            let url = '';
            let params = new URLSearchParams({
                date: reportDate,
                consultant: consultant
            });
            
            switch(reportType) {
                case 'daily':
                    url = '{{ route("reports.daily") }}';
                    break;
                case 'planned':
                    url = '{{ route("reports.planned") }}';
                    break;
                case 'status':
                    url = '{{ route("reports.status") }}';
                    params.append('location', location);
                    break;
            }
            
            // Open the export URL
            window.open(url + '?' + params.toString(), '_blank');
            
            // Close the modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('reportModal'));
            modal.hide();
        }
        
        // Show/hide location field based on report type
        document.addEventListener('DOMContentLoaded', function() {
            const reportType = document.getElementById('reportType');
            const locationField = document.getElementById('locationField');
            
            if (reportType && locationField) {
                reportType.addEventListener('change', function() {
                    if (this.value === 'status') {
                        locationField.style.display = 'block';
                    } else {
                        locationField.style.display = 'none';
                    }
                });
            }
        });
    </script>
    
    <!-- TinyMCE Initialization -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof tinymce !== 'undefined') {
                tinymce.init({
                    selector: '.data-tinymce="true"',
                    height: 300,
                    menubar: false,
                    plugins: [
                        'advlist', 'autolink', 'lists', 'link', 'image', 'charmap',
                        'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                        'insertdatetime', 'media', 'table', 'help', 'wordcount'
                    ],
                    toolbar: 'undo redo | blocks | ' +
                        'bold italic backcolor | alignleft aligncenter ' +
                        'alignright alignjustify | bullist numlist outdent indent | ' +
                        'removeformat | help',
                    content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-size: 14px; }',
                    branding: false,
                    promotion: false,
                    convert_urls: false,
                    remove_script_host: false,
                    document_base_url: window.location.origin,
                    entity_encoding: 'raw',
                    verify_html: false,
                    forced_root_block: 'div',
                    force_br_newlines: false,
                    force_p_newlines: false,
                    cleanup: false,
                    setup: function (editor) {
                        editor.on('change', function () {
                            editor.save();
                        });
                        
                        // Ensure content is properly initialized
                        editor.on('init', function () {
                            // Get the textarea content and set it properly
                            var content = editor.getElement().value;
                            if (content) {
                                editor.setContent(content);
                            }
                        });
                    }
                });
            }
        });
    </script>
    
    @yield('scripts')
</body>
</html>

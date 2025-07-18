<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'DevTime')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- TinyMCE -->
    <script src="https://cdn.tiny.cloud/1/7djntdu3j15dfkxrgs17jd2t47v41rrcdqkd1hpbhr6wj327/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #4f46e5;
            --primary-dark: #3730a3;
            --sidebar-bg: #ffffff;
            --sidebar-border: #e5e7eb;
            --navbar-bg: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            --text-primary: #1f2937;
            --text-secondary: #6b7280;
            --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --card-shadow-hover: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            min-height: 100vh;
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: #ffffff !important;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .navbar-brand i {
            color: #fbbf24;
        }
        
        .status-todo {
            background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        .status-in_progress {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        .status-completed {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        .status-on_hold {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        .status-cancelled {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        .priority-low {
            color: #10b981;
            font-weight: 600;
        }
        
        .priority-medium {
            color: #f59e0b;
            font-weight: 600;
        }
        
        .priority-high {
            color: #ef4444;
            font-weight: 600;
        }
        
        .priority-urgent {
            color: #7c3aed;
            font-weight: 600;
        }
        
        .card {
            border: none;
            box-shadow: var(--card-shadow);
            border-radius: 0.75rem;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }
        
        .card:hover {
            box-shadow: var(--card-shadow-hover);
            transform: translateY(-2px);
        }
        
        .card-header {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            border-bottom: 1px solid var(--sidebar-border);
            border-radius: 0.75rem 0.75rem 0 0 !important;
            padding: 1.25rem;
            font-weight: 600;
            color: var(--text-primary);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            border: none;
            border-radius: 0.5rem;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.3);
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 8px -1px rgba(79, 70, 229, 0.4);
        }
        
        .btn-outline-success {
            border: 2px solid #10b981;
            color: #10b981;
            border-radius: 0.5rem;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-outline-success:hover {
            background: #10b981;
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.3);
        }
        
        .btn-group .btn {
            margin-right: 0.5rem;
        }
        
        .btn-group .btn:last-child {
            margin-right: 0;
        }
        
        .page-header {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border-radius: 0.75rem;
            padding: 1.5rem 2rem;
            margin-bottom: 2rem;
            box-shadow: var(--card-shadow);
        }
        
        .page-header h1 {
            color: var(--text-primary);
            font-weight: 700;
            margin: 0;
        }
        
        .main-content {
            padding: 2rem 0;
        }
        
        .sidebar {
            min-height: calc(100vh - 56px);
            background: var(--sidebar-bg);
            padding: 1.5rem 0;
            position: fixed;
            top: 56px;
            left: 0;
            width: 260px;
            border-right: 1px solid var(--sidebar-border);
            z-index: 1000;
            box-shadow: 4px 0 6px -1px rgba(0, 0, 0, 0.1);
        }
        
        .sidebar .nav-link {
            color: var(--text-secondary);
            padding: 0.875rem 1.5rem;
            border-radius: 0.5rem;
            margin: 0.25rem 1rem;
            display: flex;
            align-items: center;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .sidebar .nav-link:hover {
            background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
            color: var(--text-primary);
            transform: translateX(5px);
        }
        
        .sidebar .nav-link.active {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.3);
        }
        
        .sidebar .nav-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: #fbbf24;
            border-radius: 0 4px 4px 0;
        }
        
        .sidebar .nav-link i {
            margin-right: 0.75rem;
            width: 18px;
            text-align: center;
            font-size: 1.1rem;
        }
        
        .main-content {
            margin-left: 260px;
            margin-top: 56px;
            padding: 2rem 1.5rem;
            max-width: calc(100vw - 260px);
        }
        
        .content-wrapper {
            margin-left: 260px;
            margin-top: 56px;
            min-height: calc(100vh - 56px);
            padding-top: 1rem;
        }
        
        .container-fluid {
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .page-header {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border-radius: 0.75rem;
            padding: 1.5rem 2rem;
            margin-bottom: 2rem;
            box-shadow: var(--card-shadow);
        }
        
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                box-shadow: none;
            }
            
            .sidebar.show {
                transform: translateX(0);
                box-shadow: 8px 0 16px -4px rgba(0, 0, 0, 0.15);
            }
            
            .main-content,
            .content-wrapper {
                margin-left: 0;
                margin-top: 56px;
            }
        }
        
        .navbar {
            background: var(--navbar-bg) !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
        }
        
        .navbar .dropdown-menu {
            border: none;
            box-shadow: var(--card-shadow);
            border-radius: 0.5rem;
        }
        
        .navbar .dropdown-item {
            padding: 0.75rem 1.25rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .navbar .dropdown-item:hover {
            background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
            color: var(--text-primary);
        }
        
        .alert {
            border: none;
            border-radius: 0.75rem;
            box-shadow: var(--card-shadow);
            font-weight: 500;
        }
        
        .alert-success {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            color: #065f46;
        }
        
        .alert-danger {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            color: #991b1b;
        }
        
        .form-control {
            border-radius: 0.5rem;
            border: 2px solid #e5e7eb;
            padding: 0.75rem 1rem;
            font-weight: 500;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }
        
        .form-select {
            border-radius: 0.5rem;
            border: 2px solid #e5e7eb;
            padding: 0.75rem 1rem;
            font-weight: 500;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }
        
        .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }
        
        .form-label {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }
        
        .card-body {
            padding: 2rem;
        }
        
        .mb-3 {
            margin-bottom: 1.5rem !important;
        }
        
        .btn-secondary {
            background: #6b7280;
            border: none;
            border-radius: 0.5rem;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-secondary:hover {
            background: #4b5563;
            transform: translateY(-1px);
        }
        
        .table {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border-radius: 0.75rem;
            overflow: hidden;
        }
        
        .table thead th {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            border: none;
            color: var(--text-primary);
            font-weight: 600;
            padding: 1rem;
        }
        
        .table tbody td {
            border: none;
            padding: 1rem;
            vertical-align: middle;
        }
        
        .table tbody tr:hover {
            background: rgba(79, 70, 229, 0.05);
        }
    </style>
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container-fluid">
            <button class="btn btn-outline-light me-3 d-md-none" type="button" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
            
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <i class="fas fa-clock me-2"></i>DevTime
            </a>
            
            <div class="navbar-nav ms-auto">
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                            <i class="fas fa-user text-primary"></i>
                        </div>
                        {{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}">
                            <i class="fas fa-user-edit me-2"></i>Profile
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
        </div>
    </nav>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <nav class="nav flex-column">
            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <i class="fas fa-home"></i>Dashboard
            </a>
            <a class="nav-link {{ request()->routeIs('projects.*') ? 'active' : '' }}" href="{{ route('projects.index') }}">
                <i class="fas fa-project-diagram"></i>Projects
            </a>
            <a class="nav-link {{ request()->routeIs('tasks.*') ? 'active' : '' }}" href="{{ route('tasks.index') }}">
                <i class="fas fa-tasks"></i>Tasks
            </a>
            <a class="nav-link {{ request()->routeIs('activities.*') ? 'active' : '' }}" href="{{ route('activities.index') }}">
                <i class="fas fa-clock"></i>Activities
            </a>
            <a class="nav-link {{ request()->routeIs('tomorrow-plans.*') ? 'active' : '' }}" href="{{ route('tomorrow-plans.index') }}">
                <i class="fas fa-calendar-day"></i>Tomorrow Plans
            </a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="content-wrapper">
        <div class="container-fluid">
            <div class="main-content">
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
                
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @if(isset($pageTitle) || View::hasSection('page-title'))
                    <div class="page-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h1 class="h3 mb-0">@yield('page-title', $pageTitle ?? 'Page')</h1>
                            <div class="page-actions">
                                @yield('page-actions')
                            </div>
                        </div>
                    </div>
                @endif
                
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script>
        // Mobile sidebar toggle
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('show');
        });
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.getElementById('sidebarToggle');
            
            if (window.innerWidth <= 768 && !sidebar.contains(event.target) && !toggle.contains(event.target)) {
                sidebar.classList.remove('show');
            }
        });
        
        // Initialize TinyMCE
        document.addEventListener('DOMContentLoaded', function() {
            const textareas = document.querySelectorAll('textarea[data-tinymce="true"], textarea.data-tinymce="true"');
            textareas.forEach(function(textarea) {
                tinymce.init({
                    target: textarea,
                    height: 300,
                    menubar: false,
                    plugins: [
                        'advlist autolink lists link image charmap preview anchor',
                        'searchreplace visualblocks code fullscreen',
                        'insertdatetime table paste code help wordcount',
                        'emoticons template codesample export print'
                    ],
                    toolbar: 'undo redo | blocks fontsize | bold italic underline strikethrough | forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image table | codesample emoticons | export print | removeformat | preview code fullscreen | help',
                    content_css: 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css',
                    skin: 'oxide',
                    export_cors_hosts: ['*'],
                    export_image_proxy: 'https://api.tinymce.com/v1/imageproxy',
                    content_style: `
                        body { 
                            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; 
                            font-size: 14px; 
                            line-height: 1.6; 
                            color: #1f2937; 
                            padding: 15px; 
                        }
                        h1, h2, h3, h4, h5, h6 { color: #1f2937; font-weight: 600; margin-top: 1rem; margin-bottom: 0.5rem; }
                        p { margin-bottom: 1rem; }
                        ul, ol { margin-left: 1.5rem; margin-bottom: 1rem; }
                        blockquote { border-left: 4px solid #4f46e5; padding-left: 1rem; margin: 1rem 0; font-style: italic; }
                        code { background-color: #f3f4f6; padding: 2px 4px; border-radius: 3px; font-family: 'Courier New', monospace; }
                        pre { background-color: #f3f4f6; padding: 1rem; border-radius: 0.5rem; overflow-x: auto; }
                        table { border-collapse: collapse; width: 100%; margin: 1rem 0; }
                        table, th, td { border: 1px solid #e5e7eb; }
                        th, td { padding: 0.75rem; text-align: left; }
                        th { background-color: #f8fafc; font-weight: 600; }
                    `,
                    fontsize_formats: '8pt 10pt 12pt 14pt 16pt 18pt 24pt 36pt',
                    block_formats: 'Paragraph=p; Heading 1=h1; Heading 2=h2; Heading 3=h3; Heading 4=h4; Heading 5=h5; Heading 6=h6; Preformatted=pre; Blockquote=blockquote',
                    image_advtab: true,
                    image_caption: true,
                    image_title: true,
                    table_responsive_width: true,
                    table_default_attributes: {
                        'class': 'table table-bordered'
                    },
                    table_default_styles: {
                        'border-collapse': 'collapse'
                    },
                    codesample_languages: [
                        {text: 'HTML/XML', value: 'markup'},
                        {text: 'JavaScript', value: 'javascript'},
                        {text: 'CSS', value: 'css'},
                        {text: 'PHP', value: 'php'},
                        {text: 'Python', value: 'python'},
                        {text: 'Java', value: 'java'},
                        {text: 'C#', value: 'csharp'},
                        {text: 'C++', value: 'cpp'},
                        {text: 'SQL', value: 'sql'},
                        {text: 'JSON', value: 'json'}
                    ],
                    export_format: 'docx',
                    export_cors_hosts: ['*'],
                    setup: function(editor) {
                        editor.on('init', function() {
                            editor.getContainer().style.transition = 'border-color 0.3s ease';
                        });
                        editor.on('focus', function() {
                            editor.getContainer().style.borderColor = '#4f46e5';
                        });
                        editor.on('blur', function() {
                            editor.getContainer().style.borderColor = '#e5e7eb';
                        });
                    }
                });
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>

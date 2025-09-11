<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ $title ?? 'Page Title' }}</title>
        <link href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('assets/fontawesome/css/fontawesome.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/fontawesome/css/solid.min.css') }}">
        <style>
            html,
            body {
                height: 100%;
            }
        
            body {
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                background: #f8f9fa;
            }
        </style>
        </head>
        
        <body>
            <div class="container d-flex align-items-center justify-content-center" style="min-height: 100vh;">
                <div class="card shadow" style="min-width: 350px; max-width: 100%; width: 400px;">
                    <div class="card-body">
                        {{ $slot }}
                    </div>
                </div>
            </div>
            <!-- Bootstrap 5 JS Bundle -->
            <script src="{{ asset('assets/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        </body>
        
        </html>

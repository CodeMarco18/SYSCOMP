<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso al Sistema - SysComp</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
            width: 100%;
            max-width: 450px;
        }

        .login-header {
            background: #0f172a;
            color: #fff;
            padding: 2.5rem 2rem 2rem;
            text-align: center;
        }

        .login-header i {
            font-size: 3rem;
            color: #3b82f6;
            margin-bottom: 1rem;
        }

        .form-control {
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
        }

        .form-control:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.25);
        }

        .btn-login {
            background: #3b82f6;
            border: none;
            border-radius: 10px;
            padding: 0.75rem;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            background: #2563eb;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(59, 130, 246, 0.4);
        }
    </style>
</head>
<body>
    @yield('content')
</body>
</html>

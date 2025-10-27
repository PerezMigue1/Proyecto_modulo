<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - {{ config('app.name', 'Laravel') }}</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <style>
        .dashboard-container {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 20px;
        }
        .dashboard-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            max-width: 1200px;
            margin: 0 auto;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }
        .user-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e5e7eb;
        }
        .user-info h2 {
            font-size: 28px;
            color: #1a202c;
        }
        .user-info .user-details {
            color: #718096;
            font-size: 14px;
        }
        .btn-logout {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-weight: 600;
        }
        .btn-logout:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }
        .content-box {
            background: #f9fafb;
            border-radius: 12px;
            padding: 30px;
            margin-top: 20px;
        }
        .content-box h3 {
            color: #1a202c;
            margin-bottom: 15px;
        }
        .content-box p {
            color: #718096;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="dashboard-card">
            <div class="user-info">
                <div>
                    <h2>Bienvenido, {{ Auth::user()->name }}</h2>
                    <div class="user-details">
                        {{ Auth::user()->email }}
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn-logout">Cerrar sesión</button>
                </form>
            </div>

            <div class="content-box">
                <h3>🎉 Dashboard</h3>
                <p>Has iniciado sesión exitosamente. Esta es tu página de Dashboard.</p>
                <p>Aquí puedes agregar el contenido de tu aplicación según tus necesidades.</p>
            </div>
        </div>
    </div>
</body>
</html>


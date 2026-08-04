<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SysComp - Tienda de Cómputo')</title>
    
    <!-- Google Fonts & FontAwesome -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --sidebar-width: 260px;
            --primary-bg: #0f172a;
            --primary-accent: #3b82f6;
            --primary-accent-hover: #2563eb;
            --body-bg: #f8fafc;
            --card-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.05);
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--body-bg);
            color: #334155;
            min-height: 100vh;
        }

        /* Sidebar Styling */
        #sidebar {
            width: var(--sidebar-width);
            background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);
            color: #f8fafc;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            transition: all 0.3s ease;
            box-shadow: 4px 0 15px rgba(0, 0, 0, 0.08);
            overflow-y: auto;
        }

        .sidebar-brand {
            padding: 1.5rem 1.25rem;
            font-size: 1.25rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .sidebar-brand i {
            color: var(--primary-accent);
            font-size: 1.6rem;
        }

        .sidebar-heading {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: #64748b;
            padding: 1.25rem 1.25rem 0.5rem;
            font-weight: 600;
        }

        .nav-link-custom {
            color: #94a3b8;
            padding: 0.75rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 0.95rem;
            font-weight: 500;
            border-radius: 8px;
            margin: 2px 10px;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .nav-link-custom:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.06);
        }

        .nav-link-custom.active {
            color: #fff;
            background: var(--primary-accent);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.35);
        }

        /* Main Content Wrapper */
        #main-wrapper {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Navbar Styling */
        .top-navbar {
            background-color: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            padding: 0.8rem 1.5rem;
            position: sticky;
            top: 0;
            z-index: 90;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        }

        .user-pill {
            background: #f1f5f9;
            padding: 6px 14px;
            border-radius: 50px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .badge-role {
            font-size: 0.75rem;
            padding: 4px 8px;
            border-radius: 6px;
            font-weight: 600;
        }

        /* Card Customizations */
        .card-custom {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            background: #ffffff;
            box-shadow: var(--card-shadow);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card-custom:hover {
            box-shadow: 0 8px 30px -4px rgba(15, 23, 42, 0.08);
        }

        .metric-card {
            border-radius: 14px;
            padding: 1.25rem;
            color: #fff;
            position: relative;
            overflow: hidden;
        }

        .metric-card i.bg-icon {
            position: absolute;
            right: -10px;
            bottom: -10px;
            font-size: 5rem;
            opacity: 0.15;
        }

        /* Responsive Table */
        .table-custom th {
            background-color: #f8fafc;
            color: #475569;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.78rem;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #e2e8f0;
        }

        .table-custom td {
            vertical-align: middle;
            font-size: 0.92rem;
        }

        /* Footer */
        footer {
            margin-top: auto;
            background: #ffffff;
            border-top: 1px solid #e2e8f0;
            padding: 1rem 1.5rem;
            font-size: 0.85rem;
            color: #64748b;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div id="sidebar">
        <div class="sidebar-brand">
            <i class="fa-solid fa-laptop-code"></i>
            <span>SYSCOMP TECH</span>
        </div>

        <div class="py-2">
            <a href="{{ route('dashboard') }}" class="nav-link-custom {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-chart-pie"></i>
                <span>Dashboard</span>
            </a>

            @if(Auth::user()->esAlmacenero())
            <div class="sidebar-heading">Inventario</div>
            <a href="{{ route('productos.index') }}" class="nav-link-custom {{ request()->routeIs('productos.*') ? 'active' : '' }}">
                <i class="fa-solid fa-box-archive"></i>
                <span>Productos</span>
            </a>
            <a href="{{ route('categorias.index') }}" class="nav-link-custom {{ request()->routeIs('categorias.*') ? 'active' : '' }}">
                <i class="fa-solid fa-tags"></i>
                <span>Categorías</span>
            </a>
            <a href="{{ route('movimientos.index') }}" class="nav-link-custom {{ request()->routeIs('movimientos.*') ? 'active' : '' }}">
                <i class="fa-solid fa-right-left"></i>
                <span>Movimientos</span>
            </a>

            <div class="sidebar-heading">Compras</div>
            <a href="{{ route('proveedores.index') }}" class="nav-link-custom {{ request()->routeIs('proveedores.*') ? 'active' : '' }}">
                <i class="fa-solid fa-truck-field"></i>
                <span>Proveedores</span>
            </a>
            <a href="{{ route('compras.index') }}" class="nav-link-custom {{ request()->routeIs('compras.*') ? 'active' : '' }}">
                <i class="fa-solid fa-cart-flatbed"></i>
                <span>Compras</span>
            </a>
            @endif

            @if(Auth::user()->esVendedor())
            <div class="sidebar-heading">Ventas</div>
            <a href="{{ route('clientes.index') }}" class="nav-link-custom {{ request()->routeIs('clientes.*') ? 'active' : '' }}">
                <i class="fa-solid fa-users"></i>
                <span>Clientes</span>
            </a>
            <a href="{{ route('ventas.index') }}" class="nav-link-custom {{ request()->routeIs('ventas.*') ? 'active' : '' }}">
                <i class="fa-solid fa-cash-register"></i>
                <span>Ventas</span>
            </a>
            <a href="{{ route('garantias.index') }}" class="nav-link-custom {{ request()->routeIs('garantias.*') ? 'active' : '' }}">
                <i class="fa-solid fa-shield-halved"></i>
                <span>Garantías</span>
            </a>
            @endif

            @if(Auth::user()->esAdmin())
            <div class="sidebar-heading">Administración</div>
            <a href="{{ route('empleados.index') }}" class="nav-link-custom {{ request()->routeIs('empleados.*') ? 'active' : '' }}">
                <i class="fa-solid fa-id-card"></i>
                <span>Empleados</span>
            </a>

            <div class="sidebar-heading">Reportes</div>
            <a href="{{ route('reportes.index') }}" class="nav-link-custom {{ request()->routeIs('reportes.*') ? 'active' : '' }}">
                <i class="fa-solid fa-file-invoice-dollar"></i>
                <span>Reportes SQL</span>
            </a>
            @endif
        </div>
    </div>

    <!-- Main Wrapper -->
    <div id="main-wrapper">
        <!-- Top Navbar -->
        <nav class="top-navbar d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
                <h5 class="m-0 font-weight-bold text-dark">
                    @yield('page_title', 'Gestión de Inventario y Ventas')
                </h5>
            </div>

            <div class="d-flex align-items-center gap-3">
                <div class="user-pill">
                    <i class="fa-solid fa-circle-user fs-5 text-primary"></i>
                    <div>
                        <span class="fw-semibold d-block text-dark lh-1" style="font-size: 0.9rem;">{{ Auth::user()->name }}</span>
                        <span class="badge bg-primary badge-role mt-1">{{ Auth::user()->role }}</span>
                    </div>
                </div>

                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3" title="Cerrar Sesión">
                        <i class="fa-solid fa-right-from-bracket me-1"></i> Salir
                    </button>
                </form>
            </div>
        </nav>

        <!-- Main Content Area -->
        <div class="container-fluid p-4">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                    <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                    <i class="fa-solid fa-circle-info me-2"></i> {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </div>

        <!-- Footer -->
        <footer class="d-flex justify-content-between align-items-center">
            <div>
                &copy; {{ date('Y') }} <strong>SysComp Tech Store</strong> - Sistema de Gestión de Inventarios y Ventas.
            </div>
            <div>
                <span class="badge bg-light text-dark border">Laravel v12</span>
                <span class="badge bg-light text-dark border">MySQL v8</span>
            </div>
        </footer>
    </div>

    <!-- Bootstrap 5 JS Bundle CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>

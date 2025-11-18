<?php
session_start();

// Detectar tipo de sesión
$es_cliente = isset($_SESSION['usuario_cliente']);
$es_empleado = isset($_SESSION['usuario_empleado']);
$es_admin = isset($_SESSION['usuario_admin']);

if($es_cliente) {
    $perfil_url = "cliente/perfil_cliente.php";
    $usuario = $_SESSION['usuario_cliente'];
    $rol = "Cliente";
} elseif($es_empleado) {
    $perfil_url = "empleado/perfil_empleado.php";
    $usuario = $_SESSION['usuario_empleado'];
    $rol = "Empleado";
    if(isset($_SESSION['tipo_empleado'])) {
        $rol .= " - " . $_SESSION['tipo_empleado'];
    }
} elseif($es_admin) {
    $perfil_url = "administrador/perfil_admin.php";
    $usuario = $_SESSION['usuario_admin'];
    $rol = "Administrador";
} else {
    $perfil_url = null;
    $usuario = null;
    $rol = null;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Dulce Sueños - Tu Refugio de Descanso</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Tailwind Config -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            DEFAULT: '#1E88E5',
                            light: '#42A5F5',
                            dark: '#1565C0',
                        },
                        gray: {
                            light: '#F1F3F4',
                            DEFAULT: '#9E9E9E',
                            dark: '#424242',
                            darker: '#212121',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.8s ease-in',
                        'slide-up': 'slideUp 0.8s ease-out',
                        'bounce-subtle': 'bounceSubtle 2s infinite',
                        'float': 'float 6s ease-in-out infinite',
                        'gradient': 'gradient 15s ease infinite',
                        'pulse-glow': 'pulseGlow 2s ease-in-out infinite',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        slideUp: {
                            '0%': { transform: 'translateY(30px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' },
                        },
                        bounceSubtle: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-10px)' },
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-20px)' },
                        },
                        gradient: {
                            '0%, 100%': { backgroundPosition: '0% 50%' },
                            '50%': { backgroundPosition: '100% 50%' },
                        },
                        pulseGlow: {
                            '0%, 100%': { boxShadow: '0 0 20px rgba(30, 136, 229, 0.3)' },
                            '50%': { boxShadow: '0 0 40px rgba(30, 136, 229, 0.6)' },
                        },
                    },
                }
            }
        }
    </script>
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
        }
        
        /* Efecto de partículas animadas */
        .particles {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 0;
        }
        
        .particle {
            position: absolute;
            background: rgba(30, 136, 229, 0.1);
            border-radius: 50%;
            animation: float 20s infinite;
        }
        
        /* Grid animado de fondo */
        .grid-pattern {
            background-image: 
                linear-gradient(rgba(30, 136, 229, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(30, 136, 229, 0.03) 1px, transparent 1px);
            background-size: 50px 50px;
            animation: gridMove 20s linear infinite;
        }
        
        @keyframes gridMove {
            0% { background-position: 0 0; }
            100% { background-position: 50px 50px; }
        }
        
        /* Gradiente animado */
        .gradient-animated {
            background: linear-gradient(-45deg, #1E88E5, #42A5F5, #1565C0, #1E88E5);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
        }
        
        /* Efecto glassmorphism */
        .glass {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        /* Efecto de brillo en hover */
        .shine-effect {
            position: relative;
            overflow: hidden;
        }
        
        .shine-effect::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s;
        }
        
        .shine-effect:hover::before {
            left: 100%;
        }
        
        /* Scrollbar personalizado */
        ::-webkit-scrollbar {
            width: 10px;
        }
        
        ::-webkit-scrollbar-track {
            background: #F1F3F4;
        }
        
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #1E88E5, #1565C0);
            border-radius: 5px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #1565C0, #1E88E5);
        }
    </style>
</head>
<body class="bg-gray-light">
    <!-- Navbar -->
    <nav id="navbar" class="fixed top-0 w-full bg-white/95 backdrop-blur-md shadow-lg z-50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-2">
                    <i class="ph ph-moon-stars text-primary text-2xl animate-bounce-subtle"></i>
                    <span class="text-primary font-bold text-xl">Dulce Sueños</span>
                </div>
                <div class="hidden md:flex items-center space-x-4">
                    <?php if($usuario): ?>
                        <!-- Usuario logueado -->
                        <a href="<?php echo $perfil_url; ?>" class="flex items-center space-x-3 bg-gradient-to-r from-primary/10 to-primary/5 hover:from-primary/20 hover:to-primary/10 px-4 py-2 rounded-lg transition-all duration-300 transform hover:scale-105 border-2 border-primary/30 group">
                            <i class="ph ph-user-circle text-primary text-xl group-hover:animate-bounce-subtle"></i>
                            <div class="flex flex-col">
                                <span class="text-gray-darker text-sm font-bold"><?php echo htmlspecialchars($usuario); ?></span>
                                <span class="text-primary text-xs font-semibold"><?php echo htmlspecialchars($rol); ?></span>
                            </div>
                        </a>
                        <form action="logout.php" method="POST" class="inline">
                            <button type="submit" class="flex items-center space-x-2 bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-xl group font-semibold">
                                <i class="ph ph-sign-out text-lg group-hover:animate-bounce-subtle"></i>
                                <span>Cerrar Sesión</span>
                            </button>
                        </form>
                    <?php else: ?>
                        <!-- Usuario no logueado -->
                        <a href="cliente/login_cliente.php" class="flex items-center space-x-2 text-gray-dark hover:text-primary transition-colors duration-300 group font-semibold">
                            <i class="ph ph-sign-in text-lg group-hover:animate-bounce-subtle"></i>
                            <span>Iniciar Sesión</span>
                        </a>
                        <a href="cliente/registrarse_cliente.php" class="flex items-center space-x-2 bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-xl group font-semibold">
                            <i class="ph ph-user-plus text-lg group-hover:animate-bounce-subtle"></i>
                            <span>Registrarse</span>
                        </a>
                        <a href="empleado/login_empleado.php" class="flex items-center space-x-2 text-gray-dark hover:text-primary transition-colors duration-300 group font-semibold">
                            <i class="ph ph-briefcase text-lg group-hover:animate-bounce-subtle"></i>
                            <span>Empleado</span>
                        </a>
                        <a href="administrador/login_admin.php" class="flex items-center space-x-2 text-gray-dark hover:text-primary transition-colors duration-300 group font-semibold">
                            <i class="ph ph-shield-check text-lg group-hover:animate-bounce-subtle"></i>
                            <span>Admin</span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section con efectos avanzados -->
    <section class="relative min-h-screen flex items-center justify-center overflow-hidden">
        <!-- Fondo con gradiente animado y grid -->
        <div class="absolute inset-0 gradient-animated opacity-20"></div>
        <div class="absolute inset-0 grid-pattern"></div>
        
        <!-- Partículas flotantes -->
        <div class="particles">
            <div class="particle" style="width: 100px; height: 100px; top: 20%; left: 10%; animation-delay: 0s;"></div>
            <div class="particle" style="width: 150px; height: 150px; top: 60%; left: 80%; animation-delay: 2s;"></div>
            <div class="particle" style="width: 80px; height: 80px; top: 80%; left: 30%; animation-delay: 4s;"></div>
            <div class="particle" style="width: 120px; height: 120px; top: 40%; left: 70%; animation-delay: 6s;"></div>
        </div>
        
        <!-- Vectores decorativos SVG -->
        <svg class="absolute top-0 left-0 w-full h-full opacity-5" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="grid" width="100" height="100" patternUnits="userSpaceOnUse">
                    <path d="M 100 0 L 0 0 0 100" fill="none" stroke="#1E88E5" stroke-width="1"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#grid)" />
        </svg>
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center z-10 animate-fade-in pt-16">
            <div class="mb-8">
                <div class="inline-block p-6 rounded-full bg-white/20 backdrop-blur-md border-2 border-primary/30 animate-pulse-glow">
                    <i class="ph ph-moon-stars text-primary text-8xl animate-float"></i>
                </div>
            </div>
            <h1 class="text-6xl md:text-8xl font-black text-gray-darker mb-6 animate-slide-up drop-shadow-lg">
                Hotel <span class="text-primary">Dulce Sueños</span>
            </h1>
            <p class="text-2xl md:text-4xl text-gray-dark font-semibold mb-12 animate-slide-up drop-shadow" style="animation-delay: 0.2s">
                Donde cada noche es un <span class="text-primary font-bold">sueño hecho realidad</span>
            </p>
            <div class="flex flex-col sm:flex-row gap-6 justify-center animate-slide-up" style="animation-delay: 0.4s">
                <a href="cliente/registrarse_cliente.php" class="group flex items-center justify-center space-x-3 bg-primary hover:bg-primary-dark text-white font-bold py-5 px-10 rounded-xl transition-all duration-300 transform hover:scale-110 shadow-2xl hover:shadow-primary/50 shine-effect">
                    <i class="ph ph-calendar-check text-3xl group-hover:animate-bounce-subtle"></i>
                    <span class="text-xl">Reservar Ahora</span>
                </a>
                <button class="group flex items-center justify-center space-x-3 bg-white hover:bg-gray-light text-gray-darker font-bold py-5 px-10 rounded-xl transition-all duration-300 transform hover:scale-110 shadow-2xl hover:shadow-xl border-3 border-primary">
                    <i class="ph ph-play-circle text-3xl group-hover:animate-bounce-subtle"></i>
                    <span class="text-xl">Ver Tour Virtual</span>
                </button>
            </div>
        </div>
        
        <!-- Scroll indicator -->
        <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2 animate-bounce z-10">
            <i class="ph ph-arrow-down text-primary text-3xl"></i>
        </div>
    </section>

    <!-- Features Section con mejor contraste -->
    <section class="py-24 bg-white relative overflow-hidden">
        <!-- Fondo decorativo -->
        <div class="absolute inset-0 opacity-5">
            <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="dots" x="0" y="0" width="40" height="40" patternUnits="userSpaceOnUse">
                        <circle cx="20" cy="20" r="2" fill="#1E88E5"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#dots)" />
            </svg>
        </div>
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-5xl font-black text-gray-darker text-center mb-16 flex items-center justify-center space-x-3">
                <i class="ph ph-sparkle text-primary text-5xl animate-pulse-glow"></i>
                <span>Servicios de <span class="text-primary">Lujo</span></span>
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-gradient-to-br from-gray-light to-white rounded-2xl p-8 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-3 group border-2 border-transparent hover:border-primary/30">
                    <div class="bg-gradient-to-br from-primary/20 to-primary/10 p-6 rounded-2xl w-fit mb-6 group-hover:scale-110 transition-transform duration-300">
                        <i class="ph ph-wifi-high text-primary text-5xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-darker mb-3">WiFi de Alta Velocidad</h3>
                    <p class="text-gray-dark leading-relaxed">Conexión de fibra óptica gratuita en todas las áreas del hotel para mantenerte siempre conectado.</p>
                </div>
                <div class="bg-gradient-to-br from-gray-light to-white rounded-2xl p-8 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-3 group border-2 border-transparent hover:border-primary/30">
                    <div class="bg-gradient-to-br from-primary/20 to-primary/10 p-6 rounded-2xl w-fit mb-6 group-hover:scale-110 transition-transform duration-300">
                        <i class="ph ph-spa text-primary text-5xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-darker mb-3">Spa & Wellness</h3>
                    <p class="text-gray-dark leading-relaxed">Disfruta de nuestro spa de clase mundial con tratamientos exclusivos y piscina climatizada.</p>
                </div>
                <div class="bg-gradient-to-br from-gray-light to-white rounded-2xl p-8 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-3 group border-2 border-transparent hover:border-primary/30">
                    <div class="bg-gradient-to-br from-primary/20 to-primary/10 p-6 rounded-2xl w-fit mb-6 group-hover:scale-110 transition-transform duration-300">
                        <i class="ph ph-fork-knife text-primary text-5xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-darker mb-3">Restaurante Gourmet</h3>
                    <p class="text-gray-dark leading-relaxed">Deléitate con platillos de chefs internacionales disponibles 24/7 en nuestros restaurantes.</p>
                </div>
                <div class="bg-gradient-to-br from-gray-light to-white rounded-2xl p-8 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-3 group border-2 border-transparent hover:border-primary/30">
                    <div class="bg-gradient-to-br from-primary/20 to-primary/10 p-6 rounded-2xl w-fit mb-6 group-hover:scale-110 transition-transform duration-300">
                        <i class="ph ph-bell text-primary text-5xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-darker mb-3">Servicio de Conserjería</h3>
                    <p class="text-gray-dark leading-relaxed">Nuestro equipo está disponible 24 horas para hacer tu estadía inolvidable.</p>
                </div>
                <div class="bg-gradient-to-br from-gray-light to-white rounded-2xl p-8 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-3 group border-2 border-transparent hover:border-primary/30">
                    <div class="bg-gradient-to-br from-primary/20 to-primary/10 p-6 rounded-2xl w-fit mb-6 group-hover:scale-110 transition-transform duration-300">
                        <i class="ph ph-barbell text-primary text-5xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-darker mb-3">Gimnasio Equipado</h3>
                    <p class="text-gray-dark leading-relaxed">Mantén tu rutina de ejercicios en nuestro gimnasio con equipos de última generación.</p>
                </div>
                <div class="bg-gradient-to-br from-gray-light to-white rounded-2xl p-8 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-3 group border-2 border-transparent hover:border-primary/30">
                    <div class="bg-gradient-to-br from-primary/20 to-primary/10 p-6 rounded-2xl w-fit mb-6 group-hover:scale-110 transition-transform duration-300">
                        <i class="ph ph-parking text-primary text-5xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-darker mb-3">Estacionamiento</h3>
                    <p class="text-gray-dark leading-relaxed">Estacionamiento privado con seguridad 24/7 y servicio de valet parking gratuito.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Rooms Gallery mejorado -->
    <section class="py-24 bg-gradient-to-br from-gray-light via-white to-primary/5 relative overflow-hidden">
        <!-- Fondo con círculos decorativos -->
        <div class="absolute top-0 right-0 w-96 h-96 bg-primary/5 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-primary/5 rounded-full blur-3xl"></div>
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-5xl font-black text-gray-darker text-center mb-16 flex items-center justify-center space-x-3">
                <i class="ph ph-house text-primary text-5xl"></i>
                <span>Nuestras <span class="text-primary">Habitaciones</span></span>
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="relative rounded-2xl overflow-hidden shadow-2xl hover:shadow-primary/30 transition-all duration-500 transform hover:-translate-y-4 group">
                    <div class="absolute inset-0 bg-gradient-to-t from-primary/90 via-primary/50 to-transparent z-10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <img src="https://images.unsplash.com/photo-1590490360182-c33d57733427?w=800&q=80" alt="Suite Deluxe" class="w-full h-80 object-cover group-hover:scale-125 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/50 to-transparent flex items-end p-8 z-20">
                        <div>
                            <h3 class="text-3xl font-black text-white mb-3">Suite Deluxe</h3>
                            <p class="text-white/90 text-lg font-medium">Lujo y comodidad en 45m² con vista panorámica</p>
                        </div>
                    </div>
                </div>
                <div class="relative rounded-2xl overflow-hidden shadow-2xl hover:shadow-primary/30 transition-all duration-500 transform hover:-translate-y-4 group">
                    <div class="absolute inset-0 bg-gradient-to-t from-primary/90 via-primary/50 to-transparent z-10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <img src="https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=800&q=80" alt="Suite Premium" class="w-full h-80 object-cover group-hover:scale-125 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/50 to-transparent flex items-end p-8 z-20">
                        <div>
                            <h3 class="text-3xl font-black text-white mb-3">Suite Premium</h3>
                            <p class="text-white/90 text-lg font-medium">Elegancia suprema con jacuzzi privado</p>
                        </div>
                    </div>
                </div>
                <div class="relative rounded-2xl overflow-hidden shadow-2xl hover:shadow-primary/30 transition-all duration-500 transform hover:-translate-y-4 group">
                    <div class="absolute inset-0 bg-gradient-to-t from-primary/90 via-primary/50 to-transparent z-10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <img src="https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=800&q=80" alt="Suite Presidencial" class="w-full h-80 object-cover group-hover:scale-125 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/50 to-transparent flex items-end p-8 z-20">
                        <div>
                            <h3 class="text-3xl font-black text-white mb-3">Suite Presidencial</h3>
                            <p class="text-white/90 text-lg font-medium">La experiencia definitiva en 120m² de lujo</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials mejorado -->
    <section class="py-24 bg-white relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-5xl font-black text-gray-darker text-center mb-16 flex items-center justify-center space-x-3">
                <i class="ph ph-chat-circle-dots text-primary text-5xl"></i>
                <span>Lo Que Dicen Nuestros <span class="text-primary">Huéspedes</span></span>
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-gradient-to-br from-gray-light to-white rounded-2xl p-8 shadow-xl hover:shadow-2xl transition-all duration-300 border-2 border-transparent hover:border-primary/30">
                    <div class="flex items-center space-x-1 mb-6 text-yellow-500 text-2xl">
                        <i class="ph ph-star-fill"></i>
                        <i class="ph ph-star-fill"></i>
                        <i class="ph ph-star-fill"></i>
                        <i class="ph ph-star-fill"></i>
                        <i class="ph ph-star-fill"></i>
                    </div>
                    <p class="text-gray-dark/80 mb-6 italic text-lg leading-relaxed">"Una experiencia inolvidable. El servicio es excepcional y las habitaciones son un verdadero paraíso. Definitivamente volveremos."</p>
                    <p class="text-gray-darker font-bold text-xl">- María González</p>
                </div>
                <div class="bg-gradient-to-br from-gray-light to-white rounded-2xl p-8 shadow-xl hover:shadow-2xl transition-all duration-300 border-2 border-transparent hover:border-primary/30">
                    <div class="flex items-center space-x-1 mb-6 text-yellow-500 text-2xl">
                        <i class="ph ph-star-fill"></i>
                        <i class="ph ph-star-fill"></i>
                        <i class="ph ph-star-fill"></i>
                        <i class="ph ph-star-fill"></i>
                        <i class="ph ph-star-fill"></i>
                    </div>
                    <p class="text-gray-dark/80 mb-6 italic text-lg leading-relaxed">"El mejor hotel en el que me he hospedado. Cada detalle está cuidado al máximo. El spa es simplemente espectacular."</p>
                    <p class="text-gray-darker font-bold text-xl">- Carlos Ramírez</p>
                </div>
                <div class="bg-gradient-to-br from-gray-light to-white rounded-2xl p-8 shadow-xl hover:shadow-2xl transition-all duration-300 border-2 border-transparent hover:border-primary/30">
                    <div class="flex items-center space-x-1 mb-6 text-yellow-500 text-2xl">
                        <i class="ph ph-star-fill"></i>
                        <i class="ph ph-star-fill"></i>
                        <i class="ph ph-star-fill"></i>
                        <i class="ph ph-star-fill"></i>
                        <i class="ph ph-star-fill"></i>
                    </div>
                    <p class="text-gray-dark/80 mb-6 italic text-lg leading-relaxed">"Perfecto para una escapada romántica. La atención personalizada y el ambiente hacen que te sientas como en casa."</p>
                    <p class="text-gray-darker font-bold text-xl">- Ana y Luis Martínez</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer mejorado -->
    <footer class="bg-gradient-to-br from-gray-darker to-gray-dark text-white py-16 relative overflow-hidden">
        <!-- Fondo decorativo -->
        <div class="absolute inset-0 opacity-10">
            <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="footerGrid" x="0" y="0" width="60" height="60" patternUnits="userSpaceOnUse">
                        <path d="M 60 0 L 0 0 0 60" fill="none" stroke="white" stroke-width="0.5"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#footerGrid)" />
            </svg>
        </div>
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <div class="flex items-center justify-center space-x-3 mb-6">
                    <div class="p-4 rounded-full bg-primary/20 backdrop-blur-md border-2 border-primary/50">
                        <i class="ph ph-moon-stars text-primary text-5xl animate-bounce-subtle"></i>
                    </div>
                    <span class="text-4xl font-black">Dulce Sueños</span>
                </div>
                <div class="flex items-center justify-center space-x-6 mb-8">
                    <a href="#" class="bg-white/10 hover:bg-primary p-4 rounded-full transition-all duration-300 transform hover:scale-125 hover:rotate-12 group border-2 border-white/20 hover:border-primary">
                        <i class="ph ph-facebook-logo text-2xl group-hover:animate-bounce-subtle"></i>
                    </a>
                    <a href="#" class="bg-white/10 hover:bg-primary p-4 rounded-full transition-all duration-300 transform hover:scale-125 hover:rotate-12 group border-2 border-white/20 hover:border-primary">
                        <i class="ph ph-instagram-logo text-2xl group-hover:animate-bounce-subtle"></i>
                    </a>
                    <a href="#" class="bg-white/10 hover:bg-primary p-4 rounded-full transition-all duration-300 transform hover:scale-125 hover:rotate-12 group border-2 border-white/20 hover:border-primary">
                        <i class="ph ph-twitter-logo text-2xl group-hover:animate-bounce-subtle"></i>
                    </a>
                    <a href="#" class="bg-white/10 hover:bg-primary p-4 rounded-full transition-all duration-300 transform hover:scale-125 hover:rotate-12 group border-2 border-white/20 hover:border-primary">
                        <i class="ph ph-youtube-logo text-2xl group-hover:animate-bounce-subtle"></i>
                    </a>
                </div>
                <p class="text-white/80 text-lg">&copy; 2025 Hotel Dulce Sueños. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <script>
        // Navbar scroll effect mejorado
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('shadow-2xl', 'bg-white');
                navbar.classList.remove('bg-white/95');
            } else {
                navbar.classList.remove('shadow-2xl', 'bg-white');
                navbar.classList.add('bg-white/95');
            }
        });
        
        // Efecto parallax suave
        window.addEventListener('scroll', function() {
            const scrolled = window.pageYOffset;
            const particles = document.querySelector('.particles');
            if (particles) {
                particles.style.transform = `translateY(${scrolled * 0.5}px)`;
            }
        });
    </script>
</body>
</html>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'Sistema de Hostal' ?></title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind Config Custom -->
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
                        'fade-in': 'fadeIn 0.5s ease-in',
                        'slide-up': 'slideUp 0.5s ease-out',
                        'slide-down': 'slideDown 0.5s ease-out',
                        'scale-in': 'scaleIn 0.3s ease-out',
                        'bounce-subtle': 'bounceSubtle 2s infinite',
                        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        slideUp: {
                            '0%': { transform: 'translateY(20px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' },
                        },
                        slideDown: {
                            '0%': { transform: 'translateY(-20px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' },
                        },
                        scaleIn: {
                            '0%': { transform: 'scale(0.9)', opacity: '0' },
                            '100%': { transform: 'scale(1)', opacity: '1' },
                        },
                        bounceSubtle: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-5px)' },
                        },
                    },
                }
            }
        }
    </script>
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        
        /* Animaciones personalizadas para iconos Phosphor */
        .ph-icon {
            transition: all 0.3s ease;
        }
        
        .ph-icon:hover {
            transform: scale(1.1) rotate(5deg);
        }
        
        /* Scrollbar personalizado */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #F1F3F4;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #1E88E5;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #1565C0;
        }
    </style>
</head>
<body class="bg-gray-light min-h-screen">


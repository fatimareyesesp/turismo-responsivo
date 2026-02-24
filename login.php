<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Salud Total</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <link rel="stylesheet" href="styles.css?v=<?php echo time(); ?>">

    <style>
        /* Estilos específicos solo para centrar el Login */
        body {
            /* Fondo corporativo suave */
            background-color: var(--color-fondo); 
            /* Esto hace que el body mida el 100% de la altura de la ventana */
            min-height: 100vh; 
            /* Centrado Flexbox */
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            max-width: 400px;
            width: 100%;
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1); /* Sombra elegante */
        }

        .login-header {
            background-color: var(--color-azul-corp);
            color: white;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
            padding: 20px;
            text-align: center;
        }
        
        /* Iconos dentro de los inputs */
        .input-group-text {
            background-color: #f8f9fa;
            border-color: #dee2e6;
            color: var(--color-azul-corp);
        }
    </style>
</head>
<body>

    <div class="card login-card">
        
        <div class="login-header">
            <h1 class="h3 mb-0 fw-bold"><i class="bi bi-hospital"></i> Salud Total</h1>
            <small>Sistema de Gestión</small>
        </div>

        <div class="card-body p-4">
            
            <h4 class="text-center mb-4" style="color: var(--color-azul-corp); font-weight: 700;">
                Bienvenido
            </h4>

            <form action="usuarioValidacion.php" method="POST">
                
                <div class="mb-3">
                    <label class="form-label text-secondary fw-bold small">Correo Electrónico</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                        <input class="form-control" type="email" name="email" placeholder="ejemplo@correo.com" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label text-secondary fw-bold small">Contraseña</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                        <input class="form-control" type="password" name="passw" placeholder="********" required>
                    </div>
                </div>

                <div class="d-grid">
                    <button class="btn btn-gold py-2" type="submit" name="Enviar">
                        Iniciar Sesión <i class="bi bi-box-arrow-in-right"></i>
                    </button>
                </div>

            </form>
        </div>
        
        <div class="card-footer text-center py-3 bg-light border-0 small text-muted" style="border-bottom-left-radius: 15px; border-bottom-right-radius: 15px;">
            &copy; <?php echo date("Y"); ?> Farmacia Salud Total
        </div>
    </div>

</body>
</html>
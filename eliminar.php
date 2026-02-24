<?php
session_start();
require 'db.php';

// 1. Verificar sesión
if (!isset($_SESSION['usuarioEmail'])) {
    header("Location: login.php");
    exit();
}

$mensaje = "";
$mostrarFormulario = true;
$medicamento = null;

// 2. LOGICA DE BORRADO LÓGICO (UPDATE activo = 0)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_a_eliminar = $_POST['id'];

    try {
        // Borrado lógico: Cambiamos activo a 0
        $sql = "UPDATE medicamentos SET activo = 0 WHERE id = :id";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id_a_eliminar]);

        $mensaje = "<div class='alert alert-success'>Medicamento eliminado correctamente.</div>";
        $mostrarFormulario = false;

    } catch (PDOException $e) {
        $mensaje = "<div class='alert alert-danger'>Error al eliminar: " . $e->getMessage() . "</div>";
    }
}

// 3. OBTENER DATOS (Para mostrar qué vamos a borrar)
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Solo buscamos si está activo, para no borrar uno ya borrado
    $stmt = $pdo->prepare("SELECT nombre, categoria, cantidad FROM medicamentos WHERE id = :id AND activo = 1");
    $stmt->execute([':id' => $id]);
    $medicamento = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$medicamento && $mostrarFormulario) {
        // Si no existe o ya estaba borrado
        echo "<script>alert('El medicamento no existe o ya fue eliminado.'); window.location.href='panel.php';</script>";
        exit;
    }
} elseif ($mostrarFormulario) {
    header("Location: panel.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Eliminar Medicamento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <link rel="stylesheet" href="styles.css?v=<?php echo time(); ?>">

    <style>
        /* Botón Rojo Personalizado (#A81111) */
        .btn-danger-custom {
            background-color: var(--color-rojo);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: 600;
            width: 100%;
            transition: 0.3s;
        }
        .btn-danger-custom:hover {
            background-color: #8a0e0e; /* Un poco más oscuro al pasar el mouse */
            color: white;
        }

        /* Texto de advertencia */
        .text-danger-custom {
            color: var(--color-rojo);
            font-weight: bold;
        }
    </style>
</head>
<body>
    
<header>
    <nav class="navbar navbar-expand-sm navbar-custom">
        <div class="container-fluid">
            <h4><i class="bi bi-trash"></i> Eliminar Registro</h4>
            <a href="panel.php" class="ms-auto">
                <button class="btn btn-logout"> 
                    <i class="bi bi-arrow-left"></i> Volver al panel
                </button>
            </a>
        </div>
    </nav>
</header>

    <div class="container mt-5">
        <div class="card shadow-sm border-0" style="max-width: 500px; margin: auto;">
            
            <div class="card-header text-white text-center py-3" style="background-color: var(--color-rojo);">
                <h4 class="mb-0"><i class="bi bi-exclamation-triangle-fill"></i> ADVERTENCIA</h4>
            </div>

            <div class="card-body p-4 text-center">
                
                <?php echo $mensaje; ?>

                <?php if ($mostrarFormulario): ?>
                    
                    <h5 class="card-title mt-2">¿Estás seguro de eliminar este medicamento?</h5>
                    
                    <div class="py-4">
                        <h2 class="text-danger-custom">
                            <?php echo htmlspecialchars($medicamento['nombre']); ?>
                        </h2>
                        <p class="text-muted mb-0">Categoría: <?php echo htmlspecialchars($medicamento['categoria']); ?></p>
                        <p class="text-muted">Stock actual: <?php echo htmlspecialchars($medicamento['cantidad']); ?></p>
                    </div>

                    <p class="small text-secondary mb-4">
                        Esta acción moverá el medicamento a la papelera y dejará de ser visible en el panel principal.
                    </p>
                    
                    <form method="POST" action="">
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                        
                        <div class="d-grid gap-3">
                            <button type="submit" class="btn btn-danger-custom">
                                <i class="bi bi-trash3-fill"></i> Sí, eliminar medicamento
                            </button>
                            
                            <a href="panel.php" class="btn btn-outline-secondary">
                                Cancelar, no borrar nada
                            </a>
                        </div>
                    </form>

                <?php else: ?>
                    <div class="d-grid gap-2 mt-3">
                        <a href="panel.php" class="btn-gold">Volver al Panel</a>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>

</body>
</html>
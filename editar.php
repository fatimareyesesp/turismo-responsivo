<?php
session_start();
require 'db.php';

// 1. Verificación de seguridad
if (!isset($_SESSION['usuarioEmail'])) {
    header("Location: login.php");
    exit();
}

$mensaje = "";
$medicamento = null;

// 2. Lógica para ACTUALIZAR datos
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $id = $_POST['id']; 
    $nombre = $_POST['nombre'];
    $categoria = $_POST['categoria'];
    $cantidad = $_POST['cantidad'];
    $precio = $_POST['precio'];
    $proveedor_id = $_POST['proveedor_id'];

    $sql = "UPDATE medicamentos SET 
            nombre = :nombre, 
            categoria = :categoria, 
            cantidad = :cantidad, 
            precio = :precio, 
            proveedor_id = :proveedor_id 
            WHERE id = :id";
            
    $stmt = $pdo->prepare($sql);
    
    try {
        $stmt->execute([
            ':nombre' => $nombre,
            ':categoria' => $categoria,
            ':cantidad' => $cantidad,
            ':precio' => $precio,
            ':proveedor_id' => $proveedor_id,
            ':id' => $id
        ]);
        
        $mensaje = "<div class='alert alert-success'>Medicamento actualizado correctamente.</div>";
        
    } catch (PDOException $e) {
        $mensaje = "<div class='alert alert-danger'>Error al actualizar: " . $e->getMessage() . "</div>";
    }
}

// 3. Obtener el ID del medicamento
if (isset($_GET['id'])) {
    $id_editar = $_GET['id'];
} elseif (isset($_POST['id'])) {
    $id_editar = $_POST['id'];
} else {
    header("Location: panel.php");
    exit();
}

// 4. Buscar los datos actuales
$stmt_med = $pdo->prepare("SELECT * FROM medicamentos WHERE id = :id");
$stmt_med->execute([':id' => $id_editar]);
$medicamento = $stmt_med->fetch(PDO::FETCH_ASSOC);

if (!$medicamento) {
    echo "El medicamento no existe.";
    exit();
}

// 5. Obtener lista de proveedores
$stmt_prov = $pdo->query("SELECT id, nombre FROM proveedores");
$proveedores = $stmt_prov->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Medicamento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <link rel="stylesheet" href="styles.css?v=<?php echo time(); ?>">
</head>
<body>
    
<header>
    <nav class="navbar navbar-expand-sm navbar-custom">
        <div class="container-fluid">
            <h4><i class="bi bi-pencil"></i> Editar Medicamento</h4>
            <a href="panel.php" class="ms-auto">
                <button class="btn btn-logout"> 
                    <i class="bi bi-arrow-left"></i> Volver al panel
                </button>
            </a>
        </div>
    </nav>
</header>

    <div class="container mt-4"> 
        <div class="card shadow-sm border-0" style="max-width: 600px; margin: auto;">
            
            <div class="card-header text-white" style="background-color: var(--color-azul-corp);">
                <h5 class="mb-0">
                    Editando: <strong><?php echo htmlspecialchars($medicamento['nombre']); ?></strong>
                </h5>
            </div>
            
            <div class="card-body p-4">
                
                <?php echo $mensaje; ?>

                <form method="POST" action="">
                    <input type="hidden" name="id" value="<?php echo $medicamento['id']; ?>">

                    <div class="mb-3">
                        <label class="form-label fw-bold" style="color: var(--color-azul-corp);">Nombre del Medicamento:</label>
                        <input type="text" name="nombre" class="form-control" 
                               value="<?php echo htmlspecialchars($medicamento['nombre']); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold" style="color: var(--color-azul-corp);">Categoría:</label>
                        <input type="text" name="categoria" class="form-control" 
                               value="<?php echo htmlspecialchars($medicamento['categoria']); ?>" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold" style="color: var(--color-azul-corp);">Cantidad (Stock):</label>
                            <input type="number" name="cantidad" class="form-control" min="1" 
                                   value="<?php echo htmlspecialchars($medicamento['cantidad']); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold" style="color: var(--color-azul-corp);">Precio Unitario ($):</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" name="precio" class="form-control" step="0.01" min="0" 
                                       value="<?php echo htmlspecialchars($medicamento['precio']); ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold" style="color: var(--color-azul-corp);">Proveedor:</label>
                        <select name="proveedor_id" class="form-select" required>
                            <option value="">Seleccione un proveedor </option>
                            <?php foreach ($proveedores as $prov): ?>
                                <?php 
                                    $selected = ($prov['id'] == $medicamento['proveedor_id']) ? 'selected' : ''; 
                                ?>
                                <option value="<?php echo $prov['id']; ?>" <?php echo $selected; ?>>
                                    <?php echo htmlspecialchars($prov['nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-gold">
                            <i class="bi bi-check-circle-fill"></i> Actualizar Medicamento
                        </button>
                        
                        <a href="panel.php" class="btn btn-gold-outline text-center">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>
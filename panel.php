<?php
require 'db.php';
session_start(); 

if (!isset($_SESSION['usuarioEmail'])) {
    header("Location: login.php");
    exit();
}

// Lógica de filtrado
$where = "WHERE m.activo = 1"; 
$parametros = [];

if (isset($_GET['categoria']) && !empty($_GET['categoria'])) {
    $categoria = $_GET['categoria'];
    $where .= " AND m.categoria LIKE :categoria"; 
    $parametros[':categoria'] = "%" . $categoria . "%";
}

$sql = "SELECT m.id, m.nombre, m.categoria, m.cantidad, m.precio, p.nombre AS nombre_proveedor 
        FROM medicamentos m 
        LEFT JOIN proveedores p ON m.proveedor_id = p.id 
        $where";

$stmt = $pdo->prepare($sql);
$stmt->execute($parametros);
$medicamentos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión Farmacia</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="styles.css?v=<?php echo time(); ?>">
</head>
<body>

<header>
    <nav class="navbar navbar-expand-sm navbar-custom">
        <div class="container-fluid">
            <h4><i class="bi bi-hospital"></i> Gestión de medicamentos</h4>
            <a href="logout.php" class="ms-auto btn-logout">
                Cerrar sesión <i class="bi bi-box-arrow-right"></i>
            </a>
        </div>
    </nav>
</header>

<div class="container mt-4">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 style="color: var(--color-azul-corp); font-weight: 700;">Inventario</h2>
        <a href="registro.php" class="btn-gold">
            Agregar <i class="bi bi-plus-circle-fill"></i>
        </a>
    </div>

    <div class="search-card">
        <form method="GET" action="panel.php" class="row g-3 align-items-center">
            <div class="col-auto">
                <label class="fw-bold" style="color: var(--color-azul-corp)">Filtrar por Categoria:</label>
            </div>
            <div class="col-auto">
                <input type="text" name="categoria" class="form-control" 
                       placeholder="Ej. Analgésico"
                       value="<?php echo isset($_GET['categoria']) ? htmlspecialchars($_GET['categoria']) : ''; ?>">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn-gold-outline">
                    Buscar <i class="bi bi-search"></i>
                </button>
            </div>
            <div class="col-auto">
                 <a href="panel.php" class="text-secondary text-decoration-none">Ver todos</a>
            </div>
        </form>
    </div>

    <table class="table-custom">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Categoria</th>
                <th class="text-center">Cantidad</th>
                <th>Precio</th>
                <th>Proveedor</th> 
                <th class="text-center">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($medicamentos) > 0): ?>
                <?php foreach ($medicamentos as $med): ?>
                <tr>
                    <td><?php echo htmlspecialchars($med['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($med['categoria']); ?></td>
                    
                    <td class=" text-center <?php echo $med['cantidad'] < 5 ? 'text-danger fw-bold' : ''; ?>">
                        <?php echo $med['cantidad']; ?>
                    </td>
                    
                    <td>$<?php echo number_format($med['precio'], 2); ?></td>
                    <td><?php echo htmlspecialchars($med['nombre_proveedor'] ?? 'Sin asignar'); ?></td>
                    
                    <td>
                        <a href="editar.php?id=<?php echo $med['id']; ?>" class="action-btn edit-icon" title="Editar">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <a href="eliminar.php?id=<?php echo $med['id']; ?>" class="action-btn delete-icon" title="Eliminar"
                           onclick="return confirm('¿Eliminar medicamento?');">
                           <i class="bi bi-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted">
                        <i class="bi bi-inbox fs-1"></i><br>
                        No hay medicamentos registrados.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</div>

</body>
</html>
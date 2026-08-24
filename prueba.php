<?php
require_once __DIR__ . '/classProducto.php';

$mensaje = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	try {
		$producto = new datosProductos(
			nom_producto: trim($_POST['nom_producto'] ?? ''),
			costoproducto: (float) ($_POST['costo'] ?? 0),
			porc_ventapro: (int) ($_POST['porc_venta'] ?? 0),
			precio_ventapro: (float) ($_POST['precio_venta'] ?? 0),
			imagenpro: trim($_POST['imagen'] ?? ''),
			stockpro: (int) ($_POST['stock'] ?? 0),
			fechapro: $_POST['fecha'] ?? null
		);

		if ($producto->get_nom_producto() === '') {
			throw new InvalidArgumentException('El nombre del producto es obligatorio.');
		}

		$producto->guardarProducto();
		$mensaje = 'Producto guardado correctamente.';
	} catch (Throwable $exception) {
		$error = $exception->getMessage();
	}
}

try {
	$conexion = new Conexion();
	$consulta = $conexion->query('SELECT * FROM inventario ORDER BY codigo DESC');
	$productos = $consulta->fetchAll();
	$total = count($productos);
} catch (PDOException $exception) {
	$productos = [];
	$total = 0;
	$error = 'No se pudo consultar la tabla inventario.';
}

function escapar($valor): string
{
	return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Inventario de productos</title>
	<style>
		body { font-family: Arial, sans-serif; margin: 32px auto; max-width: 1000px; padding: 0 20px; background: #eaf2fb; color: #0b2545; }
		h1, h2 { color: #0b3d91; }
		form { display: grid; gap: 12px; grid-template-columns: repeat(2, minmax(0, 1fr)); max-width: 650px; background: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 8px rgba(11, 61, 145, 0.15); }
		label { display: grid; gap: 5px; font-weight: bold; color: #0b3d91; }
		input { box-sizing: border-box; padding: 9px; width: 100%; border: 1px solid #a9c6e8; border-radius: 4px; background: #f5f9ff; }
		input:focus { outline: none; border-color: #1769aa; box-shadow: 0 0 0 2px rgba(23, 105, 170, 0.25); }
		button { background: #0b3d91; border: 0; color: white; cursor: pointer; padding: 11px 18px; border-radius: 4px; font-weight: bold; }
		button:hover { background: #072a66; }
		.completo { grid-column: 1 / -1; }
		.mensaje { color: #176b35; }
		.error { color: #a32121; }
		table { border-collapse: collapse; margin-top: 25px; width: 100%; background: #ffffff; box-shadow: 0 2px 8px rgba(11, 61, 145, 0.1); }
		th, td { border: 1px solid #c9dcf2; padding: 8px; text-align: left; }
		th { background: #0b3d91; color: white; }
		tr:nth-child(even) { background: #f0f6ff; }
	</style>
</head>
<body>
	<h1>Inventario de productos</h1>
	<?php if ($mensaje !== ''): ?><p class="mensaje"><?= escapar($mensaje) ?></p><?php endif; ?>
	<?php if ($error !== ''): ?><p class="error"><?= escapar($error) ?></p><?php endif; ?>

	<h2>Agregar producto</h2>
	<form method="post">
		<label>Nombre del producto
			<input type="text" name="nom_producto" required>
		</label>
		<label>Costo
			<input type="number" name="costo" min="0" step="0.01" required>
		</label>
		<label>Porcentaje de venta
			<input type="number" name="porc_venta" min="0" step="1" required>
		</label>
		<label>Precio de venta
			<input type="number" name="precio_venta" min="0" step="0.01" required>
		</label>
		<label>Imagen o nombre de archivo
			<input type="text" name="imagen">
		</label>
		<label>Stock
			<input type="number" name="stock" min="0" step="1" value="0">
		</label>
		<label>Fecha
			<input type="date" name="fecha">
		</label>
		<div class="completo"><button type="submit">Guardar producto</button></div>
	</form>

	<h2>Productos registrados: <?= escapar($total) ?></h2>
	<?php if ($productos !== []): ?>
	<table>
		<thead><tr><th>Código</th><th>Producto</th><th>Costo</th><th>Precio</th><th>Stock</th><th>Fecha</th></tr></thead>
		<tbody>
		<?php foreach ($productos as $producto): ?>
		<tr>
			<td><?= escapar($producto['codigo']) ?></td>
			<td><?= escapar($producto['nom_producto']) ?></td>
			<td><?= escapar($producto['costo']) ?></td>
			<td><?= escapar($producto['precio_venta']) ?></td>
			<td><?= escapar($producto['stock']) ?></td>
			<td><?= escapar($producto['Fecha']) ?></td>
		</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
	<?php else: ?>
	<p>No hay productos registrados.</p>
	<?php endif; ?>
</body>
</html>

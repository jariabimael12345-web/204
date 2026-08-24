CREATE TABLE IF NOT EXISTS inventario (
    codigo INT AUTO_INCREMENT PRIMARY KEY,
    nom_producto VARCHAR(255) NOT NULL,
    costo DECIMAL(10, 2) NOT NULL,
    porc_venta INT NOT NULL,
    precio_venta DECIMAL(10, 2) NOT NULL,
    Imagen VARCHAR(255),
    stock INT NOT NULL DEFAULT 0,
    Fecha DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

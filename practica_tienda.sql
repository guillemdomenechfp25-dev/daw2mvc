-- =========================================================
-- CUPRA GOURMET - practica_tienda (MySQL 8.0.16+)
-- Esquema unificado + datos iniciales corregidos
-- =========================================================

SET NAMES utf8mb4;

DROP DATABASE IF EXISTS practica_tienda;
CREATE DATABASE practica_tienda
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE practica_tienda;

-- =========================================================
-- 1. USUARIOS Y DIRECCIONES
-- =========================================================

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    telefono VARCHAR(20),
    direccion VARCHAR(255),
    fecha_nacimiento DATE,
    rol ENUM('usuario','admin') NOT NULL DEFAULT 'usuario',
    fecha_registro DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE direcciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    direccion VARCHAR(255) NOT NULL,
    ciudad VARCHAR(100) NOT NULL,
    codigo_postal VARCHAR(15) NOT NULL,
    provincia VARCHAR(100),
    principal BOOLEAN NOT NULL DEFAULT FALSE,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

-- =========================================================
-- 2. CATEGORIAS Y PRODUCTOS
-- =========================================================

CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    descripcion TEXT,
    activa BOOLEAN NOT NULL DEFAULT TRUE
);

CREATE TABLE productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    categoria_id INT NOT NULL,
    nombre VARCHAR(150) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10,2) NOT NULL,
    imagen VARCHAR(255),
    activo BOOLEAN NOT NULL DEFAULT TRUE,
    destacado BOOLEAN NOT NULL DEFAULT FALSE,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id),
    CHECK (precio >= 0)
);

-- =========================================================
-- 3. INGREDIENTES Y RECETAS
-- =========================================================

CREATE TABLE ingredientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    precio_extra DECIMAL(10,2) NOT NULL DEFAULT 0,
    unidad VARCHAR(30) NOT NULL DEFAULT 'unidad',
    activo BOOLEAN NOT NULL DEFAULT TRUE,
    CHECK (precio_extra >= 0)
);

CREATE TABLE producto_ingrediente (
    producto_id INT NOT NULL,
    ingrediente_id INT NOT NULL,
    cantidad DECIMAL(10,2) NOT NULL DEFAULT 1,
    obligatorio BOOLEAN NOT NULL DEFAULT FALSE,
    PRIMARY KEY (producto_id, ingrediente_id),
    FOREIGN KEY (producto_id) REFERENCES productos(id),
    FOREIGN KEY (ingrediente_id) REFERENCES ingredientes(id),
    CHECK (cantidad > 0)
);

-- =========================================================
-- 4. ALERGENOS
-- =========================================================

CREATE TABLE alergenos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    imagen VARCHAR(255)
);

CREATE TABLE ingrediente_alergeno (
    ingrediente_id INT NOT NULL,
    alergeno_id INT NOT NULL,
    PRIMARY KEY (ingrediente_id, alergeno_id),
    FOREIGN KEY (ingrediente_id) REFERENCES ingredientes(id),
    FOREIGN KEY (alergeno_id) REFERENCES alergenos(id)
);

-- =========================================================
-- 5. SALSAS
-- =========================================================

CREATE TABLE salsas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    descripcion TEXT,
    precio DECIMAL(10,2) NOT NULL DEFAULT 0,
    activa BOOLEAN NOT NULL DEFAULT TRUE,
    CHECK (precio >= 0)
);

CREATE TABLE producto_salsa (
    producto_id INT NOT NULL,
    salsa_id INT NOT NULL,
    PRIMARY KEY (producto_id, salsa_id),
    FOREIGN KEY (producto_id) REFERENCES productos(id),
    FOREIGN KEY (salsa_id) REFERENCES salsas(id)
);

-- =========================================================
-- 6. MENUS CONFIGURABLES
-- =========================================================

CREATE TABLE menus (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL UNIQUE,
    descripcion TEXT,
    precio DECIMAL(10,2) NOT NULL,
    activo BOOLEAN NOT NULL DEFAULT TRUE,
    CHECK (precio >= 0)
);

-- cantidad = cuántos productos de esa categoría se eligen en el menú.
CREATE TABLE menu_categoria (
    menu_id INT NOT NULL,
    categoria_id INT NOT NULL,
    cantidad INT NOT NULL DEFAULT 1,
    obligatorio BOOLEAN NOT NULL DEFAULT TRUE,
    PRIMARY KEY (menu_id, categoria_id),
    CONSTRAINT fk_menu_categoria_menu
        FOREIGN KEY (menu_id) REFERENCES menus(id),
    CONSTRAINT fk_menu_categoria_categoria
        FOREIGN KEY (categoria_id) REFERENCES categorias(id),
    CHECK (cantidad > 0)
);

-- =========================================================
-- 7. OFERTAS Y PROMOCIONES
-- =========================================================

CREATE TABLE ofertas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    descripcion TEXT,
    tipo ENUM('descuento','2x1','combo','codigo') NOT NULL,
    valor DECIMAL(10,2) NOT NULL DEFAULT 0,
    codigo VARCHAR(50) UNIQUE,
    fecha_inicio DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    fecha_fin DATETIME,
    activa BOOLEAN NOT NULL DEFAULT TRUE
);

CREATE TABLE oferta_producto (
    oferta_id INT NOT NULL,
    producto_id INT NOT NULL,
    PRIMARY KEY (oferta_id, producto_id),
    FOREIGN KEY (oferta_id) REFERENCES ofertas(id),
    FOREIGN KEY (producto_id) REFERENCES productos(id)
);

-- =========================================================
-- 8. CARRITOS
-- =========================================================

CREATE TABLE carritos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    fecha_creacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    actualizado DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

CREATE TABLE carrito_detalle (
    id INT AUTO_INCREMENT PRIMARY KEY,
    carrito_id INT NOT NULL,
    producto_id INT,
    menu_id INT,
    nombre_producto VARCHAR(150) NOT NULL,
    precio_unitario DECIMAL(10,2) NOT NULL,
    cantidad INT NOT NULL DEFAULT 1,
    subtotal DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (carrito_id) REFERENCES carritos(id),
    FOREIGN KEY (producto_id) REFERENCES productos(id),
    FOREIGN KEY (menu_id) REFERENCES menus(id),
    CHECK (cantidad > 0),
    CHECK (precio_unitario >= 0),
    CHECK (subtotal >= 0)
);

CREATE TABLE carrito_detalle_ingrediente (
    id INT AUTO_INCREMENT PRIMARY KEY,
    carrito_detalle_id INT NOT NULL,
    ingrediente_id INT,
    nombre_ingrediente VARCHAR(100) NOT NULL,
    cantidad DECIMAL(10,2) NOT NULL,
    precio_extra DECIMAL(10,2) NOT NULL DEFAULT 0,
    accion ENUM('incluido','quitado','añadido') NOT NULL,
    FOREIGN KEY (carrito_detalle_id) REFERENCES carrito_detalle(id),
    FOREIGN KEY (ingrediente_id) REFERENCES ingredientes(id),
    CHECK (cantidad > 0),
    CHECK (precio_extra >= 0)
);

-- Productos elegidos dentro de un menú.
CREATE TABLE carrito_menu_detalle (
    id INT AUTO_INCREMENT PRIMARY KEY,
    carrito_detalle_id INT NOT NULL,
    producto_id INT NOT NULL,
    categoria_id INT NOT NULL,
    nombre_producto VARCHAR(150) NOT NULL,
    precio_extra DECIMAL(10,2) NOT NULL DEFAULT 0,
    FOREIGN KEY (carrito_detalle_id) REFERENCES carrito_detalle(id),
    FOREIGN KEY (producto_id) REFERENCES productos(id),
    FOREIGN KEY (categoria_id) REFERENCES categorias(id)
);

-- =========================================================
-- 9. PEDIDOS E HISTORIAL
-- =========================================================

CREATE TABLE pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    estado ENUM('pendiente','confirmado','preparando','enviado','entregado','cancelado')
        NOT NULL DEFAULT 'pendiente',
    fecha_pedido DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    subtotal DECIMAL(10,2) NOT NULL,
    descuento DECIMAL(10,2) NOT NULL DEFAULT 0,
    gastos_envio DECIMAL(10,2) NOT NULL DEFAULT 0,
    total DECIMAL(10,2) NOT NULL,
    metodo_pago VARCHAR(50) NOT NULL DEFAULT 'simulado',
    estado_pago ENUM('pendiente','pagado','fallido') NOT NULL DEFAULT 'pendiente',
    tipo_entrega ENUM('domicilio','recogida') NOT NULL,
    direccion_entrega VARCHAR(255),
    ciudad_entrega VARCHAR(100),
    codigo_postal VARCHAR(15),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    CHECK (subtotal >= 0),
    CHECK (descuento >= 0),
    CHECK (gastos_envio >= 0),
    CHECK (total >= 0)
);

CREATE TABLE pedido_detalle (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT NOT NULL,
    producto_id INT,
    menu_id INT,
    nombre_producto VARCHAR(150) NOT NULL,
    precio_unitario DECIMAL(10,2) NOT NULL,
    cantidad INT NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id),
    FOREIGN KEY (producto_id) REFERENCES productos(id),
    FOREIGN KEY (menu_id) REFERENCES menus(id),
    CHECK (cantidad > 0),
    CHECK (precio_unitario >= 0),
    CHECK (subtotal >= 0)
);

CREATE TABLE pedido_detalle_ingrediente (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_detalle_id INT NOT NULL,
    ingrediente_id INT,
    nombre_ingrediente VARCHAR(100) NOT NULL,
    cantidad DECIMAL(10,2) NOT NULL,
    precio_extra DECIMAL(10,2) NOT NULL DEFAULT 0,
    accion ENUM('incluido','quitado','añadido') NOT NULL,
    FOREIGN KEY (pedido_detalle_id) REFERENCES pedido_detalle(id),
    FOREIGN KEY (ingrediente_id) REFERENCES ingredientes(id),
    CHECK (cantidad > 0),
    CHECK (precio_extra >= 0)
);

CREATE TABLE pedido_menu_detalle (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_detalle_id INT NOT NULL,
    producto_id INT,
    categoria_id INT,
    nombre_producto VARCHAR(150) NOT NULL,
    precio_extra DECIMAL(10,2) NOT NULL DEFAULT 0,
    FOREIGN KEY (pedido_detalle_id) REFERENCES pedido_detalle(id),
    FOREIGN KEY (producto_id) REFERENCES productos(id),
    FOREIGN KEY (categoria_id) REFERENCES categorias(id)
);

-- =========================================================
-- 10. DATOS: CATEGORIAS
-- =========================================================

INSERT INTO categorias (nombre, descripcion) VALUES
('Hamburguesas','Hamburguesas gourmet inspiradas en CUPRA.'),
('Complementos','Patatas y otros acompañamientos.'),
('Postres','Postres artesanales de la casa.'),
('Bebidas','Bebidas frías y refrescos.');

-- =========================================================
-- 11. DATOS: PRODUCTOS
-- =========================================================

-- HAMBURGUESAS (categoria 1)
INSERT INTO productos (categoria_id,nombre,descripcion,precio,imagen) VALUES
(1,'Formentor VZ','La insignia de la casa. Brioche negro, salsa cobre BBQ, ternera, cheddar curado, bacon crujiente y cebolla caramelizada.',15.90,'formentor-vz.jpg'),
(1,'Born','Una propuesta eléctrica y vegetal con pan de espelta, hummus de remolacha, burger de lentejas y quinoa, aguacate, rúcula, tomate y germinados.',14.90,'born.jpg'),
(1,'León Cup','La esencia de una clásica gourmet: brioche, mayonesa suave, ternera, emmental, lechuga, tomate, pepinillo y ketchup.',13.90,'leon-cup.jpg'),
(1,'Ateca','Potencia y sabor con pan de patata, mostaza antigua, ternera, provolone, pimientos asados y cebolla crujiente.',15.50,'ateca.jpg'),
(1,'Tavascan','Una combinación intensa de pan negro de carbón, alioli de trufa, ternera, brie, champiñones salteados, cebolla caramelizada y rúcula.',16.90,'tavascan.jpg'),
(1,'Terramar','Pan rústico de semillas, chimichurri, ternera, gouda, chorizo ibérico, piquillo y cebolla roja.',16.50,'terramar.jpg'),
(1,'Raval','Brioche, sriracha-mayo, pollo crujiente, cheddar, iceberg, pepinillo y cebolla morada encurtida.',14.90,'raval.jpg'),
(1,'Tindaya','Pan de sésamo, teriyaki, ternera, piña a la plancha, queso americano, cebolla crujiente y col lombarda.',15.90,'tindaya.jpg'),
(1,'DarkRebel','Una bestia de pan negro, BBQ picante, doble ternera, doble cheddar, bacon crujiente, jalapeños y cebolla crujiente.',17.90,'darkrebel.jpg'),
(1,'Urban Rebel','Brioche, mayonesa de lima, pulled pork, coleslaw, pepinillo y cheddar.',15.50,'urban-rebel.jpg'),
(1,'VZ5 Turbo','Una explosión de cinco quesos: brioche, salsa cremosa de queso, ternera, cheddar, emmental, brie, gouda, azul y cebolla caramelizada.',17.50,'vz5-turbo.jpg'),
(1,'Cupra Racing','Pan de patata, salsa picante cobre, ternera, pepper jack, bacon, huevo frito y aros de cebolla.',16.90,'cupra-racing.jpg'),
(1,'Leon Sportstourer','Una hamburguesa para compartir: brioche, salsa especial de la casa, triple ternera, triple cheddar, lechuga, tomate, pepinillo y cebolla fresca.',19.90,'leon-sportstourer.jpg'),
(1,'Formentor e-Hybrid','Una combinación equilibrada de pan integral, salsa de yogur y hierbas, carne mixta de ternera y setas, queso de cabra, tomate seco y espinacas baby.',15.90,'formentor-e-hybrid.jpg');

-- COMPLEMENTOS (categoria 2)
INSERT INTO productos (categoria_id,nombre,descripcion,precio,imagen) VALUES
(2,'Clásicas Pit Stop','Patatas fritas finas con un toque de sal.',3.90,'clasicas-pit-stop.jpg'),
(2,'Gran Turismo','Patatas gajo con piel y mezcla de especias.',4.50,'gran-turismo.jpg'),
(2,'Turbo Cheese','Patatas fritas cubiertas de cheddar fundido y bacon crujiente.',6.90,'turbo-cheese.jpg'),
(2,'Cobre Edition','Patatas fritas con salsa cobre BBQ y cebolla crujiente.',6.50,'cobre-edition.jpg'),
(2,'Boniato Eléctrico','Boniato frito acompañado de alioli suave.',6.50,'boniato-electrico.jpg'),
(2,'Escudo Cupra Bites','Bocaditos de queso rebozado con mermelada de pimiento.',8.50,'escudo-cupra-bites.jpg'),
(2,'Aros de Cobre','Aros de cebolla con un delicioso glaseado cobre.',7.00,'aros-de-cobre.jpg'),
(2,'Nuggets Hexágono','Nuggets de pollo crujientes con tres salsas.',8.50,'nuggets-hexagono.jpg'),
(2,'Pit Wings','Alitas de pollo con salsa cobre BBQ o salsa picante cobre.',9.90,'pit-wings.jpg'),
(2,'Born Salad','Ensalada fresca de aguacate, frutos secos y vinagreta de cítricos.',8.90,'born-salad.jpg');

-- POSTRES (categoria 3)
INSERT INTO productos (categoria_id,nombre,descripcion,precio,imagen) VALUES
(3,'Tarta Formentor','Tarta de queso con base de galleta y coulis de frutos rojos.',6.50,'tarta-formentor.jpg'),
(3,'Brownie Cobre','Brownie de chocolate acompañado de caramelo salado.',5.90,'brownie-cobre.jpg'),
(3,'Helado Born','Dos bolas de helado a elegir entre vainilla, chocolate y fresa.',5.50,'helado-born.jpg'),
(3,'Churros Turbo','Churros tradicionales acompañados de chocolate negro para mojar.',5.90,'churros-turbo.jpg'),
(3,'Cheesecake Tavascan','Cheesecake en vasito con crumble y arándanos.',6.50,'cheesecake-tavascan.jpg'),
(3,'Sundae VZ','Helado de vainilla, nata montada, sirope de chocolate y barquillo.',5.90,'sundae-vz.jpg'),
(3,'Tarta Terramar','Tarta de zanahoria con frosting de queso.',6.50,'tarta-terramar.jpg');

-- BEBIDAS (categoria 4)
INSERT INTO productos (categoria_id,nombre,descripcion,precio,imagen) VALUES
(4,'Coca Cola','Refresco Coca Cola.',2.90,'coca-cola.jpg'),
(4,'Coca Cola Zero','Refresco Coca Cola sin azúcar.',2.90,'coca-cola-zero.jpg'),
(4,'Fanta Limón','Refresco Fanta de limón.',2.90,'fanta-limon.jpg'),
(4,'Fanta Naranja','Refresco Fanta de naranja.',2.90,'fanta-naranja.jpg'),
(4,'Nestea Melocotón','Té frío sabor melocotón.',3.00,'nestea-melocoton.jpg'),
(4,'Vichy Catalán','Agua mineral con gas.',3.20,'vichy-catalan.jpg'),
(4,'Font Vella','Agua mineral natural.',2.50,'font-vella.jpg'),
(4,'Estrella Damm','Cerveza Estrella Damm.',3.50,'estrella-damm.jpg'),
(4,'Mahou 5 Estrellas','Cerveza Mahou.',3.50,'mahou-5-estrellas.jpg'),
(4,'San Miguel','Cerveza San Miguel.',3.50,'san-miguel.jpg'),
(4,'Estrella Galicia','Cerveza Estrella Galicia.',3.80,'estrella-galicia.jpg'),
(4,'Guinness','Cerveza negra Guinness.',4.50,'guinness.jpg'),
(4,'Damm Negra','Cerveza negra Damm.',4.00,'damm-negra.jpg');

-- =========================================================
-- 12. DATOS: INGREDIENTES (sin duplicados)
-- precio_extra = suplemento al añadir el ingrediente.
-- =========================================================

INSERT INTO ingredientes (nombre,precio_extra,unidad) VALUES
-- Panes
('Pan brioche negro',0.80,'unidad'),
('Pan de espelta',0.90,'unidad'),
('Pan brioche',0.70,'unidad'),
('Pan de patata',0.80,'unidad'),
('Pan negro de carbón',0.90,'unidad'),
('Pan rústico de semillas',0.90,'unidad'),
('Pan de sésamo',0.80,'unidad'),
('Pan integral',0.80,'unidad'),
-- Carnes y proteínas
('Ternera 100 g',1.80,'unidad'),
('Ternera 120 g',2.00,'unidad'),
('Ternera 150 g',2.40,'unidad'),
('Ternera 180 g',2.80,'unidad'),
('Ternera 200 g',3.00,'unidad'),
('Carne mixta ternera y setas 180 g',2.80,'unidad'),
('Hamburguesa de lentejas y quinoa',2.50,'unidad'),
('Pollo crujiente',2.20,'unidad'),
('Pulled pork',2.20,'ración'),
('Bacon crujiente',1.00,'ración'),
('Chorizo ibérico',1.20,'ración'),
('Huevo frito',1.00,'unidad'),
-- Quesos
('Queso cheddar',0.90,'loncha'),
('Queso emmental',0.90,'loncha'),
('Queso provolone',1.00,'loncha'),
('Queso brie',1.00,'loncha'),
('Queso gouda',0.90,'loncha'),
('Queso americano',0.90,'loncha'),
('Queso pepper jack',1.00,'loncha'),
('Queso azul',1.00,'ración'),
('Queso de cabra',1.00,'ración'),
-- Salsas de hamburguesa
('Salsa cobre BBQ',0.60,'ración'),
('Mayonesa suave',0.50,'ración'),
('Ketchup',0.50,'ración'),
('Mostaza antigua',0.50,'ración'),
('Alioli de trufa',0.80,'ración'),
('Chimichurri',0.60,'ración'),
('Sriracha-mayo',0.60,'ración'),
('Teriyaki',0.60,'ración'),
('BBQ picante',0.70,'ración'),
('Mayonesa de lima',0.60,'ración'),
('Salsa cremosa de queso',0.80,'ración'),
('Salsa picante cobre',0.70,'ración'),
('Salsa especial de la casa',0.70,'ración'),
('Salsa de yogur y hierbas',0.60,'ración'),
('Hummus de remolacha',0.70,'ración'),
-- Verduras y otros
('Cebolla caramelizada',0.60,'ración'),
('Cebolla crujiente',0.60,'ración'),
('Cebolla roja',0.30,'ración'),
('Cebolla morada encurtida',0.40,'ración'),
('Cebolla fresca',0.30,'ración'),
('Aguacate',0.90,'ración'),
('Rúcula',0.40,'ración'),
('Tomate',0.40,'rodaja'),
('Germinados',0.50,'ración'),
('Lechuga',0.30,'ración'),
('Lechuga iceberg',0.30,'ración'),
('Pepinillo',0.30,'unidad'),
('Pimientos asados',0.60,'ración'),
('Pimiento del piquillo',0.60,'unidad'),
('Champiñones salteados',0.70,'ración'),
('Piña a la plancha',0.70,'rodaja'),
('Col lombarda',0.40,'ración'),
('Jalapeños',0.50,'ración'),
('Coleslaw',0.70,'ración'),
('Aros de cebolla',0.70,'ración'),
('Tomate seco',0.60,'ración'),
('Espinacas baby',0.50,'ración'),
-- Complementos
('Patatas fritas finas',1.00,'ración'),
('Sal',0.10,'ración'),
('Patatas gajo con piel',1.20,'ración'),
('Mezcla de especias',0.20,'ración'),
('Salsa de cheddar fundido',0.90,'ración'),
('Boniato frito',1.40,'ración'),
('Alioli suave',0.60,'ración'),
('Bocaditos de queso rebozado',2.00,'ración'),
('Mermelada de pimiento',0.50,'ración'),
('Glaseado cobre',0.60,'ración'),
('Nuggets de pollo hexagonales',2.00,'ración'),
('Alitas de pollo',2.50,'ración'),
('Lechuga para ensalada',0.30,'ración'),
('Frutos secos',0.80,'ración'),
('Vinagreta de cítricos',0.50,'ración'),
-- Postres
('Tarta de queso',2.00,'ración'),
('Base de galleta',0.50,'ración'),
('Coulis de frutos rojos',0.60,'ración'),
('Brownie de chocolate',1.80,'ración'),
('Salsa de caramelo salado',0.60,'ración'),
('Helado de vainilla',1.00,'bola'),
('Helado de chocolate',1.00,'bola'),
('Helado de fresa',1.00,'bola'),
('Churros',1.20,'ración'),
('Chocolate negro para mojar',0.70,'ración'),
('Cheesecake en vasito',2.00,'ración'),
('Crumble',0.40,'ración'),
('Arándanos',0.50,'ración'),
('Nata montada',0.50,'ración'),
('Sirope de chocolate',0.40,'ración'),
('Barquillo',0.30,'unidad'),
('Tarta de zanahoria',2.00,'ración'),
('Frosting de queso',0.60,'ración');

-- =========================================================
-- 13. DATOS: RECETAS
-- cantidad = unidades en la receta; obligatorio = pan y carne.
-- =========================================================

-- Formentor VZ
INSERT INTO producto_ingrediente (producto_id,ingrediente_id,cantidad,obligatorio)
SELECT p.id,i.id,
CASE WHEN i.nombre='Pan brioche negro' THEN 2 ELSE 1 END,
i.nombre IN ('Pan brioche negro','Ternera 180 g')
FROM productos p CROSS JOIN ingredientes i
WHERE p.nombre='Formentor VZ'
AND i.nombre IN ('Pan brioche negro','Salsa cobre BBQ','Ternera 180 g','Queso cheddar','Bacon crujiente','Cebolla caramelizada');

-- Born
INSERT INTO producto_ingrediente (producto_id,ingrediente_id,cantidad,obligatorio)
SELECT p.id,i.id,
CASE WHEN i.nombre='Pan de espelta' THEN 2 ELSE 1 END,
i.nombre IN ('Pan de espelta','Hamburguesa de lentejas y quinoa')
FROM productos p CROSS JOIN ingredientes i
WHERE p.nombre='Born'
AND i.nombre IN ('Pan de espelta','Hummus de remolacha','Hamburguesa de lentejas y quinoa','Aguacate','Rúcula','Tomate','Germinados');

-- León Cup
INSERT INTO producto_ingrediente (producto_id,ingrediente_id,cantidad,obligatorio)
SELECT p.id,i.id,
CASE WHEN i.nombre='Pan brioche' THEN 2 ELSE 1 END,
i.nombre IN ('Pan brioche','Ternera 150 g')
FROM productos p CROSS JOIN ingredientes i
WHERE p.nombre='León Cup'
AND i.nombre IN ('Pan brioche','Mayonesa suave','Ternera 150 g','Queso emmental','Lechuga','Tomate','Pepinillo','Ketchup');

-- Ateca
INSERT INTO producto_ingrediente (producto_id,ingrediente_id,cantidad,obligatorio)
SELECT p.id,i.id,
CASE WHEN i.nombre='Pan de patata' THEN 2 ELSE 1 END,
i.nombre IN ('Pan de patata','Ternera 180 g')
FROM productos p CROSS JOIN ingredientes i
WHERE p.nombre='Ateca'
AND i.nombre IN ('Pan de patata','Mostaza antigua','Ternera 180 g','Queso provolone','Pimientos asados','Cebolla crujiente');

-- Tavascan (usa Ternera 200 g)
INSERT INTO producto_ingrediente (producto_id,ingrediente_id,cantidad,obligatorio)
SELECT p.id,i.id,
CASE WHEN i.nombre='Pan negro de carbón' THEN 2 ELSE 1 END,
i.nombre IN ('Pan negro de carbón','Ternera 200 g')
FROM productos p CROSS JOIN ingredientes i
WHERE p.nombre='Tavascan'
AND i.nombre IN ('Pan negro de carbón','Alioli de trufa','Ternera 200 g','Queso brie','Champiñones salteados','Cebolla caramelizada','Rúcula');

-- Terramar
INSERT INTO producto_ingrediente (producto_id,ingrediente_id,cantidad,obligatorio)
SELECT p.id,i.id,
CASE WHEN i.nombre='Pan rústico de semillas' THEN 2 ELSE 1 END,
i.nombre IN ('Pan rústico de semillas','Ternera 180 g')
FROM productos p CROSS JOIN ingredientes i
WHERE p.nombre='Terramar'
AND i.nombre IN ('Pan rústico de semillas','Chimichurri','Ternera 180 g','Queso gouda','Chorizo ibérico','Pimiento del piquillo','Cebolla roja');

-- Raval
INSERT INTO producto_ingrediente (producto_id,ingrediente_id,cantidad,obligatorio)
SELECT p.id,i.id,
CASE WHEN i.nombre='Pan brioche' THEN 2 ELSE 1 END,
i.nombre IN ('Pan brioche','Pollo crujiente')
FROM productos p CROSS JOIN ingredientes i
WHERE p.nombre='Raval'
AND i.nombre IN ('Pan brioche','Sriracha-mayo','Pollo crujiente','Queso cheddar','Lechuga iceberg','Pepinillo','Cebolla morada encurtida');

-- Tindaya
INSERT INTO producto_ingrediente (producto_id,ingrediente_id,cantidad,obligatorio)
SELECT p.id,i.id,
CASE WHEN i.nombre='Pan de sésamo' THEN 2 ELSE 1 END,
i.nombre IN ('Pan de sésamo','Ternera 180 g')
FROM productos p CROSS JOIN ingredientes i
WHERE p.nombre='Tindaya'
AND i.nombre IN ('Pan de sésamo','Teriyaki','Ternera 180 g','Piña a la plancha','Queso americano','Cebolla crujiente','Col lombarda');

-- DarkRebel (pan: Pan brioche negro; doble ternera y doble cheddar)
INSERT INTO producto_ingrediente (producto_id,ingrediente_id,cantidad,obligatorio)
SELECT p.id,i.id,
CASE
    WHEN i.nombre='Pan brioche negro' THEN 2
    WHEN i.nombre='Ternera 120 g' THEN 2
    WHEN i.nombre='Queso cheddar' THEN 2
    ELSE 1
END,
i.nombre IN ('Pan brioche negro','Ternera 120 g')
FROM productos p CROSS JOIN ingredientes i
WHERE p.nombre='DarkRebel'
AND i.nombre IN ('Pan brioche negro','BBQ picante','Ternera 120 g','Queso cheddar','Bacon crujiente','Jalapeños','Cebolla crujiente');

-- Urban Rebel
INSERT INTO producto_ingrediente (producto_id,ingrediente_id,cantidad,obligatorio)
SELECT p.id,i.id,
CASE WHEN i.nombre='Pan brioche' THEN 2 ELSE 1 END,
i.nombre IN ('Pan brioche','Pulled pork')
FROM productos p CROSS JOIN ingredientes i
WHERE p.nombre='Urban Rebel'
AND i.nombre IN ('Pan brioche','Mayonesa de lima','Pulled pork','Coleslaw','Pepinillo','Queso cheddar');

-- VZ5 Turbo (usa Queso gouda)
INSERT INTO producto_ingrediente (producto_id,ingrediente_id,cantidad,obligatorio)
SELECT p.id,i.id,
CASE WHEN i.nombre='Pan brioche' THEN 2 ELSE 1 END,
i.nombre IN ('Pan brioche','Ternera 200 g')
FROM productos p CROSS JOIN ingredientes i
WHERE p.nombre='VZ5 Turbo'
AND i.nombre IN ('Pan brioche','Salsa cremosa de queso','Ternera 200 g','Queso cheddar','Queso emmental','Queso brie','Queso gouda','Queso azul','Cebolla caramelizada');

-- Cupra Racing
INSERT INTO producto_ingrediente (producto_id,ingrediente_id,cantidad,obligatorio)
SELECT p.id,i.id,
CASE WHEN i.nombre='Pan de patata' THEN 2 ELSE 1 END,
i.nombre IN ('Pan de patata','Ternera 180 g')
FROM productos p CROSS JOIN ingredientes i
WHERE p.nombre='Cupra Racing'
AND i.nombre IN ('Pan de patata','Salsa picante cobre','Ternera 180 g','Queso pepper jack','Bacon crujiente','Huevo frito','Aros de cebolla');

-- Leon Sportstourer (triple ternera y triple cheddar)
INSERT INTO producto_ingrediente (producto_id,ingrediente_id,cantidad,obligatorio)
SELECT p.id,i.id,
CASE
    WHEN i.nombre='Pan brioche' THEN 2
    WHEN i.nombre='Ternera 100 g' THEN 3
    WHEN i.nombre='Queso cheddar' THEN 3
    ELSE 1
END,
i.nombre IN ('Pan brioche','Ternera 100 g')
FROM productos p CROSS JOIN ingredientes i
WHERE p.nombre='Leon Sportstourer'
AND i.nombre IN ('Pan brioche','Salsa especial de la casa','Ternera 100 g','Queso cheddar','Lechuga','Tomate','Pepinillo','Cebolla fresca');

-- Formentor e-Hybrid
INSERT INTO producto_ingrediente (producto_id,ingrediente_id,cantidad,obligatorio)
SELECT p.id,i.id,
CASE WHEN i.nombre='Pan integral' THEN 2 ELSE 1 END,
i.nombre IN ('Pan integral','Carne mixta ternera y setas 180 g')
FROM productos p CROSS JOIN ingredientes i
WHERE p.nombre='Formentor e-Hybrid'
AND i.nombre IN ('Pan integral','Salsa de yogur y hierbas','Carne mixta ternera y setas 180 g','Queso de cabra','Tomate seco','Espinacas baby');

-- Complementos
INSERT INTO producto_ingrediente (producto_id,ingrediente_id,cantidad,obligatorio)
SELECT p.id,i.id,1,FALSE
FROM productos p CROSS JOIN ingredientes i
WHERE
   (p.nombre='Clásicas Pit Stop'   AND i.nombre IN ('Patatas fritas finas','Sal'))
OR (p.nombre='Gran Turismo'        AND i.nombre IN ('Patatas gajo con piel','Mezcla de especias'))
OR (p.nombre='Turbo Cheese'        AND i.nombre IN ('Patatas fritas finas','Salsa de cheddar fundido','Bacon crujiente'))
OR (p.nombre='Cobre Edition'       AND i.nombre IN ('Patatas fritas finas','Salsa cobre BBQ','Cebolla crujiente'))
OR (p.nombre='Boniato Eléctrico'   AND i.nombre IN ('Boniato frito','Alioli suave'))
OR (p.nombre='Escudo Cupra Bites'  AND i.nombre IN ('Bocaditos de queso rebozado','Mermelada de pimiento'))
OR (p.nombre='Aros de Cobre'       AND i.nombre IN ('Aros de cebolla','Glaseado cobre'))
OR (p.nombre='Nuggets Hexágono'    AND i.nombre IN ('Nuggets de pollo hexagonales'))
OR (p.nombre='Pit Wings'           AND i.nombre IN ('Alitas de pollo'))
OR (p.nombre='Born Salad'          AND i.nombre IN ('Lechuga para ensalada','Aguacate','Frutos secos','Vinagreta de cítricos'));

-- Postres
INSERT INTO producto_ingrediente (producto_id,ingrediente_id,cantidad,obligatorio)
SELECT p.id,i.id,1,FALSE
FROM productos p CROSS JOIN ingredientes i
WHERE
   (p.nombre='Tarta Formentor'     AND i.nombre IN ('Tarta de queso','Base de galleta','Coulis de frutos rojos'))
OR (p.nombre='Brownie Cobre'       AND i.nombre IN ('Brownie de chocolate','Salsa de caramelo salado'))
OR (p.nombre='Helado Born'         AND i.nombre IN ('Helado de vainilla','Helado de chocolate','Helado de fresa'))
OR (p.nombre='Churros Turbo'       AND i.nombre IN ('Churros','Chocolate negro para mojar'))
OR (p.nombre='Cheesecake Tavascan' AND i.nombre IN ('Cheesecake en vasito','Crumble','Arándanos'))
OR (p.nombre='Sundae VZ'           AND i.nombre IN ('Helado de vainilla','Nata montada','Sirope de chocolate','Barquillo'))
OR (p.nombre='Tarta Terramar'      AND i.nombre IN ('Tarta de zanahoria','Frosting de queso'));

-- =========================================================
-- 14. DATOS: SALSAS PARA PATATAS
-- =========================================================

INSERT INTO salsas (nombre,descripcion,precio) VALUES
('Ketchup','Ketchup clásico',0.80),
('Mayonesa','Mayonesa suave',0.80),
('Alioli','Alioli casero de ajo',0.90),
('Barbacoa','Salsa BBQ ahumada',0.90),
('Brava','Salsa picante para patatas',1.00);

INSERT INTO producto_salsa (producto_id,salsa_id)
SELECT p.id,s.id
FROM productos p CROSS JOIN salsas s
WHERE p.nombre IN (
    'Clásicas Pit Stop',
    'Gran Turismo',
    'Turbo Cheese',
    'Cobre Edition',
    'Boniato Eléctrico'
);

-- Helado Born: la aplicación debe obligar a elegir exactamente 2 bolas
-- entre vainilla, chocolate y fresa (se pueden repetir sabores).

-- =========================================================
-- 15. DATOS: ALERGENOS (14 de referencia)
-- =========================================================

INSERT INTO alergenos (nombre) VALUES
('Gluten'),
('Crustáceos'),
('Huevos'),
('Pescado'),
('Cacahuetes'),
('Soja'),
('Leche'),
('Frutos de cáscara'),
('Apio'),
('Mostaza'),
('Granos de sésamo'),
('Dióxido de azufre y sulfitos'),
('Altramuces'),
('Moluscos');

-- =========================================================
-- 16. DATOS: MENUS
-- =========================================================

INSERT INTO menus (nombre,descripcion,precio) VALUES
('Experiencia CUPRA','Hamburguesa, complemento, bebida y postre a elegir.',25.90),
('CUPRA Essential','Hamburguesa, complemento y bebida a elegir.',21.90),
('Dúo Dinámico','Dos hamburguesas y dos bebidas a elegir.',31.90),
('Pausa Gourmet','Hamburguesa y bebida a elegir.',17.90),
('Plan Compartido','Dos hamburguesas, dos complementos y dos bebidas a elegir.',39.90);

INSERT INTO menu_categoria (menu_id,categoria_id,cantidad)
SELECT m.id,c.id,1
FROM menus m CROSS JOIN categorias c
WHERE m.nombre='Experiencia CUPRA'
AND c.nombre IN ('Hamburguesas','Complementos','Bebidas','Postres');

INSERT INTO menu_categoria (menu_id,categoria_id,cantidad)
SELECT m.id,c.id,1
FROM menus m CROSS JOIN categorias c
WHERE m.nombre='CUPRA Essential'
AND c.nombre IN ('Hamburguesas','Complementos','Bebidas');

INSERT INTO menu_categoria (menu_id,categoria_id,cantidad)
SELECT m.id,c.id,2
FROM menus m CROSS JOIN categorias c
WHERE m.nombre='Dúo Dinámico'
AND c.nombre IN ('Hamburguesas','Bebidas');

INSERT INTO menu_categoria (menu_id,categoria_id,cantidad)
SELECT m.id,c.id,1
FROM menus m CROSS JOIN categorias c
WHERE m.nombre='Pausa Gourmet'
AND c.nombre IN ('Hamburguesas','Bebidas');

INSERT INTO menu_categoria (menu_id,categoria_id,cantidad)
SELECT m.id,c.id,2
FROM menus m CROSS JOIN categorias c
WHERE m.nombre='Plan Compartido'
AND c.nombre IN ('Hamburguesas','Complementos','Bebidas');

-- =========================================================
-- 17. DATOS: OFERTAS DE EJEMPLO
-- =========================================================

INSERT INTO ofertas (nombre,descripcion,tipo,valor,activa) VALUES
('Doble CUPRA','Promoción 2x1 en productos seleccionados.','2x1',0,TRUE),
('Precio Gourmet','Descuento promocional en productos seleccionados.','descuento',10,TRUE);

-- =========================================================
-- 18. DATOS: USUARIOS DE PRUEBA
-- Sustituye CAMBIAR_POR_HASH_BCRYPT por hashes generados
-- con password_hash() desde PHP.
-- =========================================================

INSERT INTO usuarios (nombre,email,password,telefono,direccion,rol) VALUES
('Administrador','admin@cupra.test','CAMBIAR_POR_HASH_BCRYPT','600000001','Barcelona','admin'),
('Juan García','juan@cupra.test','CAMBIAR_POR_HASH_BCRYPT','600000002','Barcelona','usuario'),
('María López','maria@cupra.test','CAMBIAR_POR_HASH_BCRYPT','600000003','Barcelona','usuario'),
('Carlos Martín','carlos@cupra.test','CAMBIAR_POR_HASH_BCRYPT','600000004','Barcelona','usuario'),
('Ana Sánchez','ana@cupra.test','CAMBIAR_POR_HASH_BCRYPT','600000005','Barcelona','usuario');

-- =========================================================
-- COMPROBACION: nº de ingredientes por hamburguesa
-- Esperado: Formentor VZ 6, Born 7, León Cup 8, Ateca 6,
-- Tavascan 7, Terramar 7, Raval 7, Tindaya 7, DarkRebel 7,
-- Urban Rebel 6, VZ5 Turbo 9, Cupra Racing 7,
-- Leon Sportstourer 8, Formentor e-Hybrid 6
-- =========================================================

SELECT p.nombre, COUNT(pi.ingrediente_id) AS num_ingredientes
FROM productos p
LEFT JOIN producto_ingrediente pi ON pi.producto_id = p.id
WHERE p.categoria_id = 1
GROUP BY p.id, p.nombre
ORDER BY p.id;
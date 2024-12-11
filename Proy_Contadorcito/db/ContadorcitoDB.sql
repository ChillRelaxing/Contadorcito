CREATE DATABASE ContadorcitoDB;
USE ContadorcitoDB;

-- Tabla para roles
CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    roleName VARCHAR(50) NOT NULL,
    description VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabla para usuarios
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    firstName VARCHAR(100) NOT NULL,
    lastName VARCHAR(100) NOT NULL,
    userName VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone INT NOT NULL,
    password VARCHAR(255) NOT NULL,
    role_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES roles(id)
);

-- Tabla para clientes
CREATE TABLE clients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    firstName VARCHAR(100) NOT NULL,
    lastName VARCHAR(100) NOT NULL,
    address VARCHAR(255),
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(15),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabla para proveedores
CREATE TABLE suppliers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    supplierName VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    phone VARCHAR(15),
    address VARCHAR(255),
    city VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabla para la empresa
CREATE TABLE company (
    id INT AUTO_INCREMENT PRIMARY KEY,
    company_name VARCHAR(100) NOT NULL,
    company_type ENUM('Natural', 'Juridica') NOT NULL,
    address VARCHAR(255) NOT NULL,
    phone VARCHAR(15) NOT NULL,
    email VARCHAR(100) NOT NULL,
    representative VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabla para comprobantes de compras
CREATE TABLE purchase_receipts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    receiptType ENUM('Crédito Fiscal', 'Consumidor Final') NOT NULL,
    purchase_date DATE NOT NULL,
    total DECIMAL(10, 2) NOT NULL,
    pdf_path VARCHAR(255) NOT NULL,
    json_path VARCHAR(255) NOT NULL,
    supplier_id INT NOT NULL,
    user_id INT NOT NULL,
    company_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (supplier_id) REFERENCES suppliers(id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (company_id) REFERENCES company(id)
);

-- Tabla para comprobantes de ventas
CREATE TABLE sales_receipts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    receiptType ENUM('Crédito Fiscal', 'Consumidor Final') NOT NULL,
    sale_date DATE NOT NULL,
    total DECIMAL(10, 2) NOT NULL,
    pdf_path VARCHAR(255) NOT NULL,
    json_path VARCHAR(255) NOT NULL,
    client_id INT NOT NULL,
    user_id INT NOT NULL,
    company_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES clients(id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (company_id) REFERENCES company(id)
);

-- Tabla para reportes
CREATE TABLE reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    report_type ENUM('Ventas', 'Compras') NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    status ENUM('Generado', 'Pendiente', 'Cancelado') NOT NULL DEFAULT 'Pendiente',
    company_id INT NOT NULL,
    user_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES company(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Insert default roles
INSERT INTO roles (roleName, description) VALUES ('Administrador', 'Administrador del sistema');
INSERT INTO roles (roleName, description) VALUES ('Auxiliar de Contabilidad', 'Auxiliar de Contabilidad');

SELECT * FROM roles;

-- Insert default user (admin)
INSERT INTO users (firstName,lastName, userName, email, phone, password, role_id) 
VALUES ('Administrador', 'del sistema','Admin', 'admin@gmail.com', 79009900, MD5('@Admin2024'), 1),
('Auxiliar', 'de contabilidad','Aux', 'aux@gmail.com', 79009900, MD5('Auxi2024'), 2);

SELECT * FROM users;

 INSERT INTO suppliers (supplierName, email, phone, address, city) VALUES
('Distribuidora ABC', 'contacto@abcdistribuidores.com', '555-1234', 'Av. Central 123', 'San Salvador'),
('Suministros Globales', 'ventas@globalsuministros.com', '555-5678', 'Calle Principal 456', 'Santa Ana'),
('Proveedores El Mundo', 'info@proveedoreselmundo.com', '555-9101', 'Boulevard Las Rosas 789', 'San Miguel'),
('Comercializadora Del Valle', 'soporte@comercialdelvalle.com', '555-1122', 'Zona Industrial 101', 'Soyapango'),
('Surtidora General', 'servicio@surtidorageneral.com', '555-1314', 'Colonia La Esperanza 202', 'Usulután');

SELECT * FROM suppliers;

INSERT INTO company (company_name, company_type, address, phone, email, representative)
VALUES
    ('Empresa A', 'Natural', 'Calle Ficticia 123, Ciudad', '123-456-7890', 'empresaA@example.com', 'Juan Perez'),
    ('Compañia XYZ', 'Juridica', 'Avenida Principal 456, Ciudad', '987-654-3210', 'contacto@xyz.com', 'Maria Lopez');

SELECT * FROM company;

INSERT INTO purchase_receipts (receiptType, purchase_date, total, pdf_path, json_path, supplier_id, user_id, company_id)
VALUES
    ('Credito Fiscal', '2024-11-25', 150.75, '/path/to/receipt1.pdf', '/path/to/receipt1.json', 1, 1, 1),
    ('Consumidor Final', '2024-11-26', 200.00, '/path/to/receipt2.pdf', '/path/to/receipt2.json', 2, 2, 2);

SELECT * FROM purchase_receipts;

INSERT INTO sales_receipts (receiptType, sale_date, total, pdf_path, json_path, client_id, user_id, company_id)
VALUES
    ('Crédito Fiscal', '2024-11-25', 150.75, '/path/to/receipt1.pdf', '/path/to/receipt1.json', 1, 1, 1),
    ('Consumidor Final', '2024-11-26', 200.00, '/path/to/receipt2.pdf', '/path/to/receipt2.json', 2, 2, 2);

SELECT * FROM sales_receipts;

INSERT INTO clients (firstName, lastName, address, email, phone) 
VALUES 
    ('Juan', 'Pérez', 'Calle Falsa 123', 'juan.perez@example.com', '5551234567'),
    ('Ana', 'López', 'Avenida Central 456', 'ana.lopez@example.com', '5559876543');

SELECT * FROM clients;

INSERT INTO reports (report_type, start_date, end_date, status, company_id, user_id) 
VALUES 
    ('Ventas', '2024-11-01', '2024-11-30', 'Generado', 1, 1),
    ('Compras', '2024-11-15', '2024-11-20', 'Pendiente', 2, 2);

SELECT * FROM reports;
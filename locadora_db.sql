CREATE DATABASE IF NOT EXISTS locadora
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE locadora;

DROP TABLE IF EXISTS locacoes;
DROP TABLE IF EXISTS veiculos;
DROP TABLE IF EXISTS clientes;
DROP TABLE IF EXISTS usuarios;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(120) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(120) NOT NULL,
    cpf VARCHAR(14) NOT NULL UNIQUE,
    telefone VARCHAR(20),
    email VARCHAR(120),
    endereco VARCHAR(200),
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE veiculos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    placa VARCHAR(10) NOT NULL UNIQUE,
    marca VARCHAR(50) NOT NULL,
    modelo VARCHAR(80) NOT NULL,
    ano INT NOT NULL,
    disponivel TINYINT(1) NOT NULL DEFAULT 1,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE locacoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    veiculo_id INT NOT NULL,
    data_retirada DATE NOT NULL,
    data_devolucao DATE NOT NULL,
    valor DECIMAL(10,2) NOT NULL DEFAULT 0,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id),
    FOREIGN KEY (veiculo_id) REFERENCES veiculos(id)
);

-- Usuário de teste:
-- senha: 123456
INSERT INTO usuarios (nome, email, senha) VALUES
('Administrador', 'admin@locadora.com', '$2y$12$oQqQ35wN4RjPbNF/M7Ir2e1n3moTgZBsuH0/r.b/J71RLa8nnpksS'),
('Carlos Silva', 'carlos@locadora.com', '$2y$12$oQqQ35wN4RjPbNF/M7Ir2e1n3moTgZBsuH0/r.b/J71RLa8nnpksS'),
('Maria Souza', 'maria@locadora.com', '$2y$12$oQqQ35wN4RjPbNF/M7Ir2e1n3moTgZBsuH0/r.b/J71RLa8nnpksS');

INSERT INTO clientes (nome, cpf, telefone, email, endereco) VALUES
('João da Silva', '111.111.111-11', '(81) 99999-1111', 'joao@email.com', 'Rua A, 100'),
('Ana Oliveira', '222.222.222-22', '(81) 99999-2222', 'ana@email.com', 'Rua B, 200'),
('Pedro Santos', '333.333.333-33', '(81) 99999-3333', 'pedro@email.com', 'Rua C, 300');

INSERT INTO veiculos (placa, marca, modelo, ano, disponivel) VALUES
('ABC1D23', 'Fiat', 'Argo', 2023, 1),
('DEF4E56', 'Chevrolet', 'Onix', 2022, 1),
('GHI7F89', 'Toyota', 'Corolla', 2024, 1);

INSERT INTO locacoes (cliente_id, veiculo_id, data_retirada, data_devolucao, valor) VALUES
(1, 1, '2026-10-01', '2026-10-05', 600.00),
(2, 2, '2026-10-03', '2026-10-06', 450.00),
(3, 3, '2026-10-02', '2026-10-04', 700.00);

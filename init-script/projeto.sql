-- Disable triggers and constraints
--SET session_replication_role = replica;

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS mydb;
SET search_path TO mydb;

CREATE DOMAIN numeros AS VARCHAR(15)
    CHECK (VALUE ~ '^\+?[1-9]\d{1,14}$');

CREATE DOMAIN number_cpf AS VARCHAR(11)
    CHECK (VALUE ~ '^[0-9]{11}$');

CREATE DOMAIN number_cnpj AS VARCHAR(14)
    CHECK (VALUE ~ '^[0-9]{14}$');



-- -----------------------------------------------------
-- Table mydb.Gerente
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS Gerente (
  COD_GERENCIA VARCHAR(9) PRIMARY KEY,
  CPF_funcionario number_cpf NOT NULL,
  CONSTRAINT CPF_funcionario_unico UNIQUE (CPF_funcionario)
  --CONSTRAINT fk_cpf_funcionario FOREIGN KEY (CPF_funcionario) REFERENCES Funcionario (CPF)
  --ON DELETE CASCADE ON UPDATE CASCADE
);

-- -----------------------------------------------------
-- Table mydb.Conta
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS Conta (
  NUMERO VARCHAR(16) PRIMARY KEY,
  Status BOOLEAN NOT NULL,
  data_abertura DATE NOT NULL,
  Conjunta BOOLEAN NOT NULL,
  senha VARCHAR(45) NOT NULL,
  COD_GERENCIA VARCHAR(11) NOT NULL,
  saldo DOUBLE PRECISION CHECK (saldo >= 0) DEFAULT 0.0,
  Taxa DOUBLE PRECISION CHECK (Taxa >= 0) DEFAULT 0.0,
  Numero_agencia VARCHAR(16) NOT NULL,
  ID_Cliente INT NOT NULL,
  CONSTRAINT ID_Cliente_unico UNIQUE(ID_Cliente),
  CONSTRAINT fk_Conta_Gerente FOREIGN KEY (COD_GERENCIA) REFERENCES Gerente (COD_GERENCIA)
  ON UPDATE CASCADE
);

-- -----------------------------------------------------
-- Table mydb.Bloqueio
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS Bloqueio (
  Status BOOLEAN NOT NULL,
  Data DATE NOT NULL,
  NUMERO VARCHAR(16) NOT NULL,
  ID_CLIENTE INT NOT NULL,
  COD_GERENCIA VARCHAR(11) DEFAULT NULL,
  CONSTRAINT Data_unico UNIQUE(Data),
  CONSTRAINT fk_Bloqueio_Gerente FOREIGN KEY (COD_GERENCIA) REFERENCES Gerente (COD_GERENCIA)
  ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_Bloqueio_Conta FOREIGN KEY (NUMERO) REFERENCES Conta (NUMERO)
  ON DELETE CASCADE ON UPDATE CASCADE 
);


-- Inserções para a tabela Gerente
INSERT INTO Gerente (COD_GERENCIA, CPF_funcionario) VALUES
('GER001', '33333333333');
INSERT INTO Gerente (COD_GERENCIA, CPF_funcionario) VALUES
('GER002', '99999999999');
INSERT INTO Gerente (COD_GERENCIA, CPF_funcionario) VALUES
('GER003', '40404040404');


-- Inserções para a tabela Conta
INSERT INTO Conta (NUMERO, Status, data_abertura, Conjunta, senha, COD_GERENCIA, saldo, Taxa, Numero_agencia, ID_Cliente) VALUES
('CONTA001', TRUE, '2023-01-01', FALSE, 'senha123', 'GER003', 10500.00, 0.00, 'AG003', 1),
('CONTA002', TRUE, '2023-02-15', FALSE, 'senha234', 'GER001', 2500.00, 0.02, 'AG001', 2),
('CONTA003', TRUE, '2023-03-10', FALSE, 'senha345', 'GER002', 30500.00, 0.03, 'AG002', 3),
('CONTA004', TRUE, '2023-04-20', FALSE, 'senha456', 'GER003', 4500.00, 0.75, 'AG003', 4),
('CONTA005', TRUE, '2023-05-05', FALSE, 'senha567', 'GER001', 55500.00, 0.05, 'AG001', 5),
('CONTA006', TRUE, '2023-06-12', FALSE, 'senha678', 'GER003', 60500.00, 0.95, 'AG003', 6),
('CONTA007', TRUE, '2023-07-30', FALSE, 'senha789', 'GER002', 200.00, 0.25, 'AG002', 7),
('CONTA008', TRUE, '2023-08-25', FALSE, 'senha890', 'GER001', 8500.00, 0.05, 'AG001', 8);
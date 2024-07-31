
USE 5cpa_db;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    sector VARCHAR(50) NOT NULL
);

CREATE TABLE cis (
    id INT AUTO_INCREMENT PRIMARY KEY,
    number VARCHAR(20) NOT NULL UNIQUE,
    description TEXT NOT NULL,
    destination VARCHAR(255) NOT NULL,
    date DATE NOT NULL,
    requester VARCHAR(255) NOT NULL
);

-- Inserindo dados iniciais de usuários (lembre-se de usar hashing de senha)
INSERT INTO users (username, password, sector) VALUES
('user1', '$2y$10$2nleJRCMzzes/chIikpbVuIFliPbHQG.MHXrlUvSllFJgoR/DgdAi', 'P1'),  -- password1
('user2', '$2y$10$0vMYkY07ybp/hCQ/yfFQce08ty09rQJQEMxnVZn/I7M/yFvPM1F2W', 'P2'),  -- password2
('user3', '$2y$10$ZqfPb17h5r0ZtfjBvDkgEuzsqfJSa8.JWblzvlFctj2YBzRYB5h5W', 'P3');  -- password3

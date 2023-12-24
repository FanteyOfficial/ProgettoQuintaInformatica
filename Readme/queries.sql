CREATE DATABASE chat_app_test;

USE chat_app_test;

CREATE TABLE stati (
    id_stato TINYINT PRIMARY KEY,
    descrizione VARCHAR(255) NOT NULL
);

INSERT INTO stati (id_stato, descrizione) VALUES (1, 'Online'), (2, 'Offline'), (3, 'Deactivated');

CREATE TABLE TipoMessaggio (
    id_tipo INT AUTO_INCREMENT PRIMARY KEY,
    descrizione VARCHAR(255) NOT NULL
);

INSERT INTO TipoMessaggio (descrizione) VALUES
    ('Testo'),
    ('Immagine'),
    ('Vocale'),
    ('File');

CREATE TABLE Utenti (
    id_utente INT AUTO_INCREMENT PRIMARY KEY, 
    mail VARCHAR(255) NOT NULL UNIQUE,
    nome VARCHAR(255) NOT NULL,
    cognome VARCHAR(255) NOT NULL,
    username VARCHAR(255) NOT NULL UNIQUE,
    stato TINYINT NOT NULL,
    ultimo_accesso TIMESTAMP NOT NULL,
    password VARCHAR(255) NOT NULL,
    salt VARCHAR(255) NOT NULL,
    remember_me_token VARCHAR(255),
    CONSTRAINT fk_stato FOREIGN KEY (stato) REFERENCES stati(id_stato)
);

CREATE TABLE Chat (
    id_chat INT AUTO_INCREMENT PRIMARY KEY,
    statoChat VARCHAR(255),
    partecipante1 INT,
    partecipante2 INT,
    FOREIGN KEY (partecipante1) REFERENCES Utenti(id_utente),
    FOREIGN KEY (partecipante2) REFERENCES Utenti(id_utente)
);

CREATE TABLE Messaggi (
    id_messaggio INT AUTO_INCREMENT PRIMARY KEY,
    utente_id INT,
    contenuto TEXT NOT NULL,
    ora_invio TIMESTAMP NOT NULL,
    letto TINYINT NOT NULL,
    consegnato TINYINT NOT NULL,
    chat_id INT NOT NULL,
    oraVisualizzazione TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_chat FOREIGN KEY (chat_id) REFERENCES Chat(id_chat),
    CONSTRAINT fk_utente FOREIGN KEY (utente_id) REFERENCES Utenti(id_utente),
    tipo INT,
    CONSTRAINT fk_tipo FOREIGN KEY (tipo) REFERENCES TipoMessaggio(id_tipo)
);



-- fill tables with data
INSERT INTO Utenti (mail, nome, cognome, username, stato, ultimo_accesso, password, salt, remember_me_token) VALUES
    ('user1@example.com', 'John', 'Doe', 'john_doe', 1, CURRENT_TIMESTAMP, 'password_hash_1', 'salt_1', NULL),
    ('user2@example.com', 'Jane', 'Smith', 'jane_smith', 2, CURRENT_TIMESTAMP, 'password_hash_2', 'salt_2', NULL);
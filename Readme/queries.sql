CREATE TABLE Utenti (
    id_utente int AUTO_INCREMENT PRIMARY KEY, 
    mail VARCHAR(255) NOT NULL UNIQUE,
    nome VARCHAR(255) NOT NULL,
    cognome VARCHAR(255) NOT NULL,
    username VARCHAR(255) NOT NULL UNIQUE,
    stato TINYINT NOT NULL,
    ultimo_accesso TIMESTAMP NOT NULL,

    password VARCHAR(255) NOT NULL,
    salt VARCHAR(255) NOT NULL
);

CREATE TABLE Rubrica (
    id_rubrica int AUTO_INCREMENT PRIMARY KEY,
    nomeAssociato VARCHAR(255) NOT NULL,
    utente_id int,
    FOREIGN KEY (utente_id) REFERENCES Utenti(id_utente)
);

CREATE TABLE Chat (
	id_chat int AUTO_INCREMENT PRIMARY KEY,
    statoChat VARCHAR(255) NOT NULL
);

CREATE TABLE Messaggi (
	id_messaggio int AUTO_INCREMENT PRIMARY KEY,
  autore VARCHAR(255) NOT NULL,
  contenuto TEXT NOT NULL,
  ora_invio TIMESTAMP NOT NULL,
  letto TINYINT NOT NULL,
  consegnato TINYINT NOT NULL,
  chat_id int,
  FOREIGN KEY (chat_id) REFERENCES Chat(id_chat),
  tipo TINYINT NOT NULL
);

CREATE TABLE ConversaIn (
	utente_id int,
  chat_id int,
  FOREIGN KEY (utente_id) REFERENCES Utenti(id_utente),
  FOREIGN KEY (chat_id) REFERENCES Chat(id_chat)
);

CREATE TABLE VisualizzatoDa (
	utente_id int,
  messaggio_id int,
  oraVisualizzazione TIMESTAMP,
  FOREIGN KEY (utente_id) REFERENCES Utenti(id_utente),
  FOREIGN KEY (messaggio_id) REFERENCES Messaggi(id_messaggio)
);



CREATE TABLE stati (
    id_stato TINYINT PRIMARY KEY,
    descrizione VARCHAR(255) NOT NULL
);

INSERT INTO stati (id_stato, descrizione) VALUES (1, 'Online'), (2, 'Offline');

ALTER TABLE Utenti
ADD CONSTRAINT fk_stato FOREIGN KEY (stato) REFERENCES stati(id_stato);

ALTER TABLE Utenti
ADD COLUMN remember_me_token VARCHAR(255);

CREATE TABLE TipoMessaggio (
    id_tipo INT AUTO_INCREMENT PRIMARY KEY,
    descrizione VARCHAR(255) NOT NULL
);

INSERT INTO TipoMessaggio (descrizione) VALUES
    ('Testo'),
    ('Immagine'),
    ('Vocale'),
    ('File');

ALTER TABLE Messaggi
MODIFY COLUMN tipo INT;

ALTER TABLE Messaggi
ADD CONSTRAINT fk_tipo FOREIGN KEY (tipo) REFERENCES TipoMessaggio(id_tipo);
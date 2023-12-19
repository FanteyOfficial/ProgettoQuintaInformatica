# ProgettoQuintaInformatica

### Problema: Comunicazione a distanza.

### Descrizione:

#### WebApp di messaggistica istantanea (ispirato a WhatsApp e Telegram).

### Per avviarlo:

- utilizzare xampp avviando i servizi di Apache e MySql.
- eseguire la query in fondo al readme oppure dal file Readme/query.sql

---

## Funzionalità:

- [X] Registrazione e accesso di un account utente (registrazione tramite mail)
- [ ] Aggiunta di un utente nei propri "contatti"
- [ ] Rimozione di un utente nei propri "contatti"
- [X] Modifica dati di un utente
- [X] Eliminazione di un account utente
- [X] Visualizzazione utenti con cui poter interagire
- [ ] Invio messaggi a un altro utente
- [ ] Eliminazione di un messaggio dalla conversazione
- [ ] Eliminazione di una conversazione
- [ ] Modifica di un messaggio inviato
- [ ] Ricerca di uno o più messaggi attraverso una parola chiave
- [ ] Ricerca di un contatto attraverso una parola chiave
- [ ] Visualizzazione dei messaggi inviati e ricevuti dall'utente
- [ ] Recupero password
- [ ] Visualizzazione da parte del mittente se il destinatario ha visualizzato il messaggio
- [ ] Visualizzazione orario di invio del messaggio
- [ ] Visualizzazione stato online o ultimo accesso dell'utente
- [ ] Invio di diverse tipologie di messaggio (testuale, immagine o documento)
- [ ] Rinominazione utente salvato tra i propri "contatti"

---

## Assunzioni

- Si assume che ogni chat è condivisa da almeno 1 e un solo1 utente e 1 e 1 solo contatto che appartenga all'utente.
- Si assume che la mail e la username associata all'account dell'utente siano univoci, ma NON chiavi primarie.
- Si assume che ogni utente possa avere 0 o più chat.
- Si assume che ogni contatto possa avere 1 e 1 sola chat con l'utente.
- Si assume che ogni chat abbia 0 o più messaggi.
- Si assume che ogni messaggio abbia 1 e 1 sola chat.
- Si assume che ogni chat può avere 0 o più utenti.
- Si assume che un utente può avere più contatti.
- Si assume che un contatto può avere 1 e 1 solo utente.
- Si assume che ogni utente può avere 1 e una sola rubrica.
- Si assume che il tag_univoco corrisponda all'id_utente.

---

## ER

![Screenshot](./Readme/ERChatApp.png)

## Schema logico relazionale

Utente (**id_utente**, mail, nome, cognome, username, *stato_id*, ultimoAccesso, password, salt, remember_me_token)

Chat (**id_chat**, statoChat, *utente_id*, *contatto_id*)

Messaggio (**id_messaggio**, contenuto, oraInvio, letto, consegnato, *chat_id*, *tipo_id*, *utente_id*, oraVisualizzazione)

Contatto (**id_contatto**, nomeAssociato, *utente_id*, *utente_contatto_id*)

Stati (**id_stato**, stato)

TipoMessaggio (**id_tipo**, descrizione)

## Schema relazionale

![Screenshot](./Readme/SchemaRelazionale.png)

## MockUp

![Screenshot](./Readme/Slide1.jpg)
![Screenshot](./Readme/Slide2.PNG)
![Screenshot](./Readme/Slide3.PNG)

## Queries per creazione tabelle

```sql
CREATE DATABASE chat_app_test;

CREATE TABLE Utenti (
    id_utente int AUTO_INCREMENT PRIMARY KEY, 
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

CREATE TABLE Contatti (
    id_contatto INT AUTO_INCREMENT PRIMARY KEY,
    nomeAssociato VARCHAR(255) NOT NULL,
    utente_id int,
    utente_contatto_id int,
    FOREIGN KEY (utente_id) REFERENCES Utenti(id_utente),
    FOREIGN KEY (utente_contatto_id) REFERENCES Utenti(id_utente)
);

CREATE TABLE Chat (
	  id_chat int AUTO_INCREMENT PRIMARY KEY,
    statoChat VARCHAR(255)
);

CREATE TABLE Messaggi (
	id_messaggio int AUTO_INCREMENT PRIMARY KEY,
  utente_id VARCHAR(255),
  contenuto TEXT NOT NULL,
  ora_invio TIMESTAMP NOT NULL,
  letto TINYINT NOT NULL,
  consegnato TINYINT NOT NULL,
  chat_id int,
  FOREIGN KEY (chat_id) REFERENCES Chat(id_chat),
  FOREIGN KEY (utente_id) REFERENCES Utenti(id_utente),
  tipo INT,
  CONSTRAINT fk_tipo FOREIGN KEY (tipo) REFERENCES TipoMessaggio(id_tipo)
);

CREATE TABLE ConversaIn (
	utente_id int,
  chat_id int,
  contatto_id int,
  FOREIGN KEY (utente_id) REFERENCES Utenti(id_utente),
  FOREIGN KEY (chat_id) REFERENCES Chat(id_chat),
  FOREIGN KEY (contatto_id) REFERENCES Contatti(id_contatto)
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

CREATE TABLE TipoMessaggio (
    id_tipo INT AUTO_INCREMENT PRIMARY KEY,
    descrizione VARCHAR(255) NOT NULL
);

INSERT INTO TipoMessaggio (descrizione) VALUES
    ('Testo'),
    ('Immagine'),
    ('Vocale'),
    ('File');

```

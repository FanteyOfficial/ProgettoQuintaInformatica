# ProgettoQuintaInformatica

### Problema: Comunicazione a distanza.
### Descrizione:
#### WebApp di messaggistica istantanea (ispirato a WhatsApp e Telegram).
---
## Funzionalità:
- [ ] Registrazione e accesso di un account utente (registrazione tramite mail)
- [ ] Modifica dati di un utente
- [ ] Eliminazione di un account utente
- [ ] Visualizzazione utenti con cui poter interagire (Nome utente + tag univoco)
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
- [ ] Rinominazione utente salvato nella propria "rubrica"
---
## Assunzioni
- Si assume che ogni utente per avere un contatto deve condividere almeno una chat con esso.
- Si assume che ogni chat sia posseduta da 2 o più utenti.
- Si assume che ogni utente possa avere 0 o più chat.
- Si assume che ogni chat abbia 0 o più messaggi.
- Si assume che ogni messaggio abbia 1 e 1 sola chat.
- Si assume che ogni chat può avere 0 o più utenti.
- Si assume che ogni rubrica abbia 1 e un solo utente.
- Si assume che ogni utente può avere 1 e una sola rubrica.
- Si assume che il tag_univoco corrisponda all'id_utente.
---
## ER
![Screenshot](./Readme/ERChatApp.png)
---
## Schema logico relazionale
![Screenshot](./Readme/SchemaLogicoRelazionale.PNG)
---

## Schema relazionale
![Screenshot](./Readme/SchemaRelazionale.png)

## MockUp
![Screenshot](./Readme/Slide1.jpg)
![Screenshot](./Readme/Slide2.PNG)
![Screenshot](./Readme/Slide3.PNG)

## Queries per creazione tabelle
```sql
CREATE TABLE Utenti (
    id_utente int AUTO_INCREMENT PRIMARY KEY, 
    mail VARCHAR(255) NOT NULL UNIQUE,
    nome VARCHAR(255) NOT NULL,
    cognome VARCHAR(255) NOT NULL,
    username VARCHAR(255) NOT NULL UNIQUE,
    stato TINYINT NOT NULL,
    ultimo_accesso DATE NOT NULL
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
```

-- show all chats of a user
SELECT chat.id_chat
FROM chat
INNER JOIN (utenti INNER JOIN contatti ON utenti.id_utente=contatti.utente_id) ON chat.utente_id=utenti.id_utente
WHERE chat.utente_id=? OR chat.utente_contatto_id=?;

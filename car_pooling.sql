--Creazione delle tabelle per il database

CREATE TABLE Autisti(
    id_autista int not null auto_increment,
    nome varchar(50),
    cognome varchar(50),
    dt_nascita date,
    nazionalità varchar(50),
    numero_patente int,
    dt_scadenza_patente date,
    telefono int,
    mail varchar(50),
    fotografia varchar(255),
    primary key(id_autista)
);

CREATE TABLE Auto(
    targa varchar(10) not null,
    modello varchar(100),
    anno int,
    posti int,
    id_autista int,
    tipo varchar(100),
    primary key (targa),
    foreign key (id_autista) references Autisti (id_autista)
);

CREATE TABLE Passeggeri(
    id_passeggero int not null auto_increment,
    nome varchar(50),
    cognome varchar(50),
    documento varchar(255),
    telefono int,
    mail varchar(255),
    primary key (id_passeggero)
);

CREATE TABLE Viaggi(
    id_viaggio int not null auto_increment,
    citta_partenza varchar(100),
    citta_arrivo varchar(100),
    data_partenza date,
    ora_partenza int,
    contributo_passeggero float,
    tempo_percorrenza int,
    id_autista int,
    primary key (id_viaggio),
    foreign key (id_autista) references Autisti(id_autista)
);

CREATE TABLE Prenotazioni(
    codice int not null,
    id_viaggio int,
    id_passeggero int,
    primary key (codice),
    foreign key (id_viaggio) references Viaggi(id_viaggio),
    foreign key (id_passeggero) references  Passeggeri(id_passeggero)
);

--effettuata modifica che facilità la generazione di nuove prenotazioni
ALTER TABLE Prenotazioni
MODIFY COLUMN codice INT NOT NULL AUTO_INCREMENT;

ALTER TABLE Prenotazioni
AUTO_INCREMENT = 1005;


CREATE TABLE Feedback(
    id_feedback int not null auto_increment,
    voto int,
    testo varchar(255),
    id_autista int,
    id_viaggio int,
    id_passeggero int,
    mittente_ruolo ENUM('autista','passeggero'),
    mittente_id INT,
    destinatario_ruolo ENUM('autista','passeggero'),
    destinatario_id INT,
    primary key (id_feedback),
    foreign key (id_autista) references Autisti(id_autista),
    foreign key (id_viaggio) references Viaggi(id_viaggio),
    foreign key (id_passeggero) references  Passeggeri(id_passeggero)
);

INSERT INTO Autisti (id_autista, nome, cognome, dt_nascita, nazionalità, numero_patente, dt_scadenza_patente, telefono, mail, fotografia)
VALUES
(1, 'Mario', 'Rossi', '1980-01-01', 'Italiana', 123456, '2030-01-01', 3331234567, 'mario.rossi@email.it', 'mario.jpg'),
(2, 'Giuseppe', 'Bianchi', '1985-06-12', 'Italiana', 789012, '2031-01-01', 3337654321, 'giuseppe.bianchi@email.it', 'giuseppe.jpg');


INSERT INTO Passeggeri (id_passeggero, nome, cognome, documento, telefono, mail)
VALUES
(1, 'Anna', 'Verdi', 'ID123456', 3339876543, 'anna.verdi@email.it'),
(2, 'Luca', 'Neri', 'ID654321', 3338765432, 'luca.neri@email.it');


INSERT INTO Auto (targa, modello, anno, posti, id_autista, tipo)
VALUES
('AB123CD', 'Fiat Panda', 2020, 4, 1, 'Economica'),
('EF456GH', 'Mercedes Benz', 2022, 4, 2, 'Lusso');


INSERT INTO Viaggi (id_viaggio, citta_partenza, citta_arrivo, data_partenza, ora_partenza, contributo_passeggero, tempo_percorrenza, id_autista)
VALUES
(10, 'Milano', 'Bologna', '2025-05-10', 900, 20.00, 120, 1),
(11, 'Roma', 'Napoli', '2025-05-11', 800, 25.00, 90, 2);

INSERT INTO Prenotazioni (codice, id_viaggio, id_passeggero)
VALUES
(1001, 10, 1),  -- Anna Verdi prenota per Milano-Bologna
(1002, 11, 2);  -- Luca Neri prenota per Roma-Napoli
(1003, 11, 1)

-- Feedback di Anna (passeggero) per Mario (autista)
INSERT INTO Feedback (voto, testo, id_autista, id_viaggio, id_passeggero, mittente_ruolo, mittente_id, destinatario_ruolo, destinatario_id)
VALUES
(5, 'Ottimo viaggio, autista molto professionale.', 1, 10, 1, 'passeggero', 1, 'autista', 1);

-- Feedback di Mario (autista) per Anna (passeggero)
INSERT INTO Feedback (voto, testo, id_autista, id_viaggio, id_passeggero, mittente_ruolo, mittente_id, destinatario_ruolo, destinatario_id)
VALUES
(4, 'Passeggera cordiale e puntuale.', 1, 10, 1, 'autista', 1, 'passeggero', 1);

-- Feedback di Luca (passeggero) per Giuseppe (autista)
INSERT INTO Feedback (voto, testo, id_autista, id_viaggio, id_passeggero, mittente_ruolo, mittente_id, destinatario_ruolo, destinatario_id)
VALUES
(4, 'Viaggio piacevole, ma l’auto non era perfetta.', 2, 11, 2, 'passeggero', 2, 'autista', 2);

-- Feedback di Anna (passeggero) per Mario (autista)
INSERT INTO Feedback (voto, testo, id_autista, id_viaggio, id_passeggero, mittente_ruolo, mittente_id, destinatario_ruolo, destinatario_id)
VALUES
(5, 'Ottimo viaggio, autista molto professionale.', 1, 10, 1, 'passeggero', 1, 'autista', 1);

-- Feedback di Mario (autista) per Anna (passeggero)
INSERT INTO Feedback (voto, testo, id_autista, id_viaggio, id_passeggero, mittente_ruolo, mittente_id, destinatario_ruolo, destinatario_id)
VALUES
(4, 'Passeggera cordiale e puntuale.', 1, 10, 1, 'autista', 1, 'passeggero', 1);

-- Feedback di Luca (passeggero) per Giuseppe (autista)
INSERT INTO Feedback (voto, testo, id_autista, id_viaggio, id_passeggero, mittente_ruolo, mittente_id, destinatario_ruolo, destinatario_id)
VALUES
(4, 'Viaggio piacevole, ma l’auto non era perfetta.', 2, 11, 2, 'passeggero', 2, 'autista', 2);

-- Feedback di Giuseppe (autista) per Luca (passeggero)
INSERT INTO Feedback (voto, testo, id_autista, id_viaggio, id_passeggero, mittente_ruolo, mittente_id, destinatario_ruolo, destinatario_id)
VALUES
(3, 'Passeurre un po\ chiassoso, ma tutto sommato rispettoso.', 2, 11, 2, 'autista', 2, 'passeggero', 2);


--Queries

--a) data una città di partenza, una di arrivo e una data, elencare gli autisti che propongono un
--viaggio corrispondente con prenotazioni non ancora chiuse (che hanno posti disponibili), in ordine crescente di orario,
--riportando i dati dell’auto e il contributo economico richiesto;

select Autisti.nome, Autisti.cognome,Auto.targa,Auto.modello,Viaggi.contributo_passeggero, Auto.posti, count(Prenotazioni.id_passeggero) as n_passeggeri
from Autisti
inner join Auto
using (id_autista)
inner join Viaggi
using (id_autista)
left join Prenotazioni
using (id_viaggio)
where Viaggi.citta_partenza = 'Roma' and Viaggi.citta_arrivo = 'Napoli' and Viaggi.data_partenza = '2025-05-11' 
group by Autisti.id_autista, Auto.posti
having Auto.posti - 1 - n_passeggeri > 0
order by Viaggi.ora_partenza;

--**IMPORTANTE** ricorda che quando usi una variabile di una funzione di aggregazione devi usare 
-- la clausola HAVING che va dopo group by e in group by ci devi mettere anche le variabili che usi in HAVING

--b) dato il codice di una prenotazione accettata, estrarre i dati necessari per predisporre l’email
--di promemoria da inviare all’utente passeggero;

select Auto.modello, Auto.targa, Autisti.nome, Viaggi.ora_partenza, Viaggi.data_partenza, Viaggi.citta_partenza, Viaggi.citta_arrivo
from  Auto
inner join Autisti 
using (id_autista)
inner join Viaggi
using (id_autista)
inner join Prenotazioni
using (id_viaggio)
where Prenotazioni.codice = "1001";

--c) dato un certo viaggio, consentire all’autista di valutare le caratteristiche dei passeggeri
--visualizzando l’elenco di coloro che lo hanno prenotato, con il voto medio dei feedback
--ricevuti da ciascun passeggero, presentando solo i passeggeri che hanno voto medio
--superiore ad un valore indicato dall’autista;

select Passeggeri.nome,Passeggeri.cognome, AVG(Feedback.voto) as voto_medio
from Passeggeri
inner join Prenotazioni
using (id_passeggero)
inner join Viaggi
using (id_viaggio)
inner join Feedback
using (id_autista)
where Viaggi.citta_partenza = "Roma" and Viaggi.citta_arrivo = "Napoli" and Viaggi.data_partenza = "2025-05-11" and Viaggi.ora_partenza = "800"
group by Passeggeri.id_passeggero
having voto_medio > 3;



select Passeggeri.nome, Passeggeri.cognome, Passeggeri.telefono
from Passeggeri;

insert into Prenotazioni (id_viaggio, id_passeggero)
            values ('', '');


INSERT INTO Viaggi (citta_partenza, citta_arrivo, data_partenza, ora_partenza, contributo_passeggero, tempo_percorrenza, id_autista)
VALUES
('Mantova', 'Brescia', '2025-05-12', 800, 25.00, 90, 2);




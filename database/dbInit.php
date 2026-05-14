<?php

$arxiu = __DIR__. '/musics.db';
$db = new SQLite3($arxiu);

$db->exec("CREATE TABLE IF NOT EXISTS musics (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT NOT NULL,
    img TEXT NOT NULL,
    biografia TEXT NOT NULL,
    titol TEXT NOT NULL,
    video TEXT,
)");

$db->exec("INSERT INTO musics (nom, img, biografia, titol, video) VALUES
    ('Ai Furihata', '../assets/img/aifurihata.jpg', 'Biografia de l\'artista 1', 'AXIOM', 'ZTvaHCtTDc0'),
    ('Jamiroquai', '../assets/img/jamiroquai.jpg', 'Biografia de l\'artista 2', 'Little L', '1hHSH9sJUEo'),
    ('Ariana Grande', '../assets/img/arianagrande.png', 'Biografia de l\'artista 3', 'Only 1', 'qukswgSkkzc'),
    ('Rival Sons', '../assets/img/rivalsons.jpg', 'Biografia de l\'artista 4', 'Memphis Sun', 'gkvhLCny76o'),
    ('Green Day', '../assets/img/greenday.jpg', 'Biografia de l\'artista 5', 'Holiday', 'A1OqtIqzScI'),
    ('Ramón Llenas', '../assets/img/ramonllenas.jpg', 'Biografia de l\'artista 6', 'Bad Guy', 'QyLix23Dkig'),
    ('Ado', '../assets/img/ado.png', 'Biografia de l\'artista 7', 'Odo', 'YnSW8ian29w'),
    ('bbno$', '../assets/img/bbnos.png', 'Biografia de l\'artista 8', 'Super Smash Bro\'s', 'Im8cHCP5cVE'),
    ('Good Kid', '../assets/img/goodkid.png', 'Biografia de l\'artista 9', 'Mimi\'s Delivery Service', 'hYUvI5Njbbk'),
    ('Gorillaz', '../assets/img/gorillaz.png', 'Biografia de l\'artista 10', 'On Melancholy Hill', '04mfKJWDSzI'),
    ('Tame Impala', '../assets/img/tameimpala.png', 'Biografia de l\'artista 10', 'Let It Happen', 'pFptt7Cargc')
");
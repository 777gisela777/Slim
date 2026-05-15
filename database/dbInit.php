<?php

$arxiu = __DIR__. '/musics.db';
$db = new SQLite3($arxiu);

$db->exec("CREATE TABLE IF NOT EXISTS musics (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT NOT NULL,
    img TEXT NOT NULL,
    biografia TEXT NOT NULL,
    titol TEXT NOT NULL,
    video TEXT
)");

$db->exec("
    INSERT INTO musics (nom, img, biografia, titol, video) VALUES

    ('Ai Furihata', '../assets/img/aifurihata.jpg', 'Ai Furihata va néixer el 19 de febrer a la prefectura de Nagano, Japó.
    El 2015, va ser escollida per a Love Live! Sunshine!!, fent el seu debut oficial com a actriu de doblatge en el paper de Ruby Kurosawa.
    Com a membre del grup d''idols Aqours, va actuar en un concert de dos dies al Tokyo Dome el 2018.
    El 2019 va publicar el seu primer photobook, Ai Furihata Photobook: Itoshiki.
    El 23 de setembre de 2020 va publicar el seu primer miniàlbum, Moonrise.', 'AXIOM', 'ZTvaHCtTDc0'),

    ('Jamiroquai', '../assets/img/jamiroquai.jpg', 'Quan una banda té gairebé mil milions de visualitzacions a YouTube i més de 1.700 milions d''escoltes a Spotify, queda clar que són una presència musical contemporània de primer ordre.
    Fundat pel cantautor Jay Kay, Jamiroquai va triomfar des del primer moment als anys noranta.
    El març de 2024, la banda va anunciar que estava gravant un nou àlbum.', 'Little L', '1hHSH9sJUEo'),

    ('Ariana Grande', '../assets/img/arianagrande.png', 'La cantant pop guanyadora d''un Grammy Ariana Grande és coneguda per cançons com 7 Rings i Thank U, Next.
    Va començar la seva carrera a Broadway abans de triomfar a Nickelodeon.
    El 2024 va interpretar Glinda a Wicked.', 'Only 1', 'qukswgSkkzc'),

    ('Rival Sons', '../assets/img/rivalsons.jpg', 'Rival Sons toquen rock ''n'' roll en la seva forma més pura.
    Han compartit escenari amb Black Sabbath, AC/DC i Guns N'' Roses.
    El 2023 van publicar DARKFIGHTER i LIGHTBRINGER.', 'Memphis Sun', 'gkvhLCny76o'),

    ('Green Day', '../assets/img/greenday.jpg', 'Green Day és un grup de punk rock americà format el 1987 per Billie Joe Armstrong i Mike Dirnt.
    Han venut més de 85 milions de discos a tot el món i han guanyat cinc premis Grammy.', 'Holiday', 'A1OqtIqzScI'),

    ('Ramón Llenas', '../assets/img/ramonllenas.jpg', 'Ramón Llenas, conegut com Ray L. Falcon, va ser músic i actor de doblatge nascut a Barcelona.
    Va posar música a molts anuncis coneguts dels anys vuitanta i noranta.', 'Els Barrufets', 'fX3ZufZG8B0'),

    ('Ado', '../assets/img/ado.png', 'Ado és una cantant japonesa que va debutar el 2020 amb el senzill digital Usseewa.
    També va participar a la pel·lícula de One Piece del 2022.', 'Odo', 'YnSW8ian29w'),

    ('bbno$', '../assets/img/bbnos.png', 'Alexander Leon Gumuchian, conegut com bbno$, és un raper i cantautor canadenc.
    És conegut per cançons com Lalala i Edamame.', 'Super Smash Bro''s', 'Im8cHCP5cVE'),

    ('Good Kid', '../assets/img/goodkid.png', 'Good Kid és un grup indie rock de Toronto format el 2015.
    Han publicat diversos EPs, incloent Good Kid 4 i Acoustic Kid.', 'Mimi''s Delivery Service', 'hYUvI5Njbbk'),

    ('Gorillaz', '../assets/img/gorillaz.png', 'Gorillaz és una banda virtual britànica creada per Damon Albarn i Jamie Hewlett.
    El grup combina rock alternatiu, hip-hop, electrònica i britpop.', 'On Melancholy Hill', '04mfKJWDSzI'),

    ('Tame Impala', '../assets/img/tameimpala.png', 'Tame Impala és el projecte musical psicodèlic de Kevin Parker.
    El grup és conegut per àlbums com Currents i The Slow Rush.', 'Let It Happen', 'pFptt7Cargc')
");
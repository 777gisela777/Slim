[![Open in Visual Studio Code](https://classroom.github.com/assets/open-in-vscode-2e0aaae1b6195c2367325f4f02e2d04e9abb55f0b24a779b69b11b9e10269abc.svg)](https://classroom.github.com/online_ide?assignment_repo_id=23746111&assignment_repo_type=AssignmentRepo)

# Llista d'artistes

![Status](https://img.shields.io/badge/Status-In%20Development-orange)
![Framework](https://img.shields.io/badge/Framework-Slim%204-61a230)
![Methods](https://img.shields.io/badge/Methods-GET%20%7C%20POST-blue)

Aquest programa és una aplicació web que permet accedir i gestionar un catàleg d'artistes musicals. El sistema connecta amb una base de dades SQLite que conté la informació detallada dels músics, incloent imatges, biografies i vídeos incrustats de YouTube.

---

## Què fa?
És una pàgina web dinàmica que utilitza el framework Slim. Permet cercar artistes, afegir-hi de nous, editar la seva informació o eliminar-los.

---
## Vídeo

![demo](./public/assets/img/Video%20Project.gif)
---

## Possibles respostes del servidor

| Codi | Significat | Descripció |
| :--- | :--- | :--- |
| `200` | **OK** | La pàgina s'ha carregat correctament. |
| `302` | **Found (Redirect)** | Redirecció temporal automàtica després de processar un formulari (Crear, Editar, Eliminar). |
| `404` | **Not Found** | L'artista sol·licitat o la ruta no existeix a la base de dades. |
| `500` | **Internal Server Error** | Error intern del servidor o problema de connexió amb SQLite. |

---

## Rutes i Peticions disponibles

| Mètode | Ruta (Endpoint) | Descripció |
| :--- | :--- | :--- |
| `GET` | `/` | **Pàgina principal**: Mostra el cercador i el llistat de tots els artistes. |
| `GET` | `/music/create` | Mostra un formulari per afegir un nou artista al catàleg. |
| `POST` | `/music` | Processa les dades del formulari de creació i redirigeix a la pàgina amb els detalls del nou artista. |
| `GET` | `/music/{id}` | **Detalls de l'artista**: Mostra el detall d'un músic específic per la seva ID (imatge, biografia i reproductor incrustat del vídeo de YouTube). |
| `GET` | `/music/{id}/edit` | Mostra el formulari web amb les dades actuals de l'artista per poder-les modificar. |
| `POST` | `/music/{id}/edit` | Processa la modificació de les dades de l'artista i redirigeix de nou als seus detalls. |
| `POST` | `/music/{id}/delete` | Elimina l'artista de la base de dades i redirigeix a la pàgina principal. |

---

## Fet per

Projecte: **Laura i Gisela**

Disseny i Estils: **Laura i Claude/Gemini**

Arxius de suport: **David i Rai**
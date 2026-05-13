# 🏛️ Pravoslavni Svetionik

> **Digitalni vodič kroz pravoslavne svetinje Srbije** – Interaktivna web platforma za istraživanje duhovne, istorijske i kulturne baštine.

Ovaj projekat je razvijen kao završni master rad na **Fakultetu tehničkih nauka u Čačku (Univerzitet u Kragujevcu)**. Predstavlja razvoj kompleksne Full-stack web aplikacije koja spaja tradicionalne podatke sa modernim web tehnologijama.

---

### 🤖 Napomena o AI integraciji (Akademski zahtev)
> Implementacija modula veštačke inteligencije u aplikaciju nije korišćena za automatsko generisanje izvornog koda, već je predstavljala **namenski zadatak i ključni zahtev predmetnog profesora** u okviru izrade ovog master rada. 
> 
> Cilj ovog inženjerskog zadatka bio je demonstriranje sposobnosti povezivanja aplikacije sa eksternim AI API servisima unutar *Laravel* arhitekture, obrada ulaznih podataka i asinhrono vraćanje formata (edukativni sadržaji, sažeci, kvizovi) kroz složen interfejs nazvan **AI Vodič**.

---

## ✨ Ključne funkcionalnosti

* 🗺️ **Interaktivna mapa:** Pregled lokacija manastira u realnom vremenu uz korišćenje JS biblioteka i dinamičko učitavanje geografskih koordinata iz baze podataka.
* 🧠 **AI Vodič:** Pametni asistent integrisan u sistem radi obrade teksta, objašnjenja istorijskih pojmova i dinamičkog kreiranja kvizova za proveru znanja.
* 📅 **Pravoslavni kalendar i Algoritmi:** Implementiran algoritam za izračunavanje datuma Vaskrsa i složen relacioni sistem za praćenje praznika i tipika posta.
* 👑 **Genealogija i Vremenska linija:** Vizuelni prikaz porodičnog stabla dinastije Nemanjića i interaktivna hronološka vremenska linija (Timeline).
* 🎓 **Sistem za proveru znanja:** Backend logika za kreiranje, validaciju i bodovanje interaktivnih kvizova iz oblasti istorije i pravoslavlja.

## 🛠️ Tehnologije (Tech Stack)

| Frontend | Backend | Baza Podataka | Ostalo |
| :--- | :--- | :--- | :--- |
| HTML5, CSS3 | PHP 8.x | SQLite | Git & GitHub |
| JavaScript | Laravel Framework | (Preko 20 relacija) | Render Hosting |

## 🚀 Pokretanje aplikacije lokalno

Ukoliko želite da pokrenete projekat na svom računaru, pratite sledeće korake:

**1. Kloniranje repozitorijuma:**
git clone [https://github.com/AnaVukajlovic/ManastiriSrbije.git]
(https://github.com/AnaVukajlovic/ManastiriSrbije.git)

**2. Instalacija, podešavanje baze i pokretanje servera:**
Unesite sledeće komande redom u terminal kako biste podesili celokupno okruženje:

# 3. Instalacija PHP zavisnosti (backend)
composer install

# 4. Instalacija Node.js zavisnosti (frontend)
npm install

# 8. Kreiranje lokalnog konfiguracionog fajla
cp .env.example .env

# 6. Generisanje sigurnosnog ključa aplikacije
php artisan key:generate

# 7. Kreiranje strukture baze podataka i ubacivanje početnih podataka (seed)
php artisan migrate --seed

# 8. Pokretanje lokalnog razvojnog servera
php artisan serve

# Test Projekat

Ovaj projekat je aplikacija za registraciju korisnika. Koristi PHP 8.0+ za backend i MySQL za bazu podataka.

## Zahtevi

Za ovaj projekat potrebno je:

- PHP 8.0 ili noviji
- Composer
- MySQL

## Instalacija

Pratite ove korake kako bi instalirali i pokrenuli ovu aplikaciju na vašem lokalnom sistemu:

1. Klonirajte ovaj repozitorijum na vašem lokalnom sistemu. Možete koristiti komandu `git clone git@github.com:luka995/kp.git`.
2. Preuzmite i instalirajte [Composer](https://getcomposer.org/) ukoliko već nemate instaliran.
3. Pokrenite `composer install` u direktorijumu projekta da bi instalirali sve PHP zavisnosti.
4. U config.php fajlu možete uneti parametre za komunikaciju sa bazom podataka i MaxMind-om.
5. Podrška samo za MySQL (koristi se "mysqli").

## Korišćenje

Ulazni fajl index.php

## Funkcionalnosti

- Registracija korisnika
- Validacija email adrese
- Verifikacija IP adrese korisnika - MaxMind
- Logovanje akcija korisnika
# mvc-report

*Rapport-sida i kursen för MVC.*

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/missivaeak/mvc-report/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/missivaeak/mvc-report/?branch=main)
[![Code Coverage](https://scrutinizer-ci.com/g/missivaeak/mvc-report/badges/coverage.png?b=main)](https://scrutinizer-ci.com/g/missivaeak/mvc-report/?branch=main)
[![Build Status](https://scrutinizer-ci.com/g/missivaeak/mvc-report/badges/build.png?b=main)](https://scrutinizer-ci.com/g/missivaeak/mvc-report/build-status/main)

## Bygg

För att bygga appen på din enhet kan du följa dessa steg.

```
git clone git@github.com:missivaeak/mvc-report.git
cd mvc-report/
composer install --optimize-autoloader
php bin/console doctrine:migration:migrate
npm install
npm run build
```

Om du får fel med composer så kan du behöva ställa in följande global `APP_ENV=dev`.

Om du får fel som lyder *SQLSTATE[HY000]: General error: 8 attempt to write a readonly database* så kan du behöva korrigera rättigheter för att tillåta att skriva till databasfilen. Hur du bäst korrigerar rättigheter beror på din miljö, men följande kommando `chmod 677 var/data.db` bör fungera på samtliga UNIX-system. OBS! Detta kan vara en säkerhetsrisk, så tänk dig för.


## Använda appen

För att använda appen behövs en http-server som levererar `./public/` utåt. APIer och appen är relativt denna folder.

För att läsa genererad dokumentation behövs också `./docs/`.

## Populera databasen

För att spelet *RoadLike* ska gå att spela behövs innehåll i databasen. Databasen kan populeras på två vis. Det första är att ladda in en förkonfigurerad backupfil, det andra att lägga till hinder och utmanare manuellt.


### Alternativ 1: Förkonfigurerad data

Kör helt enkelt detta kommande för att ladda in backup-filen.

```
php bin/console doctrine:query:sql "$(< assets/data_backup.sql)"
```

### Alternativ 2: Populera manuellt

Använd sidans API för att populera databasen.

#### obstacle

```
GET /api/proj/obstacle
```

Hämta alla obstacles. Obstacle är hinder i spelet som spelarens utmanare behöver ta sig genom.

```
POST /api/proj/obstacle
```

Skapar ett nytt hinder till spelet.

* Parametrar i body
  * name: Namn på hindret
  * description: Beskrivning
  * cost_reward_time: Förändring i tid
  * cost_reward_health: Förändring i hälsa
  * cost_reward_stamina: Förändring i energi
  * cost_reward_int: Förändring i intelligens
  * cost_reward_str: Förändring i styrka
  * cost_reward_dex: Förändring i smidighet
  * cost_reward_lck: Förändring i tur
  * cost_reward_spd: Förändring i hastighet
  * cost_reward_con: Förändring i uthållighet
* Valbara parametrar
  * difficulty_int: Svårighetsfaktor för intelligens
  * difficulty_str: Svårighetsfaktor för styrka
  * difficulty_dex: Svårighetsfaktor för smidighet

```
DELETE /api/proj/obstacle
```

Ta bort ett obstacle.

* id: Obstacle id som det är angivet i GET

#### template

```
GET /api/proj/template
```

Hämtar alla utmanare-templates. En template är en mall för en utmanare och är underlag för när spelet generar utmanare som spelaren kan välja.

```
POST /api/proj/template
```

Skapar en ny template.

* name: Namn på utmanaren

```
DELETE /api/proj/template
```

* id: Template id som det är angivet i GET

## Ytterligare API

Förutom API för att populera databasen finns dessa endpoints att använda.

```
GET /api/proj/leaderboard
```

Hämtar alla spelare på topplistan. Med query `?top10=true` så hämtas de 10 högsta värden i fallande ordning istället.

```
POST /api/proj/leaderboard
```

Lägg till ny spelare på topplistan. Följande parametrar behövs i bodyn:
* player: Spelarens namn
* challenger: Utmanarens namn
* distance: Hur långt spelaren kom

```
DELETE /api/proj/leaderboard
```

Ta bort en spelare ur topplistan.
* id: Spelar-id som det är angivet i GET

```
GET /api/proj/draft
```

Hämtar ett urval av tre slumpmässigt utvalda utmanare.

# mvc-report

*Rapport-sida i kursen för MVC.*

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/missivaeak/mvc-report/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/missivaeak/mvc-report/?branch=main)
[![Code Coverage](https://scrutinizer-ci.com/g/missivaeak/mvc-report/badges/coverage.png?b=main)](https://scrutinizer-ci.com/g/missivaeak/mvc-report/?branch=main)
[![Build Status](https://scrutinizer-ci.com/g/missivaeak/mvc-report/badges/build.png?b=main)](https://scrutinizer-ci.com/g/missivaeak/mvc-report/build-status/main)

## Bygg

För att bygga appen på din enhet kan du följa dessa steg.

```
git clone git@github.com:missivaeak/mvc-report.git
composer install --optimize-autoloader
php bin/console cache:clear`
npm install
```

Om du får fel med composer så kan du behöva ställa in följande global `APP_ENV=dev`

## Populera databasen

För att spelet *Roadlike* ska gå att spela behövs innehåll i databasen. Databasen kan populeras på två vis. Det första är att ladda in en förkonfigurerad backupfil, det andra att lägga till hinder och utmanare manuellt.

### Alternativ 1: Förkonfigurerad data

Kör helt enkelt detta kommande för att ladda in backup-filen.

```
php bin/console doctrine:query:sql "$(< assets/data_backup.sql)"
```

### Alternativ 2: Populera manuellt

Använd sidans API för att populera databasen.

#### obstacle

Obstacle är hinder i spelet.

```
GET /api/proj/obstacle
```

Hämta alla hinder.

```
POST /api/proj/obstacle
```

Följade parametrar behövs i bodyn
> name: Namn på hindred
> description: Beskrivning
> cost_reward_time: Förändring i tid
> cost_reward_health: Förändring i hälsa
> cost_reward_stamina: Förändring i energi
> cost_reward_int: Förändring i intelligens
> cost_reward_str: Förändring i styrka
> cost_reward_dex: Förändring i smidighet
> cost_reward_lck: Förändring i tur
> cost_reward_spd: Förändring i hastighet
> cost_reward_con: Förändring i uthållighet

Följade parametrar är valbara
> difficulty_int: Svårighetsfaktor för intelligens
> difficulty_str: Svårighetsfaktor för styrka
> difficulty_dex: Svårighetsfaktor för smidighet

aoeaoe

`DELETE /api/proj/obstacle`

`GET /api/proj/template`
`POST /api/proj/template`
`DELETE /api/proj/template`

`GET /api/proj/leaderboard`
`POST /api/proj/leaderboard`
`DELETE /api/proj/leaderboard`

`GET /api/proj/draft`

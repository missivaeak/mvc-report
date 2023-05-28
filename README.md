# mvc-report

*Rapport-sida i kursen för MVC.*

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/missivaeak/mvc-report/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/missivaeak/mvc-report/?branch=main)
[![Code Coverage](https://scrutinizer-ci.com/g/missivaeak/mvc-report/badges/coverage.png?b=main)](https://scrutinizer-ci.com/g/missivaeak/mvc-report/?branch=main)
[![Build Status](https://scrutinizer-ci.com/g/missivaeak/mvc-report/badges/build.png?b=main)](https://scrutinizer-ci.com/g/missivaeak/mvc-report/build-status/main)

## Installera

För att installera repot på din enhet kan du följa dessa steg.

```
git clone git@github.com:missivaeak/mvc-report.git
composer install --optimize-autoloader
php bin/console cache:clear`
npm install
```

Om du får fel med composer så kan du behöva ställa in följande global `APP_ENV=dev`

```
php bin/console doctrine:query:sql "$(< assets/data_backup.sql)"
```

Välj en utmanare och bestäm vilken väg du ska ta i varje vägskäl. Nä

`GET /api/proj/obstacle`
`POST /api/proj/obstacle`
`DELETE /api/proj/obstacle`

`GET /api/proj/template`
`POST /api/proj/template`
`DELETE /api/proj/template`

`GET /api/proj/leaderboard`
`POST /api/proj/leaderboard`
`DELETE /api/proj/leaderboard`

`GET /api/proj/draft`
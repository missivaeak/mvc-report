[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/missivaeak/mvc-report/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/missivaeak/mvc-report/?branch=main)
[![Code Coverage](https://scrutinizer-ci.com/g/missivaeak/mvc-report/badges/coverage.png?b=main)](https://scrutinizer-ci.com/g/missivaeak/mvc-report/?branch=main)
[![Build Status](https://scrutinizer-ci.com/g/missivaeak/mvc-report/badges/build.png?b=main)](https://scrutinizer-ci.com/g/missivaeak/mvc-report/build-status/main)

#git clone git@github.com:missivaeak/mvc-report.git
(APP_ENV=dev)
#composer install --optimize-autoloader
#php bin/console cache:clear
#npm install
#php bin/console doctrine:query:sql "$(< assets/data_backup.sql)"
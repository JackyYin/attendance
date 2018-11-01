#!/bin/bash
exec php /var/www/html/artisan queue:work --sleep=3 --tries=3

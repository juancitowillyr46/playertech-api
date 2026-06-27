php bin/console doctrine:migrations:diff
php bin/console doctrine:mapping:info
php bin/console doctrine:migrations:sync-metadata-storage
php bin/console doctrine:migrations:version --add --all
php bin/console doctrine:schema:update --dump-sql
php bin/console doctrine:schema:validate
php bin/console debug:event-dispatcher kernel.exception
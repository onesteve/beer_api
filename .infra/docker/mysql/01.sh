echo "** Creating test DB"

mysql -u root -p$MYSQL_ROOT_PASSWORD --execute \
"CREATE DATABASE IF NOT EXISTS $MYSQL_DATABASE_TEST;
GRANT ALL ON $MYSQL_DATABASE_TEST.* TO '$MYSQL_USER'@'%';"

echo "** Finished creating test DB"

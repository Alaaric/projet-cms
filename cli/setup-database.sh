#!/bin/bash

if [ -f .env ]; then
    export $(grep -v '^#' .env | xargs)
else
    echo "Error : Unable to reach .env file"
    exit 1
fi

if [[ -z "$DB_HOST" || -z "$DB_NAME" || -z "$DB_USER" ]]; then
    echo "Error : Evironnement variables not find in .env"
    exit 1
fi

echo "Database creation in progress..."
mysql -h "$DB_HOST" -u "$DB_USER" -p"$DB_PASSWORD" < database/database.sql

if [ $? -eq 0 ]; then
    echo "✅ Success! Database created."
else
    echo "❌ Error : Database creation failed."
fi

## REST API without Frameworks medical appointments

Simple REST API where a patient can make a medical appointment and a doctor can confirm.

## Steps to install
### Without Docker

1. Prerequisities

- Clone project and enter to folder:
```
git clone https://github.com/themey99/api-medical-appointments.git && cd api-medical-appointments
```
2. Install dependencies:
```
composer install
```
3. Copy or Create .env:
```
cp .env.example .env
```
4. Set your database config
```
DB_HOST=
DB_PORT=
DB_DATABASE=
DB_USER=
DB_PASSWORD=
```
5. Create your databse and import the SQL file
6. Start server:
```
php -S localhost:8000 -t public/
```
7. Test your API in Postman

### With Docker

1. Prerequisities

- Install [docker](https://docs.docker.com/get-started/) in your workstation
- Install [docker-compose](https://docs.docker.com/compose/install/) in your workstation
- Clone project and enter to folder:
```
git clone https://github.com/themey99/api-medical-appointments.git && cd api-medical-appointments
```
2. Build image:
```
docker-compose build
```
3. Start containers:
```
docker-compose up -d
```
4. Install dependencies:
```
docker-compose exec -u "$(id -u):$(id -g)" app composer install
```
5. Copy or Create .env:
```
cp .env.example .env
```
6. Set your database config
```
DB_HOST=
DB_PORT=
DB_DATABASE=
DB_USER=
DB_PASSWORD=
```
8. Open in browser [adminer](http://localhost:9080)
7. Create your databse and import the SQL file
9. Test your API in Postman
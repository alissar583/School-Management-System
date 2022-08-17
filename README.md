# School management system app
## Introduction

School management system with an examination system ,
 online class and possibility of upload the curriculum ,
 a multi-authority staff and all organizational matters that facilitate and organize 
 the work of the director (School Evacuation - Timetable).
<hr> 

### Here is a list of the packages installed:
- [laravel passport](https://laravel.com/docs/9.x/passport).

# Getting started
### Installation
<hr> 


- Clone this repository.
```
git clone https://github.com/alissar583/School-Management-System.git
```

- copy this command to terminal for install the composer.
```
composer install
```
- copy this command for generate <code>.env</code> file .
```
cp .env.example .env 
```
### Don't forget to create a database with the same name in <code>.env</code> file
- run this commands .
``` 
php artisan migrate
php artisan passport:install
php artisan storage:link
```
- Generate a new application key.
```
php artisan key:generate
```
- Start the local server.
```
php artisan serve 
```
#### You can download the front end side (flutter) from here : dashboard [school_management_system_dashboard](https://github.com/Bisher01/SMS_dashboard.git)
##                                                             mobile [school_management_system_mobile](https://github.com/Bisher01/SMS_mobile.git)    
<hr>

## Now You Can Use This App 



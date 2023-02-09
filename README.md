ManualTools
========================

The "ManualTools" is a test project from [Wilke][1]

You can also learn about this project [in the description project][2].

Requirements
------------

* PHP 8.1.0 or higher;
* Composer 2.5 or higher;
* MariaDB 10.4.24-MariaDB or others
* and the [usual Laravel application requirements][3].

> **Warning**
> 
> make sure you have the php gd extension enabled or else the installation will fail
> 
> [Setup and documentation][4] of gd extension

Installation
------------

Download Zip or using git, you can get project:

Clone the repository
```bash
   git clone https://github.com/falcon9r/manual.git
```

Switch to the repo folder
```bash
   cd manual
```

Install all the dependencies using composer
```bash
   composer install
```

Copy the example env file and make the required configuration changes in the .env file
```bash
   cp .env.example .env
```

Generate a new application key
```bash
   php artisan key:generate
```

Update the dependencies using composer
```bash
   composer update
```

Run the database migrations (Set the database connection in .env before migrating)
for more [docs][6]
```bash
  php artisan migrate --seed
```
Run the command to download the CKFinder code.
After installing the Laravel package you need to download CKFinder code. 
It is not shipped with the package due to different license terms. To install it, run the following artisan command:
[docs](https://github.com/ckfinder/ckfinder-laravel-package)
```bash
    php artisan ckfinder:download
```


Start the local development server
```bash
    php artisan serve
```


To create the symbolic link, you may use
```bash
    php artisan storage:link
```

You can now access the server at http://localhost:8000
> **Warning**
> 
> if you use other domain you must change value in .env (APP_URL)

# Code overview

## Dependencies

- [ckfinder-laravel-package](https://github.com/ckfinder/ckfinder-laravel-package) - Upload images and manage files
- [ckeditor5](https://ckeditor.com/ckeditor-5/) - CKEditor 5 provides every type of WYSIWYG editing solution imaginable. From editors similar to Google Docs and Medium, to Slack or Twitter-like applications, all is possible within a single editing framework.

## Folders

- `app` - Contains all the Eloquent models
- `app/Http/Middleware/CustomCKFinderAuth.php` -  Middleware for CKFinder 
- `config` - Contains all the application configuration files
- `database/factories` - Contains the model factory for all the models
- `database/migrations` - Contains all the database migrations
- `database/seeders` - Contains the database seeder
- `routes/web.php` - Contains all the api routes defined

## Environment variables

- `.env` - Environment variables can be set in this file

***Note*** : You can quickly set the database information and other variables in this file and have the application fully working.

[1]: https://www.wilke.de/
[2]: https://github.com/falcon9r/manual
[3]: https://laravel.com/docs/9.x
[4]: https://www.php.net/manual/en/book.image.php
[5]: https://
[6]: https://laravel.com/docs/9.x/migrations

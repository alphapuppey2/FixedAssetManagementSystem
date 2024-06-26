# FixedAssetManagementSystem
for Capstone 

**Before you start**

# **Installation** 

before you start Coding Laravel, make sure that your local machine has PHP and Composer installed. In addition, we recommend installing Node and NPM.
## Link
[PHP](https://www.php.net/)

[composer](https://getcomposer.org/)

[node](https://nodejs.org/en)


# Run commands #

# Git Clone
git Clone https://github.com/alphapuppey2/FixedAssetManagementSystem.git

Installing the dependencies	

    composer install
    npm install

# Create a Environment for DB Connection
create new file name .env then Copy .env.examples to env then run this Command
```bash
php artisan key:generate
```

**Migrate from the Database**

- Before this Make sure to check the DB connection found in .env
- if .env is not found in the project-app create .env and copy paste from .env.example to .env 

```bash
php artisan migrate
```
Run Application
```bash
php artisan serve
```
Run vite to watch for any update from the Frontend
  ```bash
npm run dev
```

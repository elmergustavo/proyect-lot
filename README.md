# Local Development
```
APP_ENV=local
APP_KEY=base64:OoT644UiWBNqnkCzvP6Po29To+2CcY/p2VIQ7Po4ERw=
APP_DEBUG=true
APP_URL=https://localhost:6443
ONBOARDING_USERNAME=billing@cari.net
ONBOARDING_LICENSE=
```

In new PLEX app in composer.json change
```
{
    "type": "path",
    "url": "/package",
    "options": {
        "symlink": true
    }
}
```
for
```
{
    "type": "vcs",
    "url": "https://dev.azure.com/caricorp/PLEX/_git/package"
},
```
- Delete composer.lock file before building
- Cleanup lines for plex/package in docker compose
- Delete rr file in root directory
- After logging into the container run composer install to create the .lock file

### Roles & permissions
You can modify the *roles_app.json* file with custom app roles and they will be merged with app.roles in ShieldSeed.php

Make sure files under `infrastructure/scripts` are LF not CRLF
- Run `docker compose up -d` 
--- If you're having issues with new packages, etc possibly deleting `node_modules` - `vendor` docker volumes and `docker compose build --no-cache` will fix it
- Container `docker exec -it plex /bin/bash`

Steps to Migrate and Fill your database and Tenants:
- Migrate and seed `php artisan migrate && php artisan db:seed` this is for the main database.
- Before running your test seeder you will need to run the seeders for Holidays and Policies otherwise it will fail and throw an exception
  To run the tenants Holidays and Policies seeder you can use the command: `php artisan tenants:run db:seed --option="class=DatabaseTenantSeeder" --tenants= tenant_id ` 
  where 'tenant_id' is the id of your entity where the seeder will run, this is optional without it the seeder will run for all the entities.
- To run the Test Seeder you can use the next command: `php artisan tenants:run db:seed --option="class=TestSeeder" --tenants= tenant_id ` where 'tenant_id' is 
  the id of your entity where the seeder will run and fill the tables.
- Connect to database `127.0.0.1` port `33306` user `root` password `password`
- https://localhost:6443
- Make sure package has beeen installed `php artisan plex:installforce`

Asset bundling with Vite
- `npm run dev`
- Visit https://localhost:5173 to accept the certificate warning

Onboarding Filament Plugin
- `composer config http-basic.filament-onboarding-manager-pro.composer.sh ${ONBOARDING_USERNAME} ${ONBOARDING_LICENSE}`
- `composer require ralphjsmit/laravel-filament-onboard`

Azure Pipeline requires the following variables
- `REGISTRY_CONNECTION=k8s connection id`
- `IMAGE_REPOSITORY=plex/NEW-APP-NAME`

# Console commands Database tenants
### Migrate (tenant-aware)
The command migrates databases of your tenants. `tenants:migrate`

```bash
php artisan tenants:migrate --tenants=tenant_id
```
### Rollback & seed (tenant-aware)
- Rollback: `tenants:rollback`
- Seed: `tenants:seed`

```bash
php artisan tenants:rollback --tenants=tenant_id
```

### Migrate fresh (tenant-aware)
You may use it like this:
```bash
php artisan tenants:migrate-fresh --tenants=tenant_id
```

### List
The command lists all `existing tenants.tenants:list`
```bash
php artisan tenants:list
```

response
```bash
Listing all tenants.
[Tenant] id: dbe0b330-1a6e-11e9-b4c3-354da4b4f339
[Tenant] id: 49670df0-1a87-11e9-b7ba-cf5353777957
```

[Documentation tenancy for laravel](https://tenancyforlaravel.com/docs/v3/console-commands)


# Production
- Change database prefix to APP name in `PLEX_TENANT_DB_PREFIX` has to be unique between apps
- Change favicon at `filament.php` or customize the app layout to set several icons
- Additional ENV
-- SMTP - SNS?
-- Azure Storage Key?
-- `APP_NAME`


# WSL2 WIN10 issues
- Not being able to start/restart Docker or WSL: https://stackoverflow.com/questions/70567543/cant-restart-wsl2-lxssmanager-hangs-in-stopping-state-how-to-restart/70567544#70567544

- New Docker updates, if have WSL2 installed but you are not able to run docker from the Visual Code plugin you should probably go to: 
    Docker Settings -> Resources -> WSLIntegration check the 'Enable integration with my default WSL distro' option and in the 'Enable integration with additional distros'
    select your linux distro to use. (you will probably need to restart your device)
- If you are using a WLS1 distro and you want to upgrade to a WSL2 distro: You need at least 1903 version of Windows 10, in the cmd or powershell of windows (with admin rights)
    run the following command 'wsl --set-version "distro-name" 2' (where "distro name is the name of your linux distribution") it will take some time to make the upgrade but
    once it finish you can check your WSL version with the following command 'wsl -l -v'


# Usefule tinker commands
- Send mail `Mail::raw('Hello World!', function($msg) {$msg->to('myemail@gmail.com')->subject('Test Email'); });`
- Send database notifications `Filament\Notifications\Notification::make()->title('db notification test')->sendToDatabase(Entity::first())`
- Send notification to all users
```
User::get()->each(function($user) {
    Filament\Notifications\Notification::make()->title('db notification test')->sendToDatabase($user);
    event(new Filament\Notifications\Events\DatabaseNotificationsSent($user));
});
```
- Activate a tenant in tinker
`$tenant = Entity::find('d6162ca7-b6fc-4411-86f0-d879153a34c8'); tenancy()->initialize($tenant); setPermissionsTeamId($tenant->id);`

# Common issues
`no driver found to handle vcs repository`
- Make sure your PAT hasn't expired
- Delete composer.lock
- Delete auth.json -> `docker compose build`
`Unable to locate file in Vite manifest: resources/css/package.css.`
- run `npm run dev`

`if you have issues with permissions on your local environment, you can run the next commands where "USER NAME" is the name of your user in the System`
```
    'sudo chown -R "USER NAME" ~/git/*'
    'chmod -R 775 ./storage/'
    'chmod -R 777 ./storage/'
```
`if you are experiencing permission issues with git/sourcetree specifically the "error: cannot open .git/fetch_head: permission denied" when fetching or pulling: `
`this means that git is traying to access a directory with a user that lacks the -w permission, you can fix this running one of the following commands for the nomiplex folder in the directory`
```
    'chmod -R 775 nomiplex'  'The user and groups can read, write and execute the file. Others can read and execute but cannot write.'
    'chmod -R 777 nomiplex'  'Owner, group and everyone has all the rights to read, write and execute, this is the recommended command to run if the first command doesn't work'
```
`If you are having issues with the data base tenant access, you need to REPLACE the 'autoload section' on the composer.json with the following code`
```
    "autoload": {
        "psr-4": {
            "App\\": "app/",
            "Database\\Factories\\": "database/factories/",
            "Database\\Factories\\Tenant\\": "database/factories/tenant/",
            "Database\\Seeders\\": "database/seeders/",
            "Database\\Seeders\\Tenant\\": "database/seeders/tenant"
        }
    },
```

# TODO
- Database notifications should send an update to the frontend
- Search functionality
- Hide Onboarding widget for certain users
- Improve "Usuarios" page to be more friendly when empty
- User invitation email

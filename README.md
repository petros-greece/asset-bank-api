composer create-project --prefer-dist laravel/lumen blog
cd blog
composer require flipbox/lumen-generator

Inside your bootstrap/app.php file, add:

$app->register(Flipbox\LumenGenerator\LumenGeneratorServiceProvider::class);


php artisan make:model Categories -fmc
php artisan make:model Tree -fmc

php artisan key:generate

/// **RUN****************** //////////////////////////////////////

cd htdocs
cd asset-bank
cd asset-bank-api
php -S localhost:8000 -t public

/// DB CONFIGURATION //////////////////////////////////////

configure DB .env

database/migrations

$table->string('title');
$table->text('icon');

php artisan migrate
php artisan migrate:rollback --step=1
php artisan make:migration create_some_table

/// ADD DUMMY DATA //////////////////////////////////////

database/factories

'title' => $this->faker->sentence,
'icon'  =>$this->faker->paragraph

Models/model

use HasFactory

php artisan tinker

>>> App\Models\User::factory()->count(10)->create()

/// ROUTER  //////////////////////////////////////

routes

$router->group(['prefix' => 'api'], function() use($router){
	$router->get('/categories', 'CategoriesController@index');
});

app/Http/categoriesController

use App\Models\Categories

public function index(){
	return Categories::all();
}

bootstrap/app

$app->withEloquent();

/// VIRTUAL HOST  //////////////////////////////////////


https://stackoverflow.com/questions/27268205/how-to-create-virtual-host-on-xampp


/// FIX CORS  //////////////////////////////////////

https://www.codementor.io/@chiemelachinedum/steps-to-enable-cors-on-a-lumen-api-backend-e5a0s1ecx

/// JWT  //////////////////////////////////////

https://jwt-auth.readthedocs.io/en/develop/lumen-installation/

composer require tymon/jwt-auth:dev-develop


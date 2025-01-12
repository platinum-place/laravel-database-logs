### Configuración de Logging Personalizado

Para agregar un handler personalizado en el archivo de configuración de logging en Laravel, sigue estos pasos:

1. Abre el archivo `config/logging.php`.

2. Agrega la siguiente configuración en el array de canales (`channels`):

```php
'database' => [
    'driver' => 'monolog',
    'handler' => App\Logging\DatabaseLogger::class,
],
```

### Creación de la tabla de logs

Para registrar los logs en la base de datos, puedes crear una tabla utilizando una migración. A continuación, se muestra el código necesario:

1. Crea una nueva migración ejecutando el comando:

```bash
php artisan make:migration create_logs_table
```

2. Abre el archivo de migración generado en el directorio `database/migrations` y actualiza el método `up` con el siguiente código:

```php
Schema::create('logs', function (Blueprint $table) {
    $table->id();
    $table->text('message');
    $table->string('level_name');
    $table->json('context')->nullable();
    $table->string('loggable_id')->nullable();
    $table->string('loggable_type')->nullable();
    $table->timestamps();
    $table->softDeletes();
});
```

3. Aplica la migración para crear la tabla ejecutando el comando:

```bash
php artisan migrate
```

### Uso de relaciones morfológicas para logs

Para implementar un sistema de logs reutilizable en cualquier modelo de tu aplicación, puedes usar relaciones morfológicas en Laravel.

1. **Definición en el modelo `Log`:**

El modelo `Log` contiene la relación morfológica `loggable`, definida de la siguiente manera:

```php
public function loggable(): \Illuminate\Database\Eloquent\Relations\MorphTo
{
    return $this->morphTo();
}
```

Esto permite que cada entrada en la tabla de logs esté asociada a cualquier modelo que implemente esta relación morfológica.

2. **Definición en los modelos que usarán logs:**

En cualquier modelo donde quieras registrar logs, simplemente agrega la siguiente relación (contenida en el trati HasLogs):

```php
public function logs(): \Illuminate\Database\Eloquent\Relations\MorphMany
{
    return $this->morphMany(Log::class, 'loggable');
}
```

3. **Por qué funciona:**

- Laravel usa relaciones morfológicas para permitir que un modelo tenga relaciones polimórficas con múltiples tipos de modelos.
- La función `morphTo` en el modelo `Log` conecta automáticamente los campos `loggable_id` y `loggable_type` con el modelo correspondiente.
- La función `morphMany` en los otros modelos permite acceder a todos los registros de logs relacionados con ese modelo.

4. **Ejemplo de uso:**

Si tienes un modelo `User` y deseas registrar logs relacionados con un usuario:

```php
$user = User::find(1);
Log::channel('database')->error('Mensaje', [$user, /** Cualquier estructura array[] */)]);
```

Tamiben es posible utilizar los logs en general, sin necesidad que tener algun modelo

```php
$user = User::find(1);
Log::channel('database')->error('Mensaje', ['user_id' => 1)]);
```

Con este enfoque, puedes reutilizar el sistema de logs en cualquier modelo de tu aplicación con facilidad.

5. **Recomendaciones:**

 Al porder guardar cualquier estructura como contexto, simplemente adapte el array segun la necesidad que requiera (Como guardar un cierto array y volver a leerlo con una clase casteada).
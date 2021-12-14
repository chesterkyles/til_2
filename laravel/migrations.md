# Database: Migrations

## Reference

<https://laravel.com/docs/8.x/migrations>

## Indexes

### Foreign Key Constraints

Laravel also provides support for creating foreign key constraints, which are used to force referential integrity at the database level.

```php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

Schema::table('posts', function (Blueprint $table) {
    $table->unsignedBigInteger('user_id');

    $table->foreign('user_id')->references('id')->on('users');
});
```

A shorter version of the above code can be written like:

```php
Schema::table('posts', function (Blueprint $table) {
    $table->foreignId('user_id')->constrained();
});
```

The `foreignId` method creates an `UNSIGNED BIGINT` equivalent column, while the `constrained` method will use conventions to determine the table and column name being referenced. If your table name does not match Laravel's conventions, you may specify the table name by passing it as an argument to the `constrained` method:

```php
Schema::table('posts', function (Blueprint $table) {
    $table->foreignId('user_id')->constrained('users');
});
```

You may also specify the desired action for the "on delete" and "on update" properties of the constraint:

```php
$table->foreignId('user_id')
      ->constrained()
      ->onUpdate('cascade')
      ->onDelete('cascade');
```

You can use `set null` instead of `cascade` for `onDelete` method. This will `set null` to tables instead of deleting the entire row on cascade:

```php
$table->foreignId('user_id')
      ->constrained()
      ->onDelete('set null');
```

Any additional column modifiers must be called before the `constrained` method:

```php
$table->foreignId('user_id')
      ->nullable()
      ->constrained();
```
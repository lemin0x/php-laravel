# Laravel Simple User CRUD Application

A minimalist, educational Laravel application demonstrating core web development concepts including user authentication, authorization, and a complete blog post CRUD (Create, Read, Update, Delete) system.

## Table of Contents

- [Quick Start](#quick-start)
- [Project Structure](#project-structure)
- [Core Concepts](#core-concepts)
- [Database & Models](#database--models)
- [User Features](#user-features)
- [Blog Post CRUD](#blog-post-crud)
- [Model Relationships](#model-relationships)
- [Key Commands](#key-commands)

## Quick Start

### Installation & Setup

```bash
# Install dependencies
composer install
npm install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Update .env with your database credentials
# DB_DATABASE=your_database
# DB_USERNAME=your_username
# DB_PASSWORD=your_password

# Run migrations
php artisan migrate

# Start development server
php artisan serve
```

The application will be available at `http://localhost:8000`

## Project Structure

```
app/
├── Http/Controllers/        # Application logic
├── Models/                  # Database models
└── Providers/
config/                      # Configuration files
database/
├── migrations/              # Database schemas
└── seeders/                 # Sample data
resources/
├── css/                     # Stylesheets
├── js/                      # JavaScript
└── views/                   # Blade templates
routes/
└── web.php                  # Route definitions
```

## Core Concepts

### Routing and Controllers

**Routes** (`routes/web.php`)
- Defines URL patterns and maps them to controller methods
- Example: `Route::post('/posts', [PostController::class, 'store'])`

**Controllers** (`app/Http/Controllers/`)
- Organize business logic separate from routes
- Create: `php artisan make:controller PostController`

### Blade Templates

Laravel's templating engine with files ending in `.blade.php`

| Directive | Purpose |
|-----------|---------|
| `{{ $variable }}` | Output data (escaped) |
| `{!! $html !!}` | Output raw HTML (unescaped) |
| `@csrf` | CSRF token protection (required in forms) |
| `@auth ... @endauth` | Conditional content for authenticated users |
| `@foreach ($items as $item) ... @endforeach` | Loop through collections |
| `@method('PUT')` | Spoof HTTP method in forms |

## Database & Models

### Configuration & Migrations

1. **Environment Setup** (`.env`)
   - `DB_DATABASE`: Database name
   - `DB_USERNAME`: Database user
   - `DB_PASSWORD`: Database password

2. **Migrations** - Automated database schema management
   ```bash
   php artisan make:migration create_posts_table
   php artisan migrate
   ```
   - Foreign keys: `$table->foreignId('user_id')->constrained()`
   - Rollback: `php artisan migrate:rollback`

### Models

Models act as an abstraction layer between PHP code and the database.

```bash
php artisan make:model Post
```

**Mass Assignment** - Define fillable fields in the model:
```php
protected $fillable = ['title', 'body', 'user_id'];
```

## User Features

### Registration & Authentication

**Validation**
```php
$request->validate([
    'email' => 'required|email|unique:users,email',
    'password' => 'required|min:8|confirmed',
    'name' => 'required|min:3'
]);
```

**Password Hashing**
```php
'password' => bcrypt($request->password)
```

**Authentication Methods**
```php
auth()->login($user);                    // Log in user
auth()->attempt(['email' => $email, 'password' => $password]); // Verify credentials
auth()->logout();                        // Log out user
auth()->user();                          // Get authenticated user
auth()->id();                            // Get user ID
```

## Blog Post CRUD

### Create
```php
$validated = $request->validate([...]);
$validated['user_id'] = auth()->id();
Post::create($validated);
```

### Read
```php
Post::all();                                    // All posts
Post::where('user_id', auth()->id())->get();   // User's posts
Post::find($id);                               // Single post
```

### Update
```php
// In Blade form: @method('PUT')
$post->update($request->validated());
```

### Delete
```php
// In Blade form: @method('DELETE')
$post->delete();
```

### Authorization (Permissions)

Ensure users can only modify their own posts:

```php
if (auth()->user()->id !== $post->user_id) {
    return redirect('/posts')->with('error', 'Unauthorized');
}
```

## Model Relationships

### One-to-Many (User → Posts)

**User.php**
```php
public function posts()
{
    return $this->hasMany(Post::class);
}
```

**Post.php**
```php
public function user()
{
    return $this->belongsTo(User::class);
}
```

**Usage**
```php
$user->posts;           // Get all posts by user
$post->user->name;      // Get post author's name
```

## Key Commands

| Command | Purpose |
|---------|---------|
| `php artisan serve` | Start development server |
| `php artisan tinker` | Interactive PHP shell with app loaded |
| `php artisan migrate` | Run database migrations |
| `php artisan migrate:rollback` | Revert migrations |
| `php artisan make:model Post` | Create new model |
| `php artisan make:controller PostController` | Create new controller |
| `php artisan route:list` | Display all routes |
| `php artisan db:seed` | Run database seeders |

---

**Learning Resources:**
- [Laravel Official Documentation](https://laravel.com/docs)
- [Blade Template Documentation](https://laravel.com/docs/blade)
- [Eloquent ORM](https://laravel.com/docs/eloquent)
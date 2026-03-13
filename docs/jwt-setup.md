# JWT Service Setup

This service provides RS256 signed JWTs for cross-domain SSO.

## Prerequisites

Ensure the `firebase/php-jwt` package is installed:

```bash
composer require firebase/php-jwt
```

## 1. Key Generation

You need to generate an RSA Key Pair (Private & Public Keys).

### Automatic Generation (Recommended)

If you have implemented the `SetupJwtKeys` command:

```bash
php artisan jwt:setup
```

### Manual Generation

Run the following commands in your project root:

```bash
# Create directory
mkdir -p storage/jwt

# Generate Private Key (2048 or 4096 bits)
openssl genrsa -out storage/jwt/private.pem 4096

# Generate Public Key from Private Key
openssl rsa -in storage/jwt/private.pem -pubout -out storage/jwt/public.pem

# Set Permissions (Important for security)
chmod 600 storage/jwt/private.pem
chmod 644 storage/jwt/public.pem
```

## 2. Configuration

Add the following to your `.env` file (paths are relative to project root, or absolute):

```env
JWT_PRIVATE_KEY_PATH=storage/jwt/private.pem
JWT_PUBLIC_KEY_PATH=storage/jwt/public.pem
```

## 3. Usage Example

Inject `App\Services\JwtService` into your controller or login flow.

```php
use App\Services\JwtService;

public function login(Request $request, JwtService $jwtService)
{
    // ... authenticate user ...
    $user = Auth::user();

    $token = $jwtService->generateToken(
        userId: $user->id,
        email: $user->email,
        appSlug: 'my-app',
        role: 'admin',
        audience: 'https://client-app.com'
    );

    return response()->json(['token' => $token]);
}
```

### Public Key Exposure
To allow client apps to verify tokens, you can expose the public key via an API endpoint:

```php
Route::get('/auth/public-key', function (JwtService $service) {
    return response($service->getPublicKey())
        ->header('Content-Type', 'text/plain');
});
```

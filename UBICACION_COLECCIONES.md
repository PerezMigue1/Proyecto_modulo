# 📍 Ubicación de Colecciones en el Código

## 🔍 Colección "usuario" (para guardar usuarios)

**Archivo:** `app/Models/User.php`  
**Línea:** 26

```php
protected $collection = 'usuario';
```

**Para cambiarla:**
```php
protected $collection = 'users'; // Cambia aquí
```

## 🔍 Colección "recuperar-password" (para las preguntas)

**Archivo:** `app/Providers/FortifyServiceProvider.php`  
**Línea:** 72

```php
$collection = $database->selectCollection('recuperar-password');
```

**Para cambiarla:**
```php
$collection = $database->selectCollection('preguntas'); // Cambia aquí
```

## 📋 Resumen

| Colección | Archivo | Línea | Propósito |
|-----------|---------|-------|-----------|
| `usuario` | app/Models/User.php | 26 | Guardar usuarios registrados |
| `recuperar-password` | app/Providers/FortifyServiceProvider.php | 72 | Obtener preguntas secretas |

## 💡 Nota

Si cambias el nombre de la colección `usuario` a `users`, tendrás que actualizar la línea 26 de `app/Models/User.php`.


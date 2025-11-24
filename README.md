# Système de Gestion des Présences

## 📋 Table des matières
- [Introduction](#-introduction)
- [Installation](#-installation)
- [Configuration](#-configuration)
- [Démarrage rapide](#-démarrage-rapide)
- [Documentation de l'API](#-documentation-de-lapi)
  - [Authentification](#-authentification)
  - [Utilisateurs](#-utilisateurs)
  - [Pointages](#-pointages)
  - [Sites](#-sites)
  - [Paramètres](#-paramètres)
- [Rôles et permissions](#-rôles-et-permissions)
- [Exemples](#-exemples)
- [Dépannage](#-dépannage)

## 🌟 Introduction

Ce projet est une API RESTful pour la gestion des présences et des pointages, construite avec Laravel. Il permet de gérer les utilisateurs, les pointages, les sites et les paramètres système de manière sécurisée.

## 🚀 Installation

### Prérequis
- PHP 8.2+
- Composer
- MySQL 8.0+
- Node.js 16+ & NPM
- Docker et Docker Compose (optionnel, pour le développement avec Docker)

### Étapes d'installation

1. **Cloner le dépôt**
   ```bash
   git clone [URL_DU_DEPOT]
   cd attendance
   ```

2. **Installer les dépendances PHP**
   ```bash
   composer install
   ```

3. **Configurer l'environnement**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configurer la base de données**
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=attendance
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. **Exécuter les migrations**
   ```bash
   php artisan migrate --seed
   ```

6. **Installer les dépendances frontend**
   ```bash
   npm install
   npm run build
   ```

7. **Démarrer le serveur**
   ```bash
   php artisan serve
   ```

## 🐳 Installation avec Docker

### Prérequis
- Docker
- Docker Compose

### Étapes d'installation avec Docker

1. **Cloner le dépôt**
   ```bash
   git clone [URL_DU_DEPOT]
   cd attendance
   ```

2. **Copier les fichiers de configuration**
   ```bash
   cp .env.docker .env
   cp docker-compose.yml.dist docker-compose.yml
   ```

3. **Démarrer les conteneurs**
   ```bash
   docker-compose up -d --build
   ```

4. **Installer les dépendances**
   ```bash
   docker-compose exec app composer install
   ```

5. **Générer la clé d'application**
   ```bash
   docker-compose exec app php artisan key:generate
   ```

6. **Exécuter les migrations**
   ```bash
   docker-compose exec app php artisan migrate --seed
   ```

7. **Installer les dépendances frontend**
   ```bash
   docker-compose exec app npm install
   docker-compose exec app npm run build
   ```

### Accès aux services

- **Application** : http://localhost:8000
- **phpMyAdmin** : http://localhost:8080
  - Serveur: `db`
  - Utilisateur: `laravel`
  - Mot de passe: `password`

### Commandes utiles

- **Voir les logs** : `docker-compose logs -f`
- **Arrêter les conteneurs** : `docker-compose down`
- **Redémarrer un service** : `docker-compose restart [nom_du_service]`
- **Accéder au conteneur** : `docker-compose exec app bash`

## 🔧 Configuration

### Variables d'environnement importantes

```env
APP_NAME="Gestion des Présences"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=attendance
DB_USERNAME=root
DB_PASSWORD=

# Configuration de l'API
API_PREFIX=api
API_VERSION=v1

# Configuration des tokens d'API
SANCTUM_STATEFUL_DOMAINS=localhost:3000
SESSION_DOMAIN=localhost
```

## 🚦 Démarrage rapide

### Créer un administrateur

```bash
php artisan make:filament-user
```

### Lancer les workers de file d'attente

```bash
php artisan queue:work
```

## 📚 Documentation de l'API

### 🔐 Authentification

#### S'inscrire

```http
POST /api/v1/auth/register
```

**Corps de la requête :**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "motdepasse123",
  "password_confirmation": "motdepasse123"
}
```

**Réponse réussie :**
```json
{
  "message": "Registered",
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com",
      "role": "employee"
    },
    "token": "1|abcdef123456...",
    "abilities": ["attendance:read", "schedules:read"]
  }
}
```

#### Se connecter

```http
POST /api/v1/auth/login
```

**Corps de la requête :**
```json
{
  "email": "john@example.com",
  "password": "motdepasse123"
}
```

**Réponse réussie :**
```json
{
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "role": "employee"
  },
  "token": "2|ghijkl789012...",
  "abilities": ["attendance:read", "schedules:read"]
}
```

### 👥 Utilisateurs

#### Lister les utilisateurs

```http
GET /api/v1/users
Authorization: Bearer {token}
```

**Paramètres de requête :**
- `role` (optionnel) : Filtrer par rôle
- `per_page` (optionnel) : Nombre d'éléments par page (défaut: 15)

**Réponse réussie :**
```json
{
  "data": [
    {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com",
      "role": "employee",
      "created_at": "2023-11-19T12:00:00Z"
    }
  ],
  "links": {
    "first": "...",
    "last": "...",
    "prev": null,
    "next": null
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 1,
    "path": "...",
    "per_page": 15,
    "to": 1,
    "total": 1
  }
}
```

### 📝 Pointages

#### Enregistrer un pointage

```http
POST /api/v1/attendance
Authorization: Bearer {token}
Content-Type: application/json

{
  "user_id": 1,
  "type": "in",
  "timestamp": "2023-11-19 08:00:00",
  "device_id": "DEV-001",
  "location": "48.8566,2.3522"
}
```

**Réponse réussie :**
```json
{
  "id": 1,
  "user_id": 1,
  "type": "in",
  "timestamp": "2023-11-19T08:00:00.000000Z",
  "status": "pending",
  "device_id": "DEV-001",
  "location": "48.8566,2.3522",
  "created_at": "2023-11-19T08:00:01.000000Z",
  "updated_at": "2023-11-19T08:00:01.000000Z"
}
```

### 🏢 Sites

#### Créer un site

```http
POST /api/v1/sites
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "Siège social",
  "address": "123 Rue Exemple",
  "city": "Paris",
  "postal_code": "75000",
  "country": "France"
}
```

**Réponse réussie :**
```json
{
  "id": 1,
  "name": "Siège social",
  "address": "123 Rue Exemple",
  "city": "Paris",
  "postal_code": "75000",
  "country": "France",
  "created_at": "2023-11-19T12:00:00Z",
  "updated_at": "2023-11-19T12:00:00Z"
}
```

## 👑 Rôles et permissions

| Rôle | Description | Permissions |
|------|-------------|-------------|
| `super_admin` | Accès complet | Toutes les permissions |
| `hr` | Ressources humaines | Gestion des utilisateurs, pointages, horaires, congés |
| `manager` | Responsable d'équipe | Gestion des pointages et congés de son équipe |
| `auditor` | Auditeur | Lecture seule sur toutes les données |
| `employee` | Employé | Gestion de ses propres pointages et demandes |

## 💡 Exemples

### Exemple avec cURL

**Authentification :**
```bash
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email": "admin@example.com", "password": "password"}'
```

**Lister les utilisateurs :**
```bash
curl -X GET http://localhost:8000/api/v1/users \
  -H "Authorization: Bearer VOTRE_TOKEN_JWT"
```

## 🛠 Dépannage

### Erreurs courantes

1. **Erreur 401 Non autorisé**
   - Vérifiez que le token JWT est valide et correctement inclus dans l'en-tête `Authorization`
   - Vérifiez que le token n'a pas expiré

2. **Erreur 403 Interdit**
   - Vérifiez que l'utilisateur a les permissions nécessaires
   - Vérifiez que le rôle de l'utilisateur est correct

3. **Erreur 422 Données non valides**
   - Vérifiez que tous les champs requis sont fournis
   - Vérifiez le format des données envoyées

### Support

Pour toute question ou assistance, veuillez contacter l'équipe de support à support@example.com.

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

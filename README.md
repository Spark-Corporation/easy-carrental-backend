# backend_cars

Backend Laravel pour la gestion d'une flotte de voitures.

## Présentation

Cette application backend permet de gérer des voitures, des catégories, des conducteurs, des pannes, des historiques, des réservations, des factures et des paiements.

## Installation

1. Cloner le dépôt.
2. Installer les dépendances PHP avec Composer :
   ```bash
   composer install
   ```
3. Copier le fichier d'environnement :
   ```bash
   cp .env.example .env
   ```
4. Générer la clé d'application :
   ```bash
   php artisan key:generate
   ```
5. Configurer la base de données dans le fichier `.env`.
6. Exécuter les migrations :
   ```bash
   php artisan migrate
   ```

## Utilisation

Lancer le serveur de développement :

```bash
php artisan serve
```

L'API sera disponible par défaut sur `http://127.0.0.1:8000`.

## Tests

Lancer les tests PHPUnit :

```bash
php artisan test
```

## Structure du projet

- `app/Models` : modèles Eloquent
- `app/Http/Controllers` : contrôleurs API
- `database/migrations` : migrations de base de données
- `routes/api.php` : routes API

## Contributions

<!-- Les contributions sont les bienvenues. Merci d'ouvrir une issue ou une Pull Request. -->

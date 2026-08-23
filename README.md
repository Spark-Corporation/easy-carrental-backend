# Spark Corporation — Backend API

Backend Laravel de la plateforme web de gestion de location de voitures,
développé dans le cadre d'un stage à SPARK CORPORATION.

---

## Présentation

Cette API RESTful constitue le coeur du système de gestion de location de
voitures. Elle assure l'ensemble de la logique métier de la plateforme,
notamment :

- Gestion des utilisateurs et authentification (Laravel Sanctum)
- Gestion du parc automobile (véhicules, catégories, pannes, retours)
- Gestion des chauffeurs et des affectations
- Gestion des réservations (création, validation, annulation)
- Génération et gestion des factures
- Suivi des paiements
- Historique des activités

---

## Technologies utilisées

| Technologie     | Version |
|-----------------|---------|
| PHP             | 8.3     |
| Laravel         | 13.8    |
| MySQL           | 8.0     |
| Laravel Sanctum | -       |

---

## Installation

### Prérequis

- PHP >= 8.3
- Composer
- MySQL >= 8.0

### Étapes

1. Cloner le dépôt

   ```bash
   git clone https://github.com/votre-repo/backend_cars.git
   cd backend_cars
   ```

2. Installer les dépendances

   ```bash
   composer install
   ```

3. Configurer l'environnement

   ```bash
   cp .env.example .env
   ```

4. Générer la clé d'application

   ```bash
   php artisan key:generate
   ```

5. Configurer la base de données dans `.env`

   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=spark_cars
   DB_USERNAME=root
   DB_PASSWORD=
   ```

6. Exécuter les migrations

   ```bash
   php artisan migrate
   ```

7. Lancer le serveur de développement

   ```bash
   php artisan serve
   ```

L'API sera disponible sur `http://127.0.0.1:8000/api`

---

## Structure du projet

```
backend_cars/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php
│   │   │   ├── CarController.php
│   │   │   ├── ReservationController.php
│   │   │   ├── InvoiceController.php
│   │   │   ├── PaymentController.php
│   │   │   ├── DriverController.php
│   │   │   ├── PanneController.php
│   │   │   └── HistoricController.php
│   │   └── Middleware/
│   └── Models/
│       ├── User.php
│       ├── Car.php
│       ├── Category.php
│       ├── Reservation.php
│       ├── Invoice.php
│       ├── Payment.php
│       ├── Driver.php
│       ├── Leasing.php
│       ├── Panne.php
│       ├── CarBack.php
│       └── Historic.php
├── database/
│   └── migrations/
├── routes/
│   └── api.php
└── .env
```

---

## Authentification

L'API utilise Laravel Sanctum pour l'authentification par token.

Inclure le token dans chaque requête protégée :

```http
Authorization: Bearer {votre_token}
```

### Rôles disponibles

| Rôle   | Accès                                 |
|--------|---------------------------------------|
| ADMIN  | Accès complet à toutes les ressources |
| CLIENT | Accès limité à ses propres données    |

---

## Principales routes API

### Authentification

```
POST   /api/login              Connexion
POST   /api/register           Inscription
POST   /api/logout             Déconnexion
```

### Véhicules

```
GET    /api/cars               Liste des véhicules disponibles
POST   /api/cars               Enregistrer un véhicule
PUT    /api/cars/{id}          Modifier un véhicule
DELETE /api/cars/{id}          Supprimer un véhicule
```

### Réservations

```
GET    /api/reservations                    Liste des réservations
POST   /api/reservations                    Créer une réservation
PUT    /api/reservations/{id}               Modifier une réservation
POST   /api/reservations/{id}/valider       Valider une réservation
POST   /api/reservations/{id}/annuler       Annuler une réservation
POST   /api/reservations/{id}/demarrer      Démarrer une location
POST   /api/reservations/{id}/terminer      Terminer une location
```

### Factures

```
GET    /api/invoices/{id}                   Détail d'une facture
GET    /api/invoices/{id}/download          Télécharger une facture PDF
```

### Paiements

```
POST   /api/payments                        Enregistrer un paiement
GET    /api/payments                        Liste des paiements
```

### Chauffeurs

```
GET    /api/drivers                         Liste des chauffeurs
POST   /api/drivers                         Enregistrer un chauffeur
PUT    /api/drivers/{id}                    Modifier un chauffeur
DELETE /api/drivers/{id}                    Supprimer un chauffeur
POST   /api/drivers/{id}/affecter           Affecter un chauffeur
```

### Pannes

```
GET    /api/pannes                          Liste des pannes
POST   /api/pannes                          Enregistrer une panne
PUT    /api/pannes/{id}                     Modifier une panne
POST   /api/pannes/{id}/resoudre            Résoudre une panne
```

### Historique

```
GET    /api/historics                       Liste des activités
```

---

## Tests

Lancer les tests :

```bash
php artisan test
```

---

## Auteur

NOUGNANKEY Amégninou Yao Faure 
email : na5yfaure@gmail.com
Télephone : (+228) 70 44 36 91
Stage effectué au sein de SPARK CORPORATION
Période : Juin — Août 2026

# MyFocus

MyFocus est une application web Symfony qui centralise ton **tableau de bord perso** :
- fil d’actualités personnalisé à partir de tes mots-clés
- météo de ta ville
- gestion d’objectifs (objectives)

L’application est pensée pour être utilisée derrière une authentification classique (register / login) avec un dashboard pour chaque utilisateur.

---

## Fonctionnalités principales

- 🔐 **Authentification**
  - Inscription (`/register`)
  - Connexion / déconnexion (`/login`, `/logout`)
  - Gestion des utilisateurs via l’entité `User`

- 🏠 **Page d’accueil personnalisée**
  - `/` (route `app_home`)
  - Accès rapide à :
    - Dashboard
    - Préférences
    - Connexion / inscription

- 📊 **Dashboard utilisateur**
  - Route : `/dashboard` (`app_dashboard`)
  - Affiche :
    - Bonjour *Prénom* + ville
    - Bloc météo
    - Bloc news basées sur les mots-clés favoris

- 🌤 **Météo**
  - Service : `App\Service\WeatherService`
  - Utilise l’API **OpenWeatherMap**
  - Recherche par ville définie dans le profil utilisateur (`User::city`)

- 📰 **News personnalisées**
  - Service : `App\Service\NewsService`
  - Fil d’actualités basé sur les **mots-clés favoris** (`User::favoriteKeywords`)
  - Exemple : `javascript, php, symfony`

- 🎯 **Objectifs**
  - Entité : `Objective`
  - Relation : `User` 1—N `Objective`
  - Formulaire : `ObjectiveFormType` (création / édition d’objectifs, WIP)

- ⚙️ **Préférences utilisateur**
  - Route : `/preference/edit` (`app_preference_edit`)
  - Formulaire de type `PreferenceType` (ville + mots-clés favoris)
  - Mise à jour de `city` et `favoriteKeywords` pour l’utilisateur connecté

---

## Stack technique

- **PHP** (>= 8.1 recommandé)
- **Symfony** 6+ (Framework + Console + Security + HttpClient)
- **Doctrine ORM** (MySQL / MariaDB)
- **Twig** pour les templates
- **Bootstrap 5** pour le design de base
- **Docker / Docker Compose** pour l’environnement de dev (conteneur `php`, etc.)

---

## Prérequis

- Git
- Docker & Docker Compose **ou** un environnement PHP local (PHP 8.x, Composer)
- Une base de données MySQL / MariaDB disponible
- Une clé API OpenWeatherMap (et éventuellement une clé pour ton provider de news)

---

## Installation avec Docker

1. **Cloner le dépôt**

```bash
git clone https://github.com/Louis-STREIFF/myfocus.git
cd myfocus/app
```

2. **Lancer l’environnement Docker**

```bash
docker compose up -d --build
```

3. **Installer les dépendances dans le conteneur PHP**

```bash
docker compose exec php bash
composer install
```

4. **Configurer l’environnement**

Copier le fichier `.env` en `.env.local` si besoin, puis adapter :

```env
### Exemple .env.local

# URL de connexion à la base de données
DATABASE_URL="mysql://user:password@db:3306/myfocus?serverVersion=8.0"

# Clé API OpenWeatherMap
WEATHER_API_KEY=ta_cle_openweather

# Clé pour le service de news (si utilisé)
NEWS_API_KEY=ta_cle_news
```

5. **Créer / mettre à jour la base de données**

```bash
php bin/console doctrine:database:create --if-not-exists
php bin/console doctrine:migrations:migrate --no-interaction
```

6. **Accéder à l’application**

En général via :  
`http://localhost:8000` ou le port exposé dans ton `compose.yaml` (par ex. `:8080`).

---

## Installation sans Docker (local PHP)

1. **Cloner le dépôt**

```bash
git clone https://github.com/Louis-STREIFF/myfocus.git
cd myfocus/app
```

2. **Installer les dépendances**

```bash
composer install
```

3. **Configurer `.env.local`**

```env
DATABASE_URL="mysql://user:password@127.0.0.1:3306/myfocus?serverVersion=8.0"
WEATHER_API_KEY=ta_cle_openweather
NEWS_API_KEY=ta_cle_news
```

4. **Créer la base + migrations**

```bash
php bin/console doctrine:database:create --if-not-exists
php bin/console doctrine:migrations:migrate --no-interaction
```

5. **Lancer le serveur de dev Symfony**

```bash
symfony serve
# ou
php -S 127.0.0.1:8000 -t public
```

---

## Utilisation

1. **Créer un compte**  
   - Aller sur `/register`  
   - Renseigner prénom, nom, email, mot de passe, mots-clés favoris, ville.

2. **Se connecter**  
   - Via `/login` (ou le lien de la navbar).

3. **Dashboard**  
   - Route : `/dashboard`  
   - Affiche la météo de ta ville + les news basées sur tes mots-clés.

4. **Modifier ses préférences**  
   - Route : `/preference/edit`  
   - Modifie `favoriteKeywords` et `city`.  
   - Les mots-clés sont normalisés (séparés par virgules) côté backend.

5. **Gérer ses objectifs**  
   - Formulaire basé sur `ObjectiveFormType` (entité `Objective`)  
   - Zone encore en cours d’évolution selon les besoins.

---

## Structure du projet (simplifiée)

```text
app/
├─ assets/                 # (si front buildé / importmap)
├─ bin/
├─ config/
│  ├─ packages/
│  ├─ routes/
│  └─ services.yaml
├─ migrations/             # Fichiers de migration Doctrine
├─ public/
│  ├─ index.php            # Front controller
│  └─ css/app.css          # Style global custom
├─ src/
│  ├─ Controller/
│  │  ├─ HomeController.php
│  │  ├─ DashboardController.php
│  │  ├─ RegistrationController.php
│  │  └─ (SecurityController, etc.)
│  ├─ Entity/
│  │  ├─ User.php
│  │  └─ Objective.php
│  ├─ Form/
│  │  ├─ RegistrationFormType.php
│  │  ├─ PreferenceType.php
│  │  └─ ObjectiveFormType.php
│  ├─ Repository/
│  └─ Service/
│     ├─ WeatherService.php
│     └─ NewsService.php
├─ templates/
│  ├─ base.html.twig
│  ├─ home/
│  │  └─ index.html.twig
│  ├─ dashboard/
│  ├─ registration/
│  └─ preference/
├─ tests/
└─ composer.json
```

---

## Scripts utiles

Depuis le conteneur PHP ou ton environnement local :

```bash
# Vérifier la cohérence du schéma Doctrine
php bin/console doctrine:schema:validate

# Rafraîchir le cache
php bin/console cache:clear

# Voir les routes
php bin/console debug:router
```

---

## Roadmap / pistes d’amélioration

- Interface complète de gestion des objectifs (CRUD, tableaux, filtres)
- Statistiques (objectifs atteints, historique)
- Personnalisation avancée du flux de news
- Internationalisation complète (i18n)
- Tests fonctionnels et unitaires plus poussés

---

## Licence

Projet pédagogique / perso. Adapter la licence selon ton besoin (MIT, GPL, privatif, etc.).

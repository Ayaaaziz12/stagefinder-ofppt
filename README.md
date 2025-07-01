# 📚 StageFinder OFPPT

> Plateforme web pour les stagiaires de l’OFPPT afin de rechercher, postuler et suivre leurs candidatures de stage selon leur filière et localisation.

## 🎯 Objectif
Centraliser les offres de stage pour les stagiaires de l’OFPPT et faciliter la communication avec les entreprises partenaires.

## 👤 Utilisateurs cibles
- **Stagiaires OFPPT** : création de compte, dépôt de CV, consultation et filtrage d’offres, avis.
- **Entreprises** : publication d’offres, gestion des candidatures, envoi de messages.

## 🔑 Fonctionnalités principales
- Authentification sécurisée (stagiaires & entreprises)
- Dépôt et filtrage des offres de stage
- Sauvegarde et historique des candidatures
- Espace de statistiques personnalisé
- Multilingue (Français, Arabe, Anglais)
- Responsive design

## ⚙️ Technologies utilisées
- **Frontend** : React.js, Tailwind CSS
- **Backend** : Laravel
- **Base de données** : MySQL

## 🚀 Installation locale

### Pré-requis :
- Node.js, npm
- PHP, Composer
- MySQL

### Étapes :
```bash
# Backend
cd backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve

# Frontend
cd frontend
npm install
npm run dev
```

## 👨‍💻 Auteur
Aya Aziz – Projet de Fin d’Études OFPPT – 2025

## 📜 Licence
Usage libre à des fins éducatives.

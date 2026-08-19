# API Comptage de Pompe

## Description
API REST permettant d'enregistrer et de récupérer l'historique des séances de comptage de pompe, avec authentification sécurisée par token JWT.

## Authentification
L'API utilise un système de token JWT. Après connexion, incluez le token dans chaque requête :
Authorization: Bearer <token>

## Endpoints

### POST /register
Créer un compte utilisateur.

**Body (JSON) :**
{
"email": "test@test.com",
"password": "motdepasse123",
"nom": "Djagba",
"prenom": "Test"
}

**Réponse succès (201) :**
{"status": "success", "code": 201, "message": "Compte créé avec succès."}

**Réponse erreur (400) :**
{"status": "error", "code": 400, "message": "..."}

### POST /login
Se connecter et récupérer un token.

**Body (JSON) :**
{
"email": "test@test.com",
"password": "motdepasse123"
}

**Réponse succès (200) :**
{"token": "eyJ0eXAiOiJKV1QiLCJhbGc..."}

### POST /pompe
Enregistrer une séance de pompe. **Authentification requise.**

**Body (JSON) :**
{"countPompe": 10}

**Réponse succès (201) :**
{"status": "success", "code": 201, "message": "Pompe créée avec succès."}

### GET /historique
Récupérer l'historique de l'utilisateur connecté, regroupé par jour. **Authentification requise.**

**Réponse succès (200) :**
{
"status": "success",
"code": 200,
"historique": [
{"date": "2026-08-17", "countPompe": 22}
]
}
 Secure Drop API
A robust, containerized "Secure Drop" service built with Laravel 11, designed for high scalability and security. This service allows users to store sensitive strings (passwords, API keys) that are encrypted at rest and "burned" (permanently deleted) immediately after the first read.

Live Demo: http://3.90.33.65
API Documentation: 3.90.33.65

🚀 Quick Start (Local Development)
The project is designed for a one-command setup. Ensure you have Docker installed, then run:
bash

docker compose up -d

Use code with caution.

Post-Installation Steps:
Install dependencies: docker compose exec app composer install
Generate Key: docker compose exec app php artisan key:generate
Run Migrations: docker compose exec app php artisan migrate

The app will be available at http://secure-drop.localhost.

🏗️ Architectural Decisions
1. Backend: Service-Repository Pattern
Following the Strict Separation of Concerns requirement, the business logic is decoupled from the data access layer:
SecretRepository: Handles all Eloquent queries and database interactions.
SecretService: Manages business logic, including UUID generation, AES-256 encryption, and the "Burn-on-Read" validation logic.
SecretController: A slim controller that handles request validation and returns JSON responses.

2. DevOps: Multi-File Docker Strategy
To mirror a professional production environment, I implemented a three-file Docker Compose strategy:
docker-compose.yml: Defines the base service structure (Nginx, App, MySQL, Traefik).
docker-compose.override.yml: Optimized for local development with bind mounts and build: . instructions.
docker-compose.prod.yml: Optimized for AWS deployment with Restart Policies and pre-built images from Docker Hub.

3. Orchestration: Traefik Reverse Proxy
Instead of exposing Laravel directly, the application sits behind Traefik.
Routing is handled dynamically via Docker Labels on the Nginx container.
Health Checks are implemented to ensure the MySQL and PHP-FPM services are fully "Healthy" before Traefik routes any traffic to them.
🔒 Security Features
Encryption at Rest: All secret content is encrypted using Laravel's Crypt facade before being stored in the database.
Non-Sequential IDs: Uses UUID v4 to prevent ID enumeration attacks.
Non-Root Execution: The Docker container runs as a dedicated app user (UID 1000) to minimize the attack surface.
Multi-Stage Builds: The production image is built using a multi-stage process to keep the final footprint small and secure.
🛠️ CI/CD Pipeline
The project uses GitHub Actions for a complete CI/CD lifecycle:
Lint & Style: Runs Laravel Pint to ensure PSR-12 compliance.
Automated Testing: Executes Feature tests (PHPUnit) to verify the "Burn-on-Read" logic.
Docker Build & Push: Builds the production image and pushes it to Docker Hub.
Automated Deployment: Connects to the AWS VPS via SSH, pulls the latest image, runs migrations, and clears the configuration cache.

Required Secrets for CI/CD:
DOCKER_USERNAME / DOCKER_PASSWORD
SSH_HOST / SSH_USERNAME
SSH_PRIVATE_KEY / SSH_PASSPHRASE

📖 API Documentation
The API is fully documented using Scribe.
POST /api/v1/secrets: Store a secret with an optional ttl (minutes).
GET /api/v1/secrets/{uuid}: Retrieve the decrypted secret and trigger the permanent deletion.
Access the interactive docs at: /docs
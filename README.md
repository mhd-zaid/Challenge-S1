# Challenge S1


## Run Project
1. Run `docker compose up` (the logs will be displayed in the current shell) or Run `docker compose up -d` to run in background 
2. Run `docker compose down --remove-orphans` to stop the Docker containers.
3. Open `https://localhost` in your favorite web browser for app
4. Open `https://localhost:8080` in your favorite web browser for database
5. Run `docker compose logs -f` to display current logs, `docker compose logs -f [CONTAINER_NAME]` to display specific container's current logs 

## Rule to develop
1. Never push in master branch, Push to development branch
2. Making feature branches
3. Before merge development branch to master do test and verify all app is ok
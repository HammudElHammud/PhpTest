# Installation
To run the project you should have Docker in your system. The reasons of using Docker is it allow us to setup PostgreSQL, Apache, and Setting up for Cron job
- Clone the repository to your local machine and navigate to the project directory.
- Please add the XML file anywhere inside src folder.
- Run `docker-compose up -d`.
- Run `docker exec -it web /bin/bash /var/www/html/setup.sh`.
- Go to `http://localhost:8888/` to access the project.
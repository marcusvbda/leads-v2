# Setup container

## 1 - Clone Repository

## 2 - Create a .env file
Create a .env file according to the .env.example

## 3 - Create Docker Web Network
`$ docker network create web`

## 4 - Build the container images
```
$ docker compose up -d --force-recreate --build
```
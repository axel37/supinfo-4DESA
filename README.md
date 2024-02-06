# Readme de 4DESA
## Démarer l'application

Dans la console rentrer cette commande pour déployer Docker :
```
docker compose build && docker compose up 
```

Puis dans une autre console la commande suivante pour générer les clefs de token :
```
docker compose exec php sh -c '
    set -e
    apk add openssl
    php bin/console lexik:jwt:generate-keypair
    setfacl -R -m u:www-data:rX -m u:"$(whoami)":rwX config/jwt
    setfacl -dR -m u:www-data:rX -m u:"$(whoami)":rwX config/jwt
'
```
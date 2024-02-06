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

## Accès à la documentation
Une fois fais, allez à l'adresse :
```
https://symmetrical-disco-gw6v5ppp7pvf7xp-443.app.github.dev/
```

Aller ensuite dans la section 'API' pour acceder à la documentation 'SWAGGER'.

## Utiliser l'API
L'API peux être utilisé directement avec la documentation SWAGGER dans la section 'Try it out'.
Vous pouver également utiliser un logiciel de requête comme Postman par exemple.
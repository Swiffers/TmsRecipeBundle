TmsRecipeBundle Configuration Reference
=======================================

```yml
#app/config/config.yml

doctrine:
    dbal:
        driver:   "%database_driver%"
        host:     "%database_host%"
        port:     "%database_port%"
        dbname:   "%database_name%"
        user:     "%database_user%"
        password: "%database_password%"
        charset:  UTF8
        *mapping_types:*
            *enum: string*
```
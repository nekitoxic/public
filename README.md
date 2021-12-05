# TEST

# build docker
    docker-compose up -d --build

# install composer
    docker-compose exec fpm composer install

# Fixtures load
    docker-compose exec fpm bin/console d:f:l

# HOST
    http://127.0.0.1:81

# Routes
    # list
        /api/v1/categories          - GET
        /api/v1/products            - GET
        /api/v1/product-properties  - GET
        /api/v1/product-categories  - GET

    # single
        /api/v1/categories/{uuid}         - GET
        /api/v1/products/{uuid}           - GET
        /api/v1/product-properties/{uuid} - GET
        /api/v1/product-categories/{uuid} - GET

    # create
        /api/v1/categories - POST               | JSON - {"name":"string"}
        /api/v1/products - POST                 | JSON - {"name":"string", "price": number}
        /api/v1/product-properties - POST       | JSON - {"product": "uuid", "weight": number, "height": number}
        /api/v1/product-categories - POST       | JSON - {"product": "uuid", "category": "uuid"}

    # update
        /api/v1/categories/{uuid} - PUT              | JSON - {"name":"string"}
        /api/v1/products/{uuid} - PUT                | JSON - {"name":"string", "price": number}
        /api/v1/product-properties/{uuid} - PUT      | JSON - {"product": (nullable) "uuid", "weight": number, "height": number}
        /api/v1/product-categories/{uuid} - PUT      | JSON - {"product": (nullable) "uuid", "category": (nullable) "uuid"}

    # delete
        /api/v1/categories/{uuid} - DELETE
        /api/v1/products/{uuid} - DELETE
        /api/v1/product-properties/{uuid} - DELETE
        /api/v1/product-categories/{uuid} - DELETE

# Для авторизации передать Bearer Token сгенерированный в фикстурах 
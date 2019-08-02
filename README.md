Swagger Bundle
=================

Creates a [Swagger-ui](https://github.com/wordnik/swagger-ui) page (something like [this](https://petstore.swagger.io/)) in your Symfony4 application.

Description
=================

If you’re writing a Swagger API spec and it’s becoming too large, you can split it into multiple files.
This bundle allows a simple way to split specification files and generate static `index.html` with Swagger UI.


Installation
=================

```composer req stfalcon-studio/swagger-bundle```

#### Check the `config/bundles.php` file

By default Symfony Flex will add SwaggerBundle to the `config/bundles.php` file. But in case when you ignored `contrib-recipe` during bundle installation it would not be added. In this case add the bundle manually.

```php
# config/bundles.php

return [
    // other bundles
    StfalconStudio\SwaggerBundle\SwaggerBundle::class => ['all' => true],
    // other bundles
];
```

Using
================= 

First all we need to set up the folder where the spec is be storing.
This is the base folder relative for which we will structure the specification files.
```
swagger:
    config_folder: '%kernel.project_dir%/docs/api/'
```

Imagine you have a Swagger spec like this:

```
openapi: "3.0.0"
info:
  title: Simple API overview
  version: 2.0.0
paths:
  "/users":
    get:
      operationId: CreateUser
      summary: Create user
      responses:
        '201':
          description: |-
            201 response
  "/orders":
    post:
      operationId: CreateOrder
      summary: Create Order
      responses:
        '201':
          description: |-
            201 response        
```

Here is our desired folder structure:

```
/docs/api/
     ├── index.yaml
     ├── paths
     │   └── user
     |       └── create-user.yaml
     │   └── order
     |       └── create-order.yaml
     ├── responses
     │   └── created.yaml
```

Root file is `index.yaml`. Using `index.yaml` as file name for your root file is a convention. 

Here is list of files with their contents:
##### index.yaml
```
openapi: "3.0.0"
info:
  title: Simple API overview
  version: 2.0.0
paths:
  "$paths"
```

##### paths/user/create-user.yaml
```
"/users":
  get:
    operationId: CreateUser
    summary: Create user
    responses:
      "$responses/created.yaml"
```

##### paths/order/create-order.yaml
```
"/orders":
  post:
    operationId: CreateOrder
    summary: Create Order
    responses:
      "$responses/created.yaml"
```

##### paths/responses/created.yaml
```
'201':
  description: |-
    201 response
```

As you can see from the example, in order to specify a folder or file for the include we use the symbol `$` and name.

* `$paths` - include all `.yaml` files from folder `paths` (recursively);
* `$responses/created.yaml` - include the file `created.yaml` that storing in `responses` folder.

Generate Swagger UI
================= 

For generating Swagger UI static file use console command:

```
bin/console assets:install && bin/console swagger:generate-docs
```

The file will be saved in the `%kernel.publid_dic%/public/api/index.html` folder and shared at `http://<project>/api/index.html`.

## Contributing

Read the [CONTRIBUTING](https://github.com/stfalcon-studio/swagger-bundle/blob/master/.github/CONTRIBUTING.md) file.

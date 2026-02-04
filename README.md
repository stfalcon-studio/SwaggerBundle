# SwaggerBundle

:package: Creates a [Swagger-ui](https://github.com/wordnik/swagger-ui) page (something like [this](https://petstore.swagger.io/)) in Symfony application.

## Description

If you’re writing a Swagger API spec and it’s becoming too large, you can split it into multiple files.
This bundle allows a simple way to split specification files and generate static `index.html` with Swagger UI.

## Installation

```composer req stfalcon-studio/swagger-bundle```

### Check the `config/bundles.php` file

By default, Symfony Flex will add SwaggerBundle to the `config/bundles.php` file. Though, in case you
ignored `contrib-recipe` during bundle installation it would not be added. In this case you should add the bundle
manually.

```php
# config/bundles.php

return [
    // other bundles
    StfalconStudio\SwaggerBundle\SwaggerBundle::class => ['all' => true],
    // other bundles
];
```

## Using

First of all, it's necessary to set up the folders where the spec is stored and where the generated doc must be placed.

The api documentation we write is all inside `config_folder`.

The generated html file is going to be inside `doc_folder`.

```yaml
swagger:
  apis:
    frontend:
      config_folder: '%kernel.project_dir%/docs/api/frontend/'
      doc_folder: '%kernel.project_dir%/public/api/frontend/'
```

Imagine you are to have a Swagger spec like this:

```yaml
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

```yaml
/docs/api/frontend/
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

### index.yaml

```yaml
openapi: "3.0.0"
info:
  title: Simple API overview
  version: 2.0.0
paths:
  "$paths"
```

### paths/user/create-user.yaml

```yaml
"/users":
  get:
    operationId: CreateUser
    summary: Create user
    responses:
      "$responses/created.yaml"
```

### paths/order/create-order.yaml

```yaml
"/orders":
  post:
    operationId: CreateOrder
    summary: Create Order
    responses:
      "$responses/created.yaml"
```

### responses/created.yaml

```yaml
'201':
  description: |-
    201 response
```

As you can see from the example, in order to specify a folder or the included file, `$name` notation is used:

* `$paths` - include all `.yaml` files from folder `paths` (recursively);
* `$responses/created.yaml` - include the file `created.yaml` from the `responses` folder.

## Generate Swagger UI

For generating Swagger UI static file use console command:

```bash
bin/console assets:install && bin/console swagger:generate-docs frontend
```

The file will be saved in the `%kernel.publid_dic%/public/api/frontend/index.html` folder and shared
at `http://<project>/api/frontend/index.html`.

## Contributing

Read the [CONTRIBUTING](https://github.com/stfalcon-studio/SwaggerBundle/blob/master/.github/CONTRIBUTING.md) file.

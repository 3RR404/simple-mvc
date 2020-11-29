# Simple MVC framework

**Realy simple** MVC ( VC ) is not a project, is a FUN.

Views are simple HTML combine PHP.

Controllers are ready to first issue.

Models isn't exists. :) But i don't need it.

## Websupport API connector
Is first plugin for this framework... Connector to Websupport API. Administrate your services. Add or remove DNS records.

for fully compatibility you need add Client ID and Secret key from administration of services to Application configuration.

I forgot ADD config so...

```php
$this->response = new DNSRemote( 'CLIENT_ID', 'SECRET_KEY' );                       // return response
$this->services = \json_decode( $this->response->getService()->getJsonResponse() ); // decode response to objcet from string
```

nextime add config and response class. Maybe models too ;)
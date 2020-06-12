ClientBundle
============

KatenaChain Symfony Client Bundle that integrate [sdk-php-client](https://github.com/katena-chain/sdk-php-client).

## Installation

Applications that use Symfony Flex
----------------------------------

Not Available now.

Applications that don't use Symfony Flex
----------------------------------------

### Step 1: Download the Bundle

Open a terminal, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require katena-chain/client-bundle
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

### Step 2: Enable the Bundle

#### Symfony version < 4.0
Then, enable the bundle by adding it to the list of registered bundles
in the `app/AppKernel.php` file of your project:

```php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            // ...
            new <vendor>\<bundle-name>\<bundle-long-name>(),
        ];

        // ...
    }

    // ...
}
```

#### Symfony version > 4.0
Then, enable the bundle by adding it to the list of registered bundles
in the `bundles.php` file of your project:

```php
// bundles.php

return [
    //...
    KatenaChain\ClientBundle\KCClientBundle::class => ['all' => true]
];
```

### Step 3: Add configuration file

#### Symfony version < 4.0

Then, add the configuration file in `app/config/kc_client.yml`
```yaml
#app/config/kc_client.yml

kc_client:
    chain:
        chain_id: "%env(resolve:KC_CHAIN_ID)%"
        signer_company_bcid: "%env(resolve:KC_SIGNER_COMPANY_BCID)%"
        signer_id: "%env(resolve:KC_SIGNER_ID)%"
        signer_private_key: "%env(resolve:KC_SIGNER_PRIVATE_KEY)%"
    api:
        url: "%env(resolve:KC_API_URL)%"

```

#### Symfony version > 4.0
Then, add the configuration file in `config/packages/kc_client.yml`
```yaml
#config/packages/kc_client.yml

kc_client:
    chain:
        chain_id: "%env(resolve:KC_CHAIN_ID)%"
        signer_company_bcid: "%env(resolve:KC_SIGNER_COMPANY_BCID)%"
        signer_id: "%env(resolve:KC_SIGNER_ID)%"
        signer_private_key: "%env(resolve:KC_SIGNER_PRIVATE_KEY)%"
    api:
        url: "%env(resolve:KC_API_URL)%"
```

### Step 4: Update .env file

Add in your .env file :
```dotenv
###> katena-chain/client-bundle ###
KC_SIGNER_PRIVATE_KEY="7C67DeoLnhI6jvsp3eMksU2Z6uzj8sqZbpgwZqfIyuCZbfoPcitCiCsSp2EzCfkY52Mx58xDOyQLb1OhC7cL5A=="
KC_SIGNER_COMPANY_BCID="abcdef"
KC_SIGNER_ID="36b72ca9-fd58-44aa-b90d-5a855276ff82"
KC_CHAIN_ID="katena-chain-test"
KC_API_URL="https://nodes.test.katena.transchain.io/api/v1"
###< katena-chain/client-bundle###
```
## Usage

### Via dependency injection

Just require KatenaChain\ClientBundle\Services\Transactor service.
```php
//...

use KatenaChain\ClientBundle\Services\Transactor;

//...

    /**
     * @var Transactor
     */
    protected $transactor;

//...
    public function __construct(Transactor $transactor)
    {
        //...
        $this->transactor = $transactor;
        //...
    }
//...
```
### Via service container

```php
$transactor = $this->get("kc_client.transactor");
```

### Send certificate

```php
$transactor->sendCertificateRawV1Tx("ce492f92-a529-40c1-91e9-2af71e74ebea", "document_signature_value");
```

## Katena documentation

For more information, check the [katena documentation](https://doc.katena.transchain.io)

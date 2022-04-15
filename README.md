# Installation

```php
composer require upviral/php-sdk
```

## Usage

```php
require_once('vendor/autoload.php');

$apiKey = "<Your API Key>";
$upviral = new Upviral\SDK($apiKey);
```

## Fetch all campaigns

```php
$campaigns = $upviral->getCampaigns();
var_dump($campaigns);
```

## Fetch custom fields for a campaign

```php
$customFields = $upviral->geCustomFields('<campaign id>');
var_dump($customFields);
```
## Add new contact/lead

```php
$contact = new \Upviral\Entity\Contact();
$contact->name = '<Name>';
$contact->email = '<Email';
$contact->campaigns = '<campaign id>';
$contact->referred_by = '<Referal code>';
$contact->custom_fields = ['custom_field_1'=>'value'];
$res = $upviral->addContact($contact);
var_dump($res);
```

## Fetch contact/lead by id

```php
$contact = $upviral->getContact('<lead id>', '<campaign id>');
var_dump($contact);
```

## Fetch contact/lead by email

```php
$contact = $upviral->getContact('<email id>', '<campaign id>');
var_dump($contact);
```

## Fetch all contacts of a campaign

```php
$contacts = $upviral->getContacts('<campaign id>');
var_dump($contacts);
```

## Fetch all contacts of a campaign filtered by points

```php
$contacts = $upviral->getContacts('<campaign id>', ['operator'=>'<operator>', 'points'=>'<ponts>']);
// For example 
// $contacts = $upviral->getContacts('<campaign id>', ['operator'=>'>', 'points'=>'100']);
var_dump($contacts);
```

## Add points

```php
$res = $upviral->addPoints('<lead/contact id>', '<campaign id>', <points>);
var_dump($res);
```
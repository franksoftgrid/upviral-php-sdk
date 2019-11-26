# Installation
composer require upviral/php-sdk

# Usage

require_once('vendor/autoload.php');

$apiKey = "<Your API Key>";
$upviral = new Upviral\SDK($apiKey);

# Fetch all campaigns
$campaigns = $upviral->getCampaigns();
var_dump($campaigns);

# Fetch custom fields for a campaign
$customFields = $upviral->geCustomFields('<campaign id>');
var_dump($customFields);

# Add new contact/lead
$contact = new \Upviral\Entity\Contact();
$contact->name = '<Name>';
$contact->email = '<Email';
$contact->campaigns = '<campaign id>';
$contact->referred_by = '<Referal code>';
$contact->custom_fields = ['custom_field_1'=>'value'];
$res = $upviral->addContact($contact);
var_dump($res);

# Fetch contact/lead by id
$contact = $upviral->getContact('<lead id>', '<campaign id>');
var_dump($contact);

# Fetch contact/lead by email
$contact = $upviral->getContact('<email id>', '<campaign id>');
var_dump($contact);

# Fetch all contacts of a campaign
$contacts = $upviral->getContacts('<campaign id>');
var_dump($contacts);

# Fetch all contacts of a campaign filtered by points
$contacts = $upviral->getContacts('<campaign id>', ['operator'=>'<operator>', 'points'=>'<ponts>']);
// For example 
// $contacts = $upviral->getContacts('<campaign id>', ['operator'=>'>', 'points'=>'100']);
var_dump($contacts);

# Add points
$res = $upviral->addPoints('<lead/contact id>', '<campaign id>', <points>);
var_dump($res);

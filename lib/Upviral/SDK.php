<?php
namespace Upviral;

use GuzzleHttp\Client;

class SDK {
    private $apiKey;
    private $apiURL;
    private $apiVersion;
    private $apiClient;
    private $lastResponse = false;

    function __construct($apiKey, $apiVersion = 'v1') {
        $this->apiVersion = $apiVersion;
        $this->apiKey = $apiKey;
        $this->apiURL = "https://app.upviral.com/api/{$this->apiVersion}";

        $this->apiClient = new Client();
    }

    private function getRequestParams($method, $data=[]) {
        $data['uvmethod'] = $method;
        $data['uvapikey'] = $this->apiKey;
        return $data;
    }

    private function makeCall($method, $data = [], $verb = 'POST') {
        $options = [];
        $requestData = $this->getRequestParams($method, $data);
        if( $verb == 'POST') {
            $options['form_params'] = $requestData;
        } else if( $verb == 'GET') {
            $options['query'] = $requestData;
        }
        $this->lastResponse = $this->apiClient->request( $verb, $this->apiURL, $options );
        return $this->lastResponse;
    }

    function applyEntity(&$data, $key, $entity) {
        $data = new $entity($data);
    }

    function getCampaigns() {
        $response = $this->makeCall('lists', [], 'GET');
        $contents = (string) $response->getBody();
        $body = json_decode($contents);
        if($body->result == 'success') {
            $result = $body->data;
            array_walk($result, array($this, 'applyEntity'), 'Upviral\Entity\Campaign');
            return $result;
        }
        throw new \Exception($body->message);
    }

    function getCustomFields($campaignId) {
        $params = ['campaign_id'=>$campaignId];
        $response = $this->makeCall('get_custom_fields', $params, 'POST');
        $contents = (string) $response->getBody();
        $body = json_decode($contents);
        if($body->result == 'success') {
            $result = $body->custom_fields;
            array_walk($result, array($this, 'applyEntity'), 'Upviral\Entity\CustomField');
            return $result;
        }
        throw new \Exception($body->message);
    }

    function getContact($filter, $campaignId) {
        $params = [
            'campaign_id'=>$campaignId
        ];
        if(ctype_digit($filter)) {
            $method = 'get_lead_details';
            $params['lead_id'] = $filter;
        } else {
            $method = 'get_lead_details_by_email';
            $params['email'] = $filter;
        }
        
        $response = $this->makeCall($method, $params, 'POST');
        $contents = (string) $response->getBody();
        $body = json_decode($contents);
        if($body->result == 'success') {
            $result = $body->lead_details;
            $result = new \Upviral\Entity\Contact($result);
            return $result;
        }
        throw new \Exception($body->message);
    }

    function getContacts($campaignId, $pointsFilter = false, $page = 0, $count = 10) {
        $method = 'get_leads';
        $params = [
            'campaign_id'=>$campaignId,
            'start'=>$page,
            'size'=>$count,
        ];
        if($pointsFilter) {
            $method = 'get_leads_points';
            $params['operator'] = $pointsFilter['operator'];
            $params['points'] = $pointsFilter['points'];
        }
        $response = $this->makeCall($method, $params, 'POST');
        $contents = (string) $response->getBody();
        $body = json_decode($contents);
        if($body->data) {
            $result = $body->data->leads;
            array_walk($result, array($this, 'applyEntity'), 'Upviral\Entity\Contact');
            return $result;
        }
        throw new \Exception($body->message);
    }
 

    function addContact($contact) {
        $params = [
            'campaign_id'=>$contact->campaigns,
            'email'=>$contact->email,
            'name'=>$contact->name,
            'referral_code'=>$contact->referred_by,
            'custom_fields'=>$contact->custom_fields
        ];
        $response = $this->makeCall('add_contact', $params, 'POST');
        $contents = (string) $response->getBody();
        $body = json_decode($contents);
        if($body->result == 'success') {
            $result = $body->uid;
            return $result;
        }
        throw new \Exception($body->message);
    }

    function addPoints($leadId, $campaignId, $points) {
        $params = [
            'campaign_id'=>$campaignId,
            'lead_id'=>$leadId,
            'points'=>$points,
        ];
        $response = $this->makeCall('add_points', $params, 'POST');
        $contents = (string) $response->getBody();
        $body = json_decode($contents);
        if($body->result == 'success') {
            return true;
        }
        throw new \Exception($body->message);
    }
}
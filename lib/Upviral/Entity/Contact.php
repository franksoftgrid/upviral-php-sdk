<?php

namespace Upviral\Entity;

class Contact {
    
    private $id;
    public $name;
    public $email;
    public $status;
    public $created_date;
    public $total_points;
    public $is_fraud;
    public $referral_link;
    public $custom_fields;
    public $campaigns;
    public $referred_by;
    public $referrals;

    function __construct($record = false) {
        if($record) {
            $this->id = $record->id;
            $this->name = $record->name;
            $this->email = $record->email;
            $this->status = $record->status;
            $this->created_date = $record->created_date;
            $this->total_points = @$record->total_points;
            $this->is_fraud = @$record->is_fraud;
            $this->referral_link = $record->referral_link;
            $this->custom_fields = $record->custom_fields;
            $this->campaigns = [];
            if( isset($record->campaigns) ) {
                foreach($record->campaigns as $camp) {
                    $camp->id = $camp->campaign_id;
                    $camp->name = $camp->campaign_name;
                    $this->campaigns[] = new \Upviral\Entity\Campaign($camp);
                }
            }
            $this->referred_by = @$record->referred_by;
            $this->referrals = @$record->referrals;
        }
    }

    function getID() {
        return $this->id;
    }
}
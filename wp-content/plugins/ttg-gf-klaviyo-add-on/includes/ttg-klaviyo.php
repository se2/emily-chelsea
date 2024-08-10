<?php

use KlaviyoAPI\KlaviyoAPI;

class KlaviyoException extends Exception {}

class TTG_Klaviyo
{
    public $client;
    protected $TRACK_ONCE_KEY = '__track_once__';

    public function __construct($private_key, $public_key)
    {
        $this->client =  new KlaviyoAPI($private_key, 3);
    }

    function track($event, $customer_properties = array(), $properties = array(),  $timestamp = NULL)
    {
        if ((!array_key_exists('email', $customer_properties) || empty($customer_properties['email']))
            && (!array_key_exists('id', $customer_properties) || empty($customer_properties['id']))
        ) {
            throw new KlaviyoException('You must identify a user by email or ID.');
        }

        $params = [
            "data" => [
                "type" => 'event',
                "attributes" => [
                    "profile" => [
                        "data" => [
                            "type" => "profile",
                            "attributes" => [
                                'email' => $customer_properties['email'],
                                'first_name' => $customer_properties['first_name'],
                                'last_name' => $customer_properties['last_name'],
                                'properties' => array_merge($properties, $customer_properties)
                            ]
                        ]
                    ],
                    'metric' => [
                        "data" => [
                            "type" => "metric",
                            "attributes" => [
                                "name" => $event
                            ]
                        ]
                    ],
                    "properties" => [],
                    "unique_id" => "UNIQUE_EVENT_ID"
                ]
            ]
        ];


        if (!is_null($timestamp)) {
            $params['data']['attributes']['time'] = $timestamp;
        }

        $result = $this->client->Events->createEvent($params);

        return $result;
    }

    function track_once($event, $customer_properties = array(), $properties = array(), $timestamp = NULL)
    {
        $properties[$this->TRACK_ONCE_KEY] = true;
        return $this->track($event, $customer_properties, $properties, $timestamp);
    }

    function get_profile($email)
    {
        $profile = $this->client->Profiles->getProfiles([], [], ['equals(email,"' . $email . '")']);
        if (empty($profile['data'])) return [];

        return $profile['data'];
    }

    function subscribe_profiles($list_id, $profiles, $source = '')
    {
        $new_profiles = [];
        $source = '';
        if (!empty($profiles)) {
            foreach ($profiles as $key => $value) {
                $source = $value['source'];
                $new_profiles[] = [
                    "type" => "profile",
                    "attributes" => [
                        "email" => $value['email'],
                        "subscriptions" => [
                            "email" => [
                                "marketing" => [
                                    "consent" => "SUBSCRIBED"
                                ]
                            ]
                        ]
                    ]
                ];
            }
        }

        $params = [
            "data" => [
                "type" => "profile-subscription-bulk-create-job",
                "attributes" => [
                    "custom_source" => $source,
                    "profiles" => [
                        "data" => $new_profiles
                    ],
                    "historical_import" => false
                ],
                "relationships" => [
                    "list" => [
                        "data" => [
                            "type" => "list",
                            "id" => $list_id
                        ]
                    ]
                ]
            ]
        ];
        $this->client->Profiles->subscribeProfiles($params);
    }

    function add_members_to_list($list_id, array $arrayOfProfiles)
    {
        $profile = [];
        $subscribe_profiles = [];
        if (!empty($arrayOfProfiles)) {
            foreach ($arrayOfProfiles as $key => $value) {
                $subscribe_profiles[] = [
                    "email" => $value['email'],
                    'source' =>   $value['source']
                ];

                $profile[] = [
                    "type" => "profile",
                    "attributes" => [
                        'email' => $value['email'],
                        'first_name' => $value['first_name'],
                        'last_name' => $value['last_name'],
                        'properties' => $value
                    ]
                ];
            }
        }

        $params = [
            'data' => [
                "type" => "profile-bulk-import-job",
                "attributes" => [
                    "profiles" => [
                        "data" =>  $profile
                    ]
                ],
                "relationships" => [
                    "lists" => [
                        "data" => [
                            [
                                "type" => "list",
                                "id" => $list_id
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $this->client->Profiles->spawnBulkProfileImportJob($params);
        $this->subscribe_profiles($list_id, $subscribe_profiles);
    }

    function parseList($list)
    {
        return [
            'list_id' => $list['id'],
            "list_name" => $list['attributes']['name'],
        ];
    }

    function get_lists()
    {
        $lists = $this->client->Lists->getLists();

        if (empty($lists['data'])) return [];
        $lists = $lists['data'];

        return array_map([$this, 'parseList'], $lists);
    }

    function create_list($list_name)
    {
        $list = $this->client->Lists->createList([
            "data" => [
                'type' => 'list',
                'attributes' => [
                    'name' => $list_name
                ]
            ]
        ]);

        if (empty($list['data'])) return [];


        return $this->parseList($list['data']);
    }
};

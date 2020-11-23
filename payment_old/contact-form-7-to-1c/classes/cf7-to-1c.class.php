<?php

if(!class_exists('CF7_To_1C')):
    final class CF7_To_1C {
        const DOMAIN = CF7_TO_1C_DOMAIN;
        const API_1C_URL = 'http://91.205.17.233/DE/hs/de/land/';
        const API_1C_PORT = '8088';
        const API_1C_REQUEST_METHOD = 'POST';
        const API_1C_TOKEN = '5qcTsX3fD7zyFHNU038fVXUe2QYoYOlkH';

        public static $version = '1.0';

        /**
         * @var CF7_To_1C The single instance of the class
         */
        protected static $_instance = null;

        /**
         * Main CF7_To_1C Instance
         *
         * @static
         * @return CF7_To_1C - Main instance
         */
        public static function instance() {
            if(is_null(self::$_instance))
                self::$_instance = new self();

            return self::$_instance;
        }

        public static function send_data_to_1c($form_tag) {
            if($form = WPCF7_Submission::get_instance()) {
                $access_list   = array(
										'your-name', 'your-firstname', 'your-lastname', 'your-birthday', 'your-year',
										'billing_street', 'billing_housenumber', 'billing_housenumber_add',
										'billing_postcode', 'billing_city',
                    'your-phone', 'your-email', 'your-product', 'lead_id', 'lead-id', 'type'
                );
                $post_data = $form->get_posted_data();
                $url          = isset($post_data['url']) ? $post_data['utm_campaign'] : null;
                $utm_campaign = isset($post_data['utm_campaign']) ? $post_data['utm_campaign'] : null;
                $utm_content  = isset($post_data['utm_content']) ? $post_data['utm_content'] : null;
                $utm_medium   = isset($post_data['utm_medium']) ? $post_data['utm_medium'] : null;
                $utm_source   = isset($post_data['utm_source']) ? $post_data['utm_source'] : null;
                $utm_term   = isset($post_data['utm_term']) ? $post_data['utm_term'] : null;

                if(!empty($_SERVER['HTTP_REFERER'])) {
                    $url_parts = parse_url($_SERVER['HTTP_REFERER']);

                    parse_str($url_parts['query'], $query_params);

                    if(empty($url))
                        $url = $url_parts['scheme'].'://'.$url_parts['host'].$url_parts['path'];
                    if(empty($utm_campaign) && !empty($query_params['utm_campaign']))
                        $utm_campaign = $query_params['utm_campaign'];
                    if(empty($utm_content) && !empty($query_params['utm_content']))
                        $utm_content = $query_params['utm_content'];
                    if(empty($utm_medium) && !empty($query_params['utm_medium']))
                        $utm_medium = $query_params['utm_medium'];
                    if(empty($utm_source) && !empty($query_params['utm_source']))
                        $utm_source = $query_params['utm_source'];
                    if(empty($utm_term) && !empty($query_params['utm_term']))
                        $utm_term = $query_params['utm_term'];
                }

                $form_data = array(
                    'utm_campaign' => $utm_campaign,
                    'utm_content'  => $utm_content,
                    'utm_medium'   => $utm_medium,
                    'utm_source'   => $utm_source,
                    'utm_term'     => $utm_term,
                    'url'          => $url
                );

//                if($_COOKIE) {
//                    $form_data['param'] = $_COOKIE['param'];
//                    $form_data['pubid'] = $_COOKIE['pubid'];
//                }

                if(!empty($_SERVER) && !empty($_SERVER['REMOTE_ADDR']))
                    $form_data['IP_client'] = $_SERVER['REMOTE_ADDR'];

                foreach($post_data as $key => $data) {
                    if (in_array($key, $access_list)) {
                        switch ($key) {
                            case 'your-name':
                            case 'your-firstname':
                                $form_data['name'] = $data;
                                break;
                            case 'your-lastname':
                                $form_data['lastname'] = $data;
                                break;
                            case 'your-birthday':
                                $date = new \DateTime($data);
                                $form_data['date'] = $date->format('Ymd');
                                break;
                            case 'your-year':
                                $form_data['date'] = intval($data).'0101';
                                break;
                            case 'your-phone':
                                $form_data['phone'] = $data;
                                break;
                            case 'your-email':
                                $form_data['email'] = $data;
																break;
														case 'billing_street':
																$form_data['billing_street'] = $data;
																break;
														case 'billing_housenumber':
																$form_data['billing_housenumber'] = $data;
																break;
														case 'billing_housenumber_add':
																$form_data['billing_housenumber_add'] = $data;
																break;
														case 'billing_postcode':
																$form_data['billing_postcode'] = $data;
																break;
														case 'billing_city':
																$form_data['billing_city'] = $data;
																break;
                            case 'lead_id':
                            case 'lead-id':
                                $form_data['lead_id'] = $data;
                                break;
                            case 'your-product':
                                $form_data['goods'] = array();

                                if(is_array($data)) {
                                    foreach ($data as $product) {
                                        $product_parts = explode('*', $product);

                                        if (!empty($product_parts[1]))
                                            $form_data['goods'][] = $product_parts[1];
                                    }
                                } elseif(!empty($data))
                                    $form_data['goods'][] = $data;

                                break;
                            case 'type':
                                $form_data['type'] = $data;
                                break;
                        }
                    }
                }

                if(empty($form_data['lastname']) && !empty($form_data['name'])) {
                    $name_parts = explode(' ', $form_data['name']);

                    if(!empty($form_data[1])) {
                        $form_data['name'] = $name_parts[0];
                        $form_data['lastname'] = $name_parts[1];
                    } else
                        $form_data['lastname'] = $form_data['name'];
                }

                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, self::API_1C_URL.(!empty($form_data['goods']) ? 'DEOrder/' : 'DEReg/'));
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
                curl_setopt($curl, CURLOPT_TIMEOUT, 30);
                curl_setopt($curl, CURLOPT_HEADER, 1);
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, self::API_1C_REQUEST_METHOD);
                curl_setopt($curl, CURLOPT_PORT, self::API_1C_PORT);
                curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'token: '.self::API_1C_TOKEN
                ));
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($form_data));

                $response = curl_exec($curl);
                $response_error = curl_error($curl);
                $response_info = curl_getinfo($curl);

                curl_close($curl);

                if(!empty($response_info) && $response_info['http_code'] == 200)
                    return true;

                return false;
            }
        }
    }
endif;
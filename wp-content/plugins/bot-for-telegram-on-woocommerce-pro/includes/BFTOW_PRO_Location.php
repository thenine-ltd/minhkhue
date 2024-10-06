<?php
new BFTOW_PRO_Location;

class BFTOW_PRO_Location
{
    public function __construct()
    {
        add_action('bftow_location_saved', [$this, 'location_saved'], 10, 2);
    }

    function location_saved($user_id, $location)
    {
        $google_api_key = bftow_get_option('bftow_google_maps_api_key', '');
        if(!empty($google_api_key) && !empty($user_id) && !empty($location['latitude']) && !empty($location['longitude'])) {
            $language = get_bloginfo("language");
            $language = substr($language, 0, 2);
            if(empty($language)) {
                $language = 'en';
            }
            $address_data = file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?latlng={$location['latitude']},{$location['longitude']}&key={$google_api_key}&language={$language}");
            $address = json_decode($address_data, true);
            if(!empty($address['results'][0]['formatted_address']) && !empty($address['results'][0]['address_components'])){
                $formatted_address = $address['results'][0]['formatted_address'];
                $line_1 = '';
                $line_2 = '';
                $city = '';
                $country = '';
                $state = '';
                $code = '';
                foreach ($address['results'][0]['address_components'] as $component) {
                    if(in_array('street_number', $component['types'])) {
                        $line_1 .= $component['long_name'];
                    }
                    else if(in_array('route', $component['types'])) {
                        $line_1 .= ' ' . $component['long_name'];
                    }
                    else if(in_array('sublocality', $component['types'])) {
                        $line_2 .= $component['long_name'];
                    }
                    else if(in_array('locality', $component['types'])) {
                        $city .=  $component['long_name'];
                    }
                    else if(in_array('country', $component['types'])) {
                        $country .= $component['short_name'];
                    }
                    else if(in_array('administrative_area_level_1', $component['types'])) {
                        $state .= $component['long_name'];
                    }
                    else if(in_array('postal_code', $component['types'])) {
                        $code .= $component['long_name'];
                    }
                }
                if(!empty($line_1)) {
                    update_user_meta($user_id, 'billing_address_1', $line_1);
                }
                if(!empty($line_2)) {
                    update_user_meta($user_id, 'billing_address_2', $line_2);
                }
                if(!empty($city)) {
                    update_user_meta($user_id, 'billing_city', $city);
                }
                if(!empty($country)) {
                    update_user_meta($user_id, 'billing_country', $country);
                }
                if(!empty($state)) {
                    update_user_meta($user_id, 'billing_state', $state);
                }
                if(!empty($code)) {
                    update_user_meta($user_id, 'billing_postcode', $code);
                }
                if(!empty($formatted_address)) {
                    update_user_meta($user_id, 'bftow_formatted_address', $formatted_address);
                }
            }
        }
        elseif(!empty($user_id) && !empty($location['latitude']) && !empty($location['longitude'])) {
            $language = get_bloginfo("language");
            $language = substr($language, 0, 2);
            if(empty($language)) {
                $language = 'en';
            }
            $url = add_query_arg([
                'lat' => $location['latitude'],
                'format' => 'json',
                'lon' => $location['longitude'],
                'accept-language' => $language
            ], 'https://nominatim.openstreetmap.org/reverse');

            $response = $this->getLocationData($url);
            if(!empty($response['display_name']) && !empty($response['address'])){
                $formatted_address = $response['display_name'];
                $line_1 = [];
                $line_2 = [];
                $city = '';
                $country = '';
                $code = '';
                $address = !$response['address'];

                if(!empty($address['house_number'])) {
                    $line_1[] = $address['house_number'];
                }
                if(!empty($address['street'])) {
                    $line_1[]= $address['street'];
                }
                if(!empty($address['road'])) {
                    $line_1[]= $address['road'];
                }
                if(!empty($address['locality'])) {
                    $line_1[]= $address['locality'];
                }
                if(!empty($address['residential'])) {
                    $line_1[]= $address['residential'];
                }
                if(!empty($address['county'])) {
                    $line_1[]= $address['county'];
                }
                if(!empty($address['village'])) {
                    $line_2[]= $address['village'];
                }
                if(!empty($address['town'])) {
                    $line_2[]= $address['town'];
                }
                if(!empty($address['city'])) {
                    $line_2[]= $address['city'];
                    $city = $address['city'];
                }
                if(!empty($address['country'])) {
                    $line_2[]= $address['country'];
                    $country = $address['country'];
                }
                if(!empty($address['postcode'])) {
                    $code = $address['postcode'];
                }
                if(!empty($line_1)) {
                    update_user_meta($user_id, 'billing_address_1', implode(', ', $line_1));
                }
                if(!empty($line_2)) {
                    update_user_meta($user_id, 'billing_address_2', implode(', ', $line_2));
                }
                if(!empty($city)) {
                    update_user_meta($user_id, 'billing_city', $city);
                }
                if(!empty($country)) {
                    update_user_meta($user_id, 'billing_country', $country);
                }
                if(!empty($code)) {
                    update_user_meta($user_id, 'billing_postcode', $code);
                }
                if(!empty($formatted_address)) {
                    update_user_meta($user_id, 'bftow_formatted_address', $formatted_address);
                }
            }
        }
    }

    public function getLocationData($url)
    {
        $response = wp_remote_get($url);
        if (is_wp_error($response)) {
            return false;
        } elseif (wp_remote_retrieve_response_code($response) === 200) {
            return json_decode(wp_remote_retrieve_body($response), true, 100, JSON_UNESCAPED_UNICODE);
        }
    }

}
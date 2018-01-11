<?php
if (!function_exists('country_list')) {

    function country_list() {
        return array(
            "Afghanistan",
            "Albania",
            "Algeria",
            "Andorra",
            "Angola",
            "Antigua and Barbuda",
            "Argentina",
            "Armenia",
            "Australia",
            "Austria",
            "Azerbaijan",
            "Bahamas",
            "Bahrain",
            "Bangladesh",
            "Barbados",
            "Belarus",
            "Belgium",
            "Belize",
            "Benin",
            "Bhutan",
            "Bolivia",
            "Bosnia and Herzegovina",
            "Botswana",
            "Brazil",
            "Brunei",
            "Bulgaria",
            "Burkina Faso",
            "Burundi",
            "Cambodia",
            "Cameroon",
            "Canada",
            "Cape Verde",
            "Central African Republic",
            "Chad",
            "Chile",
            "China",
            "Colombi",
            "Comoros",
            "Congo (Brazzaville)",
            "Congo",
            "Costa Rica",
            "Cote d'Ivoire",
            "Croatia",
            "Cuba",
            "Cyprus",
            "Czech Republic",
            "Denmark",
            "Djibouti",
            "Dominica",
            "Dominican Republic",
            "East Timor (Timor Timur)",
            "Ecuador",
            "Egypt",
            "El Salvador",
            "Equatorial Guinea",
            "Eritrea",
            "Estonia",
            "Ethiopia",
            "Fiji",
            "Finland",
            "France",
            "Gabon",
            "Gambia, The",
            "Georgia",
            "Germany",
            "Ghana",
            "Greece",
            "Grenada",
            "Guatemala",
            "Guinea",
            "Guinea-Bissau",
            "Guyana",
            "Haiti",
            "Honduras",
            "Hungary",
            "Iceland",
            "India",
            "Indonesia",
            "Iran",
            "Iraq",
            "Ireland",
            "Israel",
            "Italy",
            "Jamaica",
            "Japan",
            "Jordan",
            "Kazakhstan",
            "Kenya",
            "Kiribati",
            "Korea, North",
            "Korea, South",
            "Kuwait",
            "Kyrgyzstan",
            "Laos",
            "Latvia",
            "Lebanon",
            "Lesotho",
            "Liberia",
            "Libya",
            "Liechtenstein",
            "Lithuania",
            "Luxembourg",
            "Macedonia",
            "Madagascar",
            "Malawi",
            "Malaysia",
            "Maldives",
            "Mali",
            "Malta",
            "Marshall Islands",
            "Mauritania",
            "Mauritius",
            "Mexico",
            "Micronesia",
            "Moldova",
            "Monaco",
            "Mongolia",
            "Morocco",
            "Mozambique",
            "Myanmar",
            "Namibia",
            "Nauru",
            "Nepal",
            "Netherlands",
            "New Zealand",
            "Nicaragua",
            "Niger",
            "Nigeria",
            "Norway",
            "Oman",
            "Pakistan",
            "Palau",
            "Panama",
            "Papua New Guinea",
            "Paraguay",
            "Peru",
            "Philippines",
            "Poland",
            "Portugal",
            "Qatar",
            "Romania",
            "Russia",
            "Rwanda",
            "Saint Kitts and Nevis",
            "Saint Lucia",
            "Saint Vincent",
            "Samoa",
            "San Marino",
            "Sao Tome and Principe",
            "Saudi Arabia",
            "Senegal",
            "Serbia and Montenegro",
            "Seychelles",
            "Sierra Leone",
            "Singapore",
            "Slovakia",
            "Slovenia",
            "Solomon Islands",
            "Somalia",
            "South Africa",
            "Spain",
            "Sri Lanka",
            "Sudan",
            "Suriname",
            "Swaziland",
            "Sweden",
            "Switzerland",
            "Syria",
            "Taiwan",
            "Tajikistan",
            "Tanzania",
            "Thailand",
            "Togo",
            "Tonga",
            "Trinidad and Tobago",
            "Tunisia",
            "Turkey",
            "Turkmenistan",
            "Tuvalu",
            "Uganda",
            "Ukraine",
            "United Arab Emirates",
            "United Kingdom",
            "United States",
            "Uruguay",
            "Uzbekistan",
            "Vanuatu",
            "Vatican City",
            "Venezuela",
            "Vietnam",
            "Yemen",
            "Zambia",
            "Zimbabwe"
        );
    }

}
$user_fields = array(
    "user_phone", "user_company", "user_address1", "user_address2","user_country", "user_city", "user_zipcode","user_retailer",
);

add_action('show_user_profile', 'my_show_extra_profile_fields');
add_action('edit_user_profile', 'my_show_extra_profile_fields');
add_action('personal_options_update', 'my_save_extra_profile_fields');
add_action('edit_user_profile_update', 'my_save_extra_profile_fields');

function my_show_extra_profile_fields($user) {
    ?>
    <h3>Extra profile information</h3>
    <table class="form-table">
        <tr>
            <th><label for="user_sltion">Salutation</label></th>
            <td>
                <input disabled type="text" name="user_sltion" id="user_sltion" value="<?php echo esc_attr(get_the_author_meta('user_sltion', $user->ID)); ?>" class="regular-text" /><br />
                <!--<span class="description">Please enter your phone number.</span>-->
            </td>
        </tr>
        <tr>
            <th><label for="user_retailer">Retailer</label></th>
            <td>
                <input disabled type="text" name="user_retailer" id="user_retailer" value="<?php echo esc_attr(get_the_author_meta('user_retailer', $user->ID)); ?>" class="regular-text" /><br />
                <!--<span class="description">Please enter your phone number.</span>-->
            </td>
        </tr>
        <tr>
            <th><label for="user_phone">Phone number</label></th>
            <td>
                <input type="text" name="user_phone" id="user_phone" value="<?php echo esc_attr(get_the_author_meta('user_phone', $user->ID)); ?>" class="regular-text" /><br />
                <!--<span class="description">Please enter your phone number.</span>-->
            </td>
        </tr>
        <tr>
            <th><label for="user_company">Company</label></th>
            <td>
                <input type="text" name="user_company" id="user_company" value="<?php echo esc_attr(get_the_author_meta('user_company', $user->ID)); ?>" class="regular-text" /><br />
                <!--<span class="description">Please enter your phone number.</span>-->
            </td>
        </tr>
        <tr>
            <th><label for="user_city">City</label></th>
            <td>
                <input type="text" name="user_city" id="user_company" value="<?php echo esc_attr(get_the_author_meta('user_city', $user->ID)); ?>" class="regular-text" /><br />
                <!--<span class="description">Please enter your phone number.</span>-->
            </td>
        </tr>
        <tr>
            <th><label for="user_zipcode">Zip code</label></th>
            <td>
                <input type="text" name="user_zipcode" id="user_zipcode" value="<?php echo esc_attr(get_the_author_meta('user_zipcode', $user->ID)); ?>" class="regular-text" /><br />
                <!--<span class="description">Please enter your phone number.</span>-->
            </td>
        </tr>
        <tr>
            <th><label for="user_address1">Address 1</label></th>
            <td>
                <input type="text" name="user_address1" id="user_address1" value="<?php echo esc_attr(get_the_author_meta('user_address1', $user->ID)); ?>" class="regular-text" /><br />
                <!--<span class="description">Please enter your phone number.</span>-->
            </td>
        </tr>
        <tr>
            <th><label for="user_address2">Address 2</label></th>
            <td>
                <input type="text" name="user_address2" id="user_address2" value="<?php echo esc_attr(get_the_author_meta('user_address2', $user->ID)); ?>" class="regular-text" /><br />
                <!--<span class="description">Please enter your phone number.</span>-->
            </td>
        </tr>
        
        <tr>
            <th><label for="user_country">Country</label></th>
            <td>
                <select name="user_country" id="user_country" style="width: 15em;">
                    <?php
                    $countres = country_list();
                    foreach ($countres as $country) {
                        if (esc_attr(get_the_author_meta('user_country', $user->ID)) == $country) {
                            echo '<option value="' . $country . '" selected="selected">' . $country . '</option>';
                        } else {
                            echo '<option value="' . $country . '">' . $country . '</option>';
                        }
                    }
                    ?>
                </select>
            </td>
        </tr>
    </table>
    <?php
}

function my_save_extra_profile_fields($user_id) {
    global $user_fields;

    if (!current_user_can('edit_user', $user_id))
        return false;

    foreach ($user_fields as $field) {
        update_usermeta($user_id, $field, $_POST[$field]);
    }
}
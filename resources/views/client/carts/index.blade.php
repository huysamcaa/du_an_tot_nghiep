@extends('client.layouts.app')

@section('content')
<section class="pageBannerSection">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="pageBannerContent text-center">
                            <h2>Shopping Cart</h2>
                            <div class="pageBannerPath">
                                <a href="{{ route('client.home') }}">Home</a>&nbsp;&nbsp;&gt;&nbsp;&nbsp;<span>Cart</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
</section>
<section class="cartPageSection woocommerce">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="cartHeader">
                            <h3>Your Cart Items</h3>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <table class="shop_table cart_table">
                            <thead>
                                <tr>
                                    <th class="product-thumbnail">Item Name</th>
                                    <th class="product-name">&nbsp;</th>
                                    <th class="product-price">Price</th>
                                    <th class="product-quantity">Quantity</th>
                                    <th class="product-subtotal">Total</th>
                                    <th class="product-remove">&nbsp;</th>
                                </tr>
                            </thead>
                                @php 
                                    $total = 0; 
                                    $totalQuantity = 0;
                                @endphp
                                @foreach($cartItems as $item)
                                @php 
                                    $total += $item->product->price * $item->quantity; 
                                    $totalQuantity += $item->quantity;
                                @endphp
                            <tbody>
                                <tr data-id="{{ $item->product_id }}">
                                    <td class="product-thumbnail">
                                        <a href="shop_details1.html"><img src="{{ asset($item->product->thumbnail) }}" alt="Cart Item"></a>
                                    </td>
                                    <td class="product-name">
                                        <a href="shop_details1.html">{{ $item->product->name }}</a>
                                    </td>
                                    <td class="product-price">
                                        <div class="pi01Price">
                                            <ins>{{ number_format($item->product->price) }}đ</ins>
                                        </div>
                                    </td>
                                    <td class="product-quantity">
                                        <div class="quantity clearfix">
                                            <form class="update-cart-form mt-3" method="POST">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $item->product_id }}">
                                                <button class="change-qty" type="submit" data-action="decrease">_</button>
                                                <span class="quantity-num">{{ $item->quantity }}</span>
                                                <button class="change-qty" type="submit" data-action="increase">+</button>
                                            </form>
                                        </div>
                                    </td>
                                    <td class="product-subtotal" data-id="{{$item->id}}">
                                        <div class="pi01Price">
                                            <ins>{{ number_format($item->product->price * $item->quantity) }}đ</ins>
                                        </div>
                                    </td>
                                    <td class="product-remove">
                                        <a href="{{ route('cart.destroy', $item->id) }}" class="remove"><span>
                                        </span></a>
                                    </td>
                            </tbody>
                            @endforeach
                            <tfoot>
                                <tr class="actions">
                                    <td colspan="2" class="text-start">
                                        <a href="shop_full_width.html" class="ulinaBTN"><span>Continue Shopping</span></a>  
                                    </td>
                                    <td colspan="4" class="text-end">
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="row cartAccessRow">
                    <div class="col-md-6 col-lg-4">
                        <div class="calculatShippingForm">
                            <h3>Calculate Shipping</h3>
                            <div class="shippingFormElements">
                                <label>Country</label>
                                <div class="sfeItem">
                                    <select name="country" style="display: none;">
                                        <option value="">Select a country</option>
                                        <option value="AF">Afghanistan</option><option value="AX">Åland Islands</option><option value="AL">Albania</option><option value="DZ">Algeria</option><option value="AS">American Samoa</option><option value="AD">Andorra</option><option value="AO">Angola</option><option value="AI">Anguilla</option><option value="AQ">Antarctica</option><option value="AG">Antigua and Barbuda</option><option value="AR">Argentina</option><option value="AM">Armenia</option><option value="AW">Aruba</option><option value="AU">Australia</option><option value="AT">Austria</option><option value="AZ">Azerbaijan</option><option value="BS">Bahamas</option><option value="BH">Bahrain</option><option value="BD">Bangladesh</option><option value="BB">Barbados</option><option value="BY">Belarus</option><option value="PW">Belau</option><option value="BE">Belgium</option><option value="BZ">Belize</option><option value="BJ">Benin</option><option value="BM">Bermuda</option><option value="BT">Bhutan</option><option value="BO">Bolivia</option><option value="BQ">Bonaire, Saint Eustatius and Saba</option><option value="BA">Bosnia and Herzegovina</option><option value="BW">Botswana</option><option value="BV">Bouvet Island</option><option value="BR">Brazil</option><option value="IO">British Indian Ocean Territory</option><option value="BN">Brunei</option><option value="BG">Bulgaria</option><option value="BF">Burkina Faso</option><option value="BI">Burundi</option><option value="KH">Cambodia</option><option value="CM">Cameroon</option><option value="CA">Canada</option><option value="CV">Cape Verde</option><option value="KY">Cayman Islands</option><option value="CF">Central African Republic</option><option value="TD">Chad</option><option value="CL">Chile</option><option value="CN">China</option><option value="CX">Christmas Island</option><option value="CC">Cocos (Keeling) Islands</option><option value="CO">Colombia</option><option value="KM">Comoros</option><option value="CG">Congo (Brazzaville)</option><option value="CD">Congo (Kinshasa)</option><option value="CK">Cook Islands</option><option value="CR">Costa Rica</option><option value="HR">Croatia</option><option value="CU">Cuba</option><option value="CW">Curaçao</option><option value="CY">Cyprus</option><option value="CZ">Czech Republic</option><option value="DK">Denmark</option><option value="DJ">Djibouti</option><option value="DM">Dominica</option><option value="DO">Dominican Republic</option><option value="EC">Ecuador</option><option value="EG">Egypt</option><option value="SV">El Salvador</option><option value="GQ">Equatorial Guinea</option><option value="ER">Eritrea</option><option value="EE">Estonia</option><option value="SZ">Eswatini</option><option value="ET">Ethiopia</option><option value="FK">Falkland Islands</option><option value="FO">Faroe Islands</option><option value="FJ">Fiji</option><option value="FI">Finland</option><option value="FR">France</option><option value="GF">French Guiana</option><option value="PF">French Polynesia</option><option value="TF">French Southern Territories</option><option value="GA">Gabon</option><option value="GM">Gambia</option><option value="GE">Georgia</option><option value="DE">Germany</option><option value="GH">Ghana</option><option value="GI">Gibraltar</option><option value="GR">Greece</option><option value="GL">Greenland</option><option value="GD">Grenada</option><option value="GP">Guadeloupe</option><option value="GU">Guam</option><option value="GT">Guatemala</option><option value="GG">Guernsey</option><option value="GN">Guinea</option><option value="GW">Guinea-Bissau</option><option value="GY">Guyana</option><option value="HT">Haiti</option><option value="HM">Heard Island and McDonald Islands</option><option value="HN">Honduras</option><option value="HK">Hong Kong</option><option value="HU">Hungary</option><option value="IS">Iceland</option><option value="IN">India</option><option value="ID">Indonesia</option><option value="IR">Iran</option><option value="IQ">Iraq</option><option value="IE">Ireland</option><option value="IM">Isle of Man</option><option value="IL">Israel</option><option value="IT">Italy</option><option value="CI">Ivory Coast</option><option value="JM">Jamaica</option><option value="JP">Japan</option><option value="JE">Jersey</option><option value="JO">Jordan</option><option value="KZ">Kazakhstan</option><option value="KE">Kenya</option><option value="KI">Kiribati</option><option value="KW">Kuwait</option><option value="KG">Kyrgyzstan</option><option value="LA">Laos</option><option value="LV">Latvia</option><option value="LB">Lebanon</option><option value="LS">Lesotho</option><option value="LR">Liberia</option><option value="LY">Libya</option><option value="LI">Liechtenstein</option><option value="LT">Lithuania</option><option value="LU">Luxembourg</option><option value="MO">Macao</option><option value="MG">Madagascar</option><option value="MW">Malawi</option><option value="MY">Malaysia</option><option value="MV">Maldives</option><option value="ML">Mali</option><option value="MT">Malta</option><option value="MH">Marshall Islands</option><option value="MQ">Martinique</option><option value="MR">Mauritania</option><option value="MU">Mauritius</option><option value="YT">Mayotte</option><option value="MX">Mexico</option><option value="FM">Micronesia</option><option value="MD">Moldova</option><option value="MC">Monaco</option><option value="MN">Mongolia</option><option value="ME">Montenegro</option><option value="MS">Montserrat</option><option value="MA">Morocco</option><option value="MZ">Mozambique</option><option value="MM">Myanmar</option><option value="NA">Namibia</option><option value="NR">Nauru</option><option value="NP">Nepal</option><option value="NL">Netherlands</option><option value="NC">New Caledonia</option><option value="NZ">New Zealand</option><option value="NI">Nicaragua</option><option value="NE">Niger</option><option value="NG">Nigeria</option><option value="NU">Niue</option><option value="NF">Norfolk Island</option><option value="KP">North Korea</option><option value="MK">North Macedonia</option><option value="MP">Northern Mariana Islands</option><option value="NO">Norway</option><option value="OM">Oman</option><option value="PK">Pakistan</option><option value="PS">Palestinian Territory</option><option value="PA">Panama</option><option value="PG">Papua New Guinea</option><option value="PY">Paraguay</option><option value="PE">Peru</option><option value="PH">Philippines</option><option value="PN">Pitcairn</option><option value="PL">Poland</option><option value="PT">Portugal</option><option value="PR">Puerto Rico</option><option value="QA">Qatar</option><option value="RE">Reunion</option><option value="RO">Romania</option><option value="RU">Russia</option><option value="RW">Rwanda</option><option value="ST">São Tomé and Príncipe</option><option value="BL">Saint Barthélemy</option><option value="SH">Saint Helena</option><option value="KN">Saint Kitts and Nevis</option><option value="LC">Saint Lucia</option><option value="SX">Saint Martin (Dutch part)</option><option value="MF">Saint Martin (French part)</option><option value="PM">Saint Pierre and Miquelon</option><option value="VC">Saint Vincent and the Grenadines</option><option value="WS">Samoa</option><option value="SM">San Marino</option><option value="SA">Saudi Arabia</option><option value="SN">Senegal</option><option value="RS">Serbia</option><option value="SC">Seychelles</option><option value="SL">Sierra Leone</option><option value="SG">Singapore</option><option value="SK">Slovakia</option><option value="SI">Slovenia</option><option value="SB">Solomon Islands</option><option value="SO">Somalia</option><option value="ZA">South Africa</option><option value="GS">South Georgia/Sandwich Islands</option><option value="KR">South Korea</option><option value="SS">South Sudan</option><option value="ES">Spain</option><option value="LK">Sri Lanka</option><option value="SD">Sudan</option><option value="SR">Suriname</option><option value="SJ">Svalbard and Jan Mayen</option><option value="SE">Sweden</option><option value="CH">Switzerland</option><option value="SY">Syria</option><option value="TW">Taiwan</option><option value="TJ">Tajikistan</option><option value="TZ">Tanzania</option><option value="TH">Thailand</option><option value="TL">Timor-Leste</option><option value="TG">Togo</option><option value="TK">Tokelau</option><option value="TO">Tonga</option><option value="TT">Trinidad and Tobago</option><option value="TN">Tunisia</option><option value="TR">Turkey</option><option value="TM">Turkmenistan</option><option value="TC">Turks and Caicos Islands</option><option value="TV">Tuvalu</option><option value="UG">Uganda</option><option value="UA">Ukraine</option><option value="AE">United Arab Emirates</option><option value="GB">United Kingdom (UK)</option><option value="US" selected="selected">United States (US)</option><option value="UM">United States (US) Minor Outlying Islands</option><option value="UY">Uruguay</option><option value="UZ">Uzbekistan</option><option value="VU">Vanuatu</option><option value="VA">Vatican</option><option value="VE">Venezuela</option><option value="VN">Vietnam</option><option value="VG">Virgin Islands (British)</option><option value="VI">Virgin Islands (US)</option><option value="WF">Wallis and Futuna</option><option value="EH">Western Sahara</option><option value="YE">Yemen</option><option value="ZM">Zambia</option><option value="ZW">Zimbabwe</option>
                                    </select><div class="nice-select" tabindex="0"><span class="current">United States (US)</span><ul class="list"><li data-value="" class="option">Select a country</li><li data-value="AF" class="option">Afghanistan</li><li data-value="AX" class="option">Åland Islands</li><li data-value="AL" class="option">Albania</li><li data-value="DZ" class="option">Algeria</li><li data-value="AS" class="option">American Samoa</li><li data-value="AD" class="option">Andorra</li><li data-value="AO" class="option">Angola</li><li data-value="AI" class="option">Anguilla</li><li data-value="AQ" class="option">Antarctica</li><li data-value="AG" class="option">Antigua and Barbuda</li><li data-value="AR" class="option">Argentina</li><li data-value="AM" class="option">Armenia</li><li data-value="AW" class="option">Aruba</li><li data-value="AU" class="option">Australia</li><li data-value="AT" class="option">Austria</li><li data-value="AZ" class="option">Azerbaijan</li><li data-value="BS" class="option">Bahamas</li><li data-value="BH" class="option">Bahrain</li><li data-value="BD" class="option">Bangladesh</li><li data-value="BB" class="option">Barbados</li><li data-value="BY" class="option">Belarus</li><li data-value="PW" class="option">Belau</li><li data-value="BE" class="option">Belgium</li><li data-value="BZ" class="option">Belize</li><li data-value="BJ" class="option">Benin</li><li data-value="BM" class="option">Bermuda</li><li data-value="BT" class="option">Bhutan</li><li data-value="BO" class="option">Bolivia</li><li data-value="BQ" class="option">Bonaire, Saint Eustatius and Saba</li><li data-value="BA" class="option">Bosnia and Herzegovina</li><li data-value="BW" class="option">Botswana</li><li data-value="BV" class="option">Bouvet Island</li><li data-value="BR" class="option">Brazil</li><li data-value="IO" class="option">British Indian Ocean Territory</li><li data-value="BN" class="option">Brunei</li><li data-value="BG" class="option">Bulgaria</li><li data-value="BF" class="option">Burkina Faso</li><li data-value="BI" class="option">Burundi</li><li data-value="KH" class="option">Cambodia</li><li data-value="CM" class="option">Cameroon</li><li data-value="CA" class="option">Canada</li><li data-value="CV" class="option">Cape Verde</li><li data-value="KY" class="option">Cayman Islands</li><li data-value="CF" class="option">Central African Republic</li><li data-value="TD" class="option">Chad</li><li data-value="CL" class="option">Chile</li><li data-value="CN" class="option">China</li><li data-value="CX" class="option">Christmas Island</li><li data-value="CC" class="option">Cocos (Keeling) Islands</li><li data-value="CO" class="option">Colombia</li><li data-value="KM" class="option">Comoros</li><li data-value="CG" class="option">Congo (Brazzaville)</li><li data-value="CD" class="option">Congo (Kinshasa)</li><li data-value="CK" class="option">Cook Islands</li><li data-value="CR" class="option">Costa Rica</li><li data-value="HR" class="option">Croatia</li><li data-value="CU" class="option">Cuba</li><li data-value="CW" class="option">Curaçao</li><li data-value="CY" class="option">Cyprus</li><li data-value="CZ" class="option">Czech Republic</li><li data-value="DK" class="option">Denmark</li><li data-value="DJ" class="option">Djibouti</li><li data-value="DM" class="option">Dominica</li><li data-value="DO" class="option">Dominican Republic</li><li data-value="EC" class="option">Ecuador</li><li data-value="EG" class="option">Egypt</li><li data-value="SV" class="option">El Salvador</li><li data-value="GQ" class="option">Equatorial Guinea</li><li data-value="ER" class="option">Eritrea</li><li data-value="EE" class="option">Estonia</li><li data-value="SZ" class="option">Eswatini</li><li data-value="ET" class="option">Ethiopia</li><li data-value="FK" class="option">Falkland Islands</li><li data-value="FO" class="option">Faroe Islands</li><li data-value="FJ" class="option">Fiji</li><li data-value="FI" class="option">Finland</li><li data-value="FR" class="option">France</li><li data-value="GF" class="option">French Guiana</li><li data-value="PF" class="option">French Polynesia</li><li data-value="TF" class="option">French Southern Territories</li><li data-value="GA" class="option">Gabon</li><li data-value="GM" class="option">Gambia</li><li data-value="GE" class="option">Georgia</li><li data-value="DE" class="option">Germany</li><li data-value="GH" class="option">Ghana</li><li data-value="GI" class="option">Gibraltar</li><li data-value="GR" class="option">Greece</li><li data-value="GL" class="option">Greenland</li><li data-value="GD" class="option">Grenada</li><li data-value="GP" class="option">Guadeloupe</li><li data-value="GU" class="option">Guam</li><li data-value="GT" class="option">Guatemala</li><li data-value="GG" class="option">Guernsey</li><li data-value="GN" class="option">Guinea</li><li data-value="GW" class="option">Guinea-Bissau</li><li data-value="GY" class="option">Guyana</li><li data-value="HT" class="option">Haiti</li><li data-value="HM" class="option">Heard Island and McDonald Islands</li><li data-value="HN" class="option">Honduras</li><li data-value="HK" class="option">Hong Kong</li><li data-value="HU" class="option">Hungary</li><li data-value="IS" class="option">Iceland</li><li data-value="IN" class="option">India</li><li data-value="ID" class="option">Indonesia</li><li data-value="IR" class="option">Iran</li><li data-value="IQ" class="option">Iraq</li><li data-value="IE" class="option">Ireland</li><li data-value="IM" class="option">Isle of Man</li><li data-value="IL" class="option">Israel</li><li data-value="IT" class="option">Italy</li><li data-value="CI" class="option">Ivory Coast</li><li data-value="JM" class="option">Jamaica</li><li data-value="JP" class="option">Japan</li><li data-value="JE" class="option">Jersey</li><li data-value="JO" class="option">Jordan</li><li data-value="KZ" class="option">Kazakhstan</li><li data-value="KE" class="option">Kenya</li><li data-value="KI" class="option">Kiribati</li><li data-value="KW" class="option">Kuwait</li><li data-value="KG" class="option">Kyrgyzstan</li><li data-value="LA" class="option">Laos</li><li data-value="LV" class="option">Latvia</li><li data-value="LB" class="option">Lebanon</li><li data-value="LS" class="option">Lesotho</li><li data-value="LR" class="option">Liberia</li><li data-value="LY" class="option">Libya</li><li data-value="LI" class="option">Liechtenstein</li><li data-value="LT" class="option">Lithuania</li><li data-value="LU" class="option">Luxembourg</li><li data-value="MO" class="option">Macao</li><li data-value="MG" class="option">Madagascar</li><li data-value="MW" class="option">Malawi</li><li data-value="MY" class="option">Malaysia</li><li data-value="MV" class="option">Maldives</li><li data-value="ML" class="option">Mali</li><li data-value="MT" class="option">Malta</li><li data-value="MH" class="option">Marshall Islands</li><li data-value="MQ" class="option">Martinique</li><li data-value="MR" class="option">Mauritania</li><li data-value="MU" class="option">Mauritius</li><li data-value="YT" class="option">Mayotte</li><li data-value="MX" class="option">Mexico</li><li data-value="FM" class="option">Micronesia</li><li data-value="MD" class="option">Moldova</li><li data-value="MC" class="option">Monaco</li><li data-value="MN" class="option">Mongolia</li><li data-value="ME" class="option">Montenegro</li><li data-value="MS" class="option">Montserrat</li><li data-value="MA" class="option">Morocco</li><li data-value="MZ" class="option">Mozambique</li><li data-value="MM" class="option">Myanmar</li><li data-value="NA" class="option">Namibia</li><li data-value="NR" class="option">Nauru</li><li data-value="NP" class="option">Nepal</li><li data-value="NL" class="option">Netherlands</li><li data-value="NC" class="option">New Caledonia</li><li data-value="NZ" class="option">New Zealand</li><li data-value="NI" class="option">Nicaragua</li><li data-value="NE" class="option">Niger</li><li data-value="NG" class="option">Nigeria</li><li data-value="NU" class="option">Niue</li><li data-value="NF" class="option">Norfolk Island</li><li data-value="KP" class="option">North Korea</li><li data-value="MK" class="option">North Macedonia</li><li data-value="MP" class="option">Northern Mariana Islands</li><li data-value="NO" class="option">Norway</li><li data-value="OM" class="option">Oman</li><li data-value="PK" class="option">Pakistan</li><li data-value="PS" class="option">Palestinian Territory</li><li data-value="PA" class="option">Panama</li><li data-value="PG" class="option">Papua New Guinea</li><li data-value="PY" class="option">Paraguay</li><li data-value="PE" class="option">Peru</li><li data-value="PH" class="option">Philippines</li><li data-value="PN" class="option">Pitcairn</li><li data-value="PL" class="option">Poland</li><li data-value="PT" class="option">Portugal</li><li data-value="PR" class="option">Puerto Rico</li><li data-value="QA" class="option">Qatar</li><li data-value="RE" class="option">Reunion</li><li data-value="RO" class="option">Romania</li><li data-value="RU" class="option">Russia</li><li data-value="RW" class="option">Rwanda</li><li data-value="ST" class="option">São Tomé and Príncipe</li><li data-value="BL" class="option">Saint Barthélemy</li><li data-value="SH" class="option">Saint Helena</li><li data-value="KN" class="option">Saint Kitts and Nevis</li><li data-value="LC" class="option">Saint Lucia</li><li data-value="SX" class="option">Saint Martin (Dutch part)</li><li data-value="MF" class="option">Saint Martin (French part)</li><li data-value="PM" class="option">Saint Pierre and Miquelon</li><li data-value="VC" class="option">Saint Vincent and the Grenadines</li><li data-value="WS" class="option">Samoa</li><li data-value="SM" class="option">San Marino</li><li data-value="SA" class="option">Saudi Arabia</li><li data-value="SN" class="option">Senegal</li><li data-value="RS" class="option">Serbia</li><li data-value="SC" class="option">Seychelles</li><li data-value="SL" class="option">Sierra Leone</li><li data-value="SG" class="option">Singapore</li><li data-value="SK" class="option">Slovakia</li><li data-value="SI" class="option">Slovenia</li><li data-value="SB" class="option">Solomon Islands</li><li data-value="SO" class="option">Somalia</li><li data-value="ZA" class="option">South Africa</li><li data-value="GS" class="option">South Georgia/Sandwich Islands</li><li data-value="KR" class="option">South Korea</li><li data-value="SS" class="option">South Sudan</li><li data-value="ES" class="option">Spain</li><li data-value="LK" class="option">Sri Lanka</li><li data-value="SD" class="option">Sudan</li><li data-value="SR" class="option">Suriname</li><li data-value="SJ" class="option">Svalbard and Jan Mayen</li><li data-value="SE" class="option">Sweden</li><li data-value="CH" class="option">Switzerland</li><li data-value="SY" class="option">Syria</li><li data-value="TW" class="option">Taiwan</li><li data-value="TJ" class="option">Tajikistan</li><li data-value="TZ" class="option">Tanzania</li><li data-value="TH" class="option">Thailand</li><li data-value="TL" class="option">Timor-Leste</li><li data-value="TG" class="option">Togo</li><li data-value="TK" class="option">Tokelau</li><li data-value="TO" class="option">Tonga</li><li data-value="TT" class="option">Trinidad and Tobago</li><li data-value="TN" class="option">Tunisia</li><li data-value="TR" class="option">Turkey</li><li data-value="TM" class="option">Turkmenistan</li><li data-value="TC" class="option">Turks and Caicos Islands</li><li data-value="TV" class="option">Tuvalu</li><li data-value="UG" class="option">Uganda</li><li data-value="UA" class="option">Ukraine</li><li data-value="AE" class="option">United Arab Emirates</li><li data-value="GB" class="option">United Kingdom (UK)</li><li data-value="US" class="option selected">United States (US)</li><li data-value="UM" class="option">United States (US) Minor Outlying Islands</li><li data-value="UY" class="option">Uruguay</li><li data-value="UZ" class="option">Uzbekistan</li><li data-value="VU" class="option">Vanuatu</li><li data-value="VA" class="option">Vatican</li><li data-value="VE" class="option">Venezuela</li><li data-value="VN" class="option">Vietnam</li><li data-value="VG" class="option">Virgin Islands (British)</li><li data-value="VI" class="option">Virgin Islands (US)</li><li data-value="WF" class="option">Wallis and Futuna</li><li data-value="EH" class="option">Western Sahara</li><li data-value="YE" class="option">Yemen</li><li data-value="ZM" class="option">Zambia</li><li data-value="ZW" class="option">Zimbabwe</li></ul></div>
                                </div>
                            </div>
                            <div class="shippingFormElements">
                                <label>State</label>
                                <div class="sfeItem">
                                    <select name="state" style="display: none;">
                                        <option value="">Select a state</option>
                                        <option value="AL">Alabama</option><option value="AK">Alaska</option><option value="AZ">Arizona</option><option value="AR">Arkansas</option><option value="CA">California</option><option value="CO">Colorado</option><option value="CT">Connecticut</option><option value="DE">Delaware</option><option value="DC">District Of Columbia</option><option value="FL">Florida</option><option value="GA">Georgia</option><option value="HI">Hawaii</option><option value="ID">Idaho</option><option value="IL">Illinois</option><option value="IN">Indiana</option><option value="IA">Iowa</option><option value="KS">Kansas</option><option value="KY">Kentucky</option><option value="LA">Louisiana</option><option value="ME">Maine</option><option value="MD">Maryland</option><option value="MA">Massachusetts</option><option value="MI">Michigan</option><option value="MN">Minnesota</option><option value="MS">Mississippi</option><option value="MO">Missouri</option><option value="MT">Montana</option><option value="NE">Nebraska</option><option value="NV">Nevada</option><option value="NH">New Hampshire</option><option value="NJ">New Jersey</option><option value="NM">New Mexico</option><option value="NY">New York</option><option value="NC">North Carolina</option><option value="ND">North Dakota</option><option value="OH">Ohio</option><option value="OK">Oklahoma</option><option value="OR">Oregon</option><option value="PA">Pennsylvania</option><option value="RI">Rhode Island</option><option value="SC">South Carolina</option><option value="SD">South Dakota</option><option value="TN">Tennessee</option><option value="TX">Texas</option><option value="UT">Utah</option><option value="VT">Vermont</option><option value="VA">Virginia</option><option value="WA">Washington</option><option value="WV">West Virginia</option><option value="WI">Wisconsin</option><option value="WY">Wyoming</option><option value="AA">Armed Forces (AA)</option><option value="AE">Armed Forces (AE)</option><option value="AP">Armed Forces (AP)</option>
                                    </select><div class="nice-select" tabindex="0"><span class="current">Select a state</span><ul class="list"><li data-value="" class="option selected">Select a state</li><li data-value="AL" class="option">Alabama</li><li data-value="AK" class="option">Alaska</li><li data-value="AZ" class="option">Arizona</li><li data-value="AR" class="option">Arkansas</li><li data-value="CA" class="option">California</li><li data-value="CO" class="option">Colorado</li><li data-value="CT" class="option">Connecticut</li><li data-value="DE" class="option">Delaware</li><li data-value="DC" class="option">District Of Columbia</li><li data-value="FL" class="option">Florida</li><li data-value="GA" class="option">Georgia</li><li data-value="HI" class="option">Hawaii</li><li data-value="ID" class="option">Idaho</li><li data-value="IL" class="option">Illinois</li><li data-value="IN" class="option">Indiana</li><li data-value="IA" class="option">Iowa</li><li data-value="KS" class="option">Kansas</li><li data-value="KY" class="option">Kentucky</li><li data-value="LA" class="option">Louisiana</li><li data-value="ME" class="option">Maine</li><li data-value="MD" class="option">Maryland</li><li data-value="MA" class="option">Massachusetts</li><li data-value="MI" class="option">Michigan</li><li data-value="MN" class="option">Minnesota</li><li data-value="MS" class="option">Mississippi</li><li data-value="MO" class="option">Missouri</li><li data-value="MT" class="option">Montana</li><li data-value="NE" class="option">Nebraska</li><li data-value="NV" class="option">Nevada</li><li data-value="NH" class="option">New Hampshire</li><li data-value="NJ" class="option">New Jersey</li><li data-value="NM" class="option">New Mexico</li><li data-value="NY" class="option">New York</li><li data-value="NC" class="option">North Carolina</li><li data-value="ND" class="option">North Dakota</li><li data-value="OH" class="option">Ohio</li><li data-value="OK" class="option">Oklahoma</li><li data-value="OR" class="option">Oregon</li><li data-value="PA" class="option">Pennsylvania</li><li data-value="RI" class="option">Rhode Island</li><li data-value="SC" class="option">South Carolina</li><li data-value="SD" class="option">South Dakota</li><li data-value="TN" class="option">Tennessee</li><li data-value="TX" class="option">Texas</li><li data-value="UT" class="option">Utah</li><li data-value="VT" class="option">Vermont</li><li data-value="VA" class="option">Virginia</li><li data-value="WA" class="option">Washington</li><li data-value="WV" class="option">West Virginia</li><li data-value="WI" class="option">Wisconsin</li><li data-value="WY" class="option">Wyoming</li><li data-value="AA" class="option">Armed Forces (AA)</li><li data-value="AE" class="option">Armed Forces (AE)</li><li data-value="AP" class="option">Armed Forces (AP)</li></ul></div>
                                </div>
                            </div>
                            <div class="shippingFormElements">
                                <label>Zip Code</label>
                                <div class="sfeItem">
                                    <input type="text" name="zip_code" placeholder="Enter your zip code">
                                </div>
                            </div>
                            <button type="submit" class="ulinaBTN" name="Calculate" value="Calculate"><span>Calculate</span></button>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="cartCoupon">
                            <h3>Have A Coupon Code?</h3>
                            <input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="Write your Coupon Code">
                            <button type="submit" class="ulinaBTN" name="apply_coupon" value="Apply Code"><span>Apply Code</span></button>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="col-sm-12 cart_totals">
                            <table class="shop_table shop_table_responsive">
                                <tbody><tr class="cart-subtotal">
                                    <th>Subtotal</th>
                                    <td data-title="Subtotal">
                                        <div class="pi01Price" id="cart-total">
                                            <ins>{{ number_format($total) }}đ</ins>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="cart-shipping">
                                    <th>Shipping</th>
                                    <td data-title="Subtotal">
                                        <div class="pi01Price">
                                            <ins>30,000đ</ins>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="order-total">
                                    <th>Grand Total</th>
                                    <td data-title="Subtotal">
                                        <div class="pi01Price" id="grand-total">
                                            <ins>{{ number_format($total + 30000)}}đ</ins>
                                        </div>
                                    </td>
                                </tr>
                            </tbody></table>
                            <a href="{{ route('checkout') }}" class="checkout-button ulinaBTN">
                                <span>Proceed to checkout</span>
                            </a>
                            <p class="cartHints">Checkout with multiple address</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
</div>    
</div>

@endsection
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

<script>
$(document).ready(function(){
    $('.change-qty').click(function(e){
        e.preventDefault();

        let button = $(this);
        let form = button.closest('form');
        let action = button.data('action');
        let productId = form.find('input[name="product_id"]').val();

        $.ajax({
            url: '{{ route("cart.update") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                product_id: productId,
                quantity: action
            },
            success: function(res){
                if(res.success){
                    form.find('.quantity-num').text(res.new_quantity);
                    form.closest('tr').find('.product-subtotal ins').text(res.item_total + 'đ');
                    $('#cart-total ins').text(res.cart_total + 'đ');
                    $('#grand-total').text(res.grand_total + 'đ');
                } else {
                    alert('Không cập nhật được sản phẩm');
                }
            },
            error: function(err){
                alert('Đã xảy ra lỗi.');
                console.log(err);
            }
        });
    });
});

</script>

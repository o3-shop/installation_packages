<?php
/**
 * This file is part of O3-Shop Paypal module.
 *
 * O3-Shop is free software: you can redistribute it and/or modify  
 * it under the terms of the GNU General Public License as published by  
 * the Free Software Foundation, version 3.
 *
 * O3-Shop is distributed in the hope that it will be useful, but 
 * WITHOUT ANY WARRANTY; without even the implied warranty of 
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU 
 * General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with O3-Shop.  If not, see <http://www.gnu.org/licenses/>
 *
 * @copyright  Copyright (c) 2022 OXID eSales AG (https://www.oxid-esales.com)
 * @copyright  Copyright (c) 2022 O3-Shop (https://www.o3-shop.com)
 * @license    https://www.gnu.org/licenses/gpl-3.0  GNU General Public License 3 (GPLv3)
 */

return [
    'adminUser' => [
        "userId" => "admin",
        "userLoginName" => "admin",
        "userPassword" => "admin",
        "userName" => "John",
        "userLastName" => "Doe",
    ],
    'userPassword' => 'useruser',
    'defaultUserName' => 'defaultuser@oxid-esales.dev',
    'defaultUserFirstName' => 'UserName',
    'userName' => 'paypaluser@oxid-esales.dev',
    'demoUserName'  => 'user@oxid-esales.com',
    'details' => [
        'firstname' => 'TestUserName',
        'lastname' => 'TestUserSurname',
        'oxcity' => 'Hamburg',
        'oxstreet' => 'Hauptstr.',
        'oxstreetnr' => '13',
        'oxzip' => '22547'
    ],
    'totalordersum_ecsdetails' => 149.5,
    'totalordersum_ecswithshipping' => 33.80,
    // This user will be created and will be available in ce|pe|ee demodata
    'user' => [
        'oxid'         => 'pptestuser',
        'oxactive'     => 1,
        'oxrights'     => 'user',
        'oxshopid'     => 1,
        'oxusername'   => 'testing_account@oxid-esales.dev',
        'oxpassword'   => '$2y$10$lj90Q/CaB0IB8PZemQW4Xu1/EWvAhkW9SOZ1Sr3JBx8DOmd3qz7bu',
        'oxfname'      => 'User',
        'oxlname'      => 'User',
        'oxstreet'     => 'Street',
        'oxstreetnr'   => 'Street Number',
        'oxzip'        => 'ZIP',
        'oxcity'       => 'City',
        'oxcountryid'  => 'a7c40f631fc920687.20179984',
        'oxboni'       => '600',
        'oxcreate'     => date("Y-m-d H:i:s"),
        'oxregister'   => date("Y-m-d H:i:s"),
        'oxbirthdate'  => date("Y-m-d"),
        'oxpasssalt'   => ''
    ],
    'usergroups' => [
        'OXID'       => 'pptestusergroups',
        'oxobjectid' => 'pptestuser',
        'oxgroupsid' => 'oxidcustomer'
    ],
    // This product is available in ce|pe|ee demodata
    'product' => [
        'id' => 'dc5ffdf380e15674b56dd562a7cb6aec',
        'title' => 'Kuyichi leather belt JEVER',
        'amount' => 4,
        'price' => '119,60 €',
        'bruttoprice_single' => '29.9',
        'nettoprice_single' => '25.13'
    ],
    'parent' => [
        'id' => '531b537118f5f4d7a427cdb825440922',
        'maxNettoPrice' => 83.95,
        'maxBruttoPrice' => 99.9,
        'minNettoPrice' => 78.07,
        'minBruttoPrice' => 92.9,
    ],
    'variant' => [
        'id' => '6b6e0bb9f2b8b5f070f91593073b4555',
        'bruttoprice' => '99.9',
        'nettoprice'  => '83.95' 
    ],
    'alternate_variant' => [
        'id' => '6b65295a7fe5fa6faaa2f0ac3f9b0f80',
        'bruttoprice' => '109.9',
        'nettoprice'  => '92.35'
    ],
    'shipping' => [
        'standard' => 'oxidstandard'
    ],
    'payment_id' => 'oxidpaypal',
    'payment_id_other' => 'oxidcashondel',
    'oxvoucherseries' => [
        'OXID'               => 'ppgserie1',
        'OXSERIENR'          => 'ppgserie1',
        'OXDISCOUNT'         => '10',
        'OXDISCOUNTTYPE'     => 'absolute',
        'OXBEGINDATE'        => '2000-01-01',
        'OXENDDATE'          => '2050-12-31',
        'OXSERIEDESCRIPTION' => 'PPG test voucher',
        'OXALLOWOTHERSERIES' => 1
    ],
    'oxvoucherseries_ee' => [
        'OXID'               => 'ppgserie1',
        'OXMAPID'            => '6543',
        'OXSERIENR'          => 'ppgserie1',
        'OXDISCOUNT'         => '10',
        'OXDISCOUNTTYPE'     => 'absolute',
        'OXBEGINDATE'        => '2000-01-01',
        'OXENDDATE'          => '2050-12-31',
        'OXSERIEDESCRIPTION' => 'PPG test voucher',
        'OXALLOWOTHERSERIES' => 1
    ],
    'oxvoucherseries2shop' => [
        'OXSHOPID'           => '1',
        'OXMAPOBJECTID'      => '6543'
    ],
    'oxvouchers'        => [
        'OXID'             => 'ppgvoucher1',
        'OXVOUCHERNR'      => 'ppgvoucher1',
        'OXVOUCHERSERIEID' => 'ppgserie1'
    ],
    'oxdiscount'       => [
        'OXID'         => 'ppgdiscount',
        'OXSHOPID'     => 1,
        'OXACTIVE'     => 1,
        'OXACTIVEFROM' => '2020-12-01 00:00:00',
        'OXACTIVETO'   => '2099-01-01 00:00:00',
        'OXTITLE'      => 'ppgdiscount for product',
        'OXTITLE_1'    => 'ppgdiscount for product',
        'OXAMOUNT'     => 1,
        'OXAMOUNTTO'   => 999999,
        'OXADDSUMTYPE' => 'abs',
        'OXADDSUM'     => 9.9
    ],
    'oxdiscount_ee'     => [
        'OXID'         => 'ppgdiscount',
        'OXMAPID'      => '7654',
        'OXSHOPID'     => 1,
        'OXACTIVE'     => 1,
        'OXACTIVEFROM' => '2020-12-01 00:00:00',
        'OXACTIVETO'   => '2099-01-01 00:00:00',
        'OXTITLE'      => 'ppgdiscount for product',
        'OXTITLE_1'    => 'ppgdiscount for product',
        'OXAMOUNT'     => 1,
        'OXAMOUNTTO'   => 999999,
        'OXADDSUMTYPE' => 'abs',
        'OXADDSUM'     => 9.9
    ],
    'oxdiscount2shop'   => [
        'OXSHOPID'      => '1',
        'OXMAPOBJECTID' => '7654'
    ],
    'oxobject2discount' => [
        'OXID'         => 'ppgdiscountrelation',
        'OXDISCOUNTID' => 'ppgdiscount',
        'OXOBJECTID'   => 'dc5ffdf380e15674b56dd562a7cb6aec',
        'OXTYPE'       => 'oxarticles'
    ]
];

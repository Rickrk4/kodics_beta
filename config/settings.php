<?php return array (
  'test' => false,
  'require_authentication' => true,
  'registration' => true,
  'allow_user_registration' => false,
  'jobKey' => 376,
  'scrapers' => 
  array (
    0 => 'App\\Scrapers\\LocalScraper',
    1 => 'App\\Scrapers\\NameScraper',
    2 => 'App\\Scrapers\\LastHope',
  ),
  'comics_dir' => '/home/riccardo/comics',
  'use_online_data' => true,
  'admin_credentials' => 
  array (
    'username' => 'admin',
    'password' => 'adminadmin',
  ),
  'scan_frequency' => 
  array (
    'value' => 'monthly',
    'values' => 
    array (
      0 => 'monthly',
      1 => 'weekly',
      2 => 'daily',
    ),
  ),
);
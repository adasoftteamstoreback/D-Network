<?php defined ('BASEPATH') or exit ( 'No direct script access allowed' );

$route ['news/(:any)/(:any)']    = 'news/news/News_controller/index/$1/$2';
$route ['newslist']                   = 'news/news/News_controller/FSvCNEWListPage';
$route ['newsDataTable']              = 'news/news/News_controller/FSvCNEWDataTable';
$route ['newsPageAdd']                = 'news/news/News_controller/FSvCNEWPageAdd';
$route ['newsPageAddinfo1']           = 'news/news/News_controller/FSvCNEWPInfomationTab1';
$route ['newsEventAdd']               = 'news/news/News_controller/FSoCNEWAddEvent';
$route ['newsEventDelete']            = 'news/news/News_controller/FSoCNEWDeleteEvent';
$route ['newsPageEdit']               = 'news/news/News_controller/FSvCNEWEditPage';
$route ['newsEventEdit']              = 'news/news/News_controller/FSoCNEWEditEvent';

$route ['newsEventSendNoti']          = 'news/news/News_controller/FSoCNEWEventSendNoti';
$route ['ReadNews/(:any)/(:any)']     = 'news/news/News_controller/FSoCNEWEventReadNews/$1/$2';

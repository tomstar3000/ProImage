<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

/*$route['default_controller'] = "welcome";
$route['test'] = "welcome/test";*/

/*
 * photographer routes
 */
$route['(:any)'] = "Photographer/home/$1";
$route['(:any)/home'] = "Photographer/home/$1";

$route['(:any)/gallery'] = "Photographer/gallery/$1";

$route['(:any)/contact-us'] = "Photographer/contactUs/$1";

/*
 * event routes
 */
$route['(:any)/(:any)'] = "PhotographerEvent/welcome/$1/$2";
$route['(:any)/(:any)/welcome'] = "PhotographerEvent/welcome/$1/$2";


/*
 * authorized event routes
 */
$route['(:any)/(:any)/images'] = "AuthorizedPhotographerEvent/images/$1/$2";
$route['(:any)/(:any)/checkout-complete'] = "AuthorizedPhotographerEvent/checkoutComplete/$1/$2";
$route['(:any)/(:any)/logout'] = "AuthorizedPhotographerEvent/logout/$1/$2";

$route['404_override'] = '';


/* End of file routes.php */
/* Location: ./application/config/routes.php */
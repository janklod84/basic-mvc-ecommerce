<?php

  define('DEBUG', true); // set debug to false for production

  define('DB_NAME', 'live'); // database name
  define('DB_USER', 'root'); // database user
  define('DB_PASSWORD', ''); // database password
  define('DB_HOST', 'localhost'); // database host *** use IP address to avoid DNS lookup

  define('DEFAULT_CONTROLLER', 'Home'); // default controller if there isn't one defined in the url
  define('DEFAULT_LAYOUT', 'default'); // if no layout is set in the controller use this layout.

  define('PROOT', '/'); // set this to '/' for a live server.
  define('VERSION','0.27'); // release version this can be used to display version or version assets like css and js files useful for fighting cached browser files

  define('SITE_TITLE', 'Ruah MVC Framework'); // This will be used if no site title is set
  define('MENU_BRAND', 'RUAH'); //This is the Brand text in the menu

  define('CURRENT_USER_SESSION_NAME', 'fEueuD2Z5fOknVeY6Qp1'); //session name for logged in user
  define('REMEMBER_ME_COOKIE_NAME', 'CKfKmnZ3bzxSeL5M9g1x'); // cookie name for logged in user remember me
  define('REMEMBER_ME_COOKIE_EXPIRY', 2592000); // time in seconds for remember me cookie to live (30 days)

  define('CART_COOKIE_NAME','zpe8Nox8P9I3dxnJ5bFC');
  define('CART_COOKIE_EXPIRY',1209600);

  define('ACCESS_RESTRICTED', 'Restricted'); //controller name for the restricted redirect

  ################# Gateway Settings #######################################
  define('GATEWAY','stripe'); // could use stripe, paypal
  define('STRIPE_PUBLIC','pk_test_UgjEw1q4QsEClQOnyRE2C0r8');
  define('STRIPE_PRIVATE','sk_test_s38Ykf9pzkwx7kGzLuDH7qSJ');

<?php
//Capabilities for the block
$capabilities = array(

      'block/sits_v2:myaddinstance' => array(
      'riskbitmask' => RISK_PERSONAL,
      'captype' => 'read',
      'contextlevel' => CONTEXT_COURSE,
      'legacy' => array(
        'guest'          => CAP_PREVENT,
        'student'        => CAP_PREVENT,
        'teacher'        => CAP_ALLOW,
        'editingteacher' => CAP_ALLOW
        )
    ),
      'block/sits_v2:addinstance' => array(
      'riskbitmask' => RISK_PERSONAL,
      'captype' => 'read',
      'contextlevel' => CONTEXT_USER,
      'legacy' => array(
        'guest'          => CAP_PREVENT,
        'student'        => CAP_PREVENT,
        'teacher'        => CAP_ALLOW,
        'editingteacher' => CAP_ALLOW
        )
    ),
); 

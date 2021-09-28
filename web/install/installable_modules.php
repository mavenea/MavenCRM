<?php
//You can skip the installation of any of the below modules.
//list of vte core modules and other modules created by vte
//installation page can be used to select which modules to install
//this is an advanced feature - combination of modules might be needed. For example : Processes module have links to Messages, Employees,et. Need more testing
$installable_modules = array(
    'PrivacyPolicy',
    'GDPR',
    'CalendarTracking', //tracking an activity
    'ModComments',  //Talks module
    'ConfProducts', // Configurable products -end here. require testing
    'VteSync', //sync data with other systems like salesforce
    'Sms',
    'Fax',
    'Myfiles',
    'MyNotes',

);
//those moduels cannot be selectable for installation. they will be installed automatically.
$non_installable_modules = array(
    'Morphsuit',
    'ModNotifications',
    'Conditionals',
    'FieldFormulas',  //- Add custom equations to custom fields keep it in core it is a useful module
    'ChangeLog', //audit log is essential module
    'Area',  // non-entity
    'Popup',  // non-entity,
    'Processes',
    'Messages',
    'MailScanner',
    'Services', // - related to Processes
    'Timecards', // - related to Processes
    'Assets', // - related to Processes
    'Charts',
    'WSAPP',
    'PDFMaker',
    'ProjectMilestone', // - related to Processes
    'ProjectTask', // - related to Processes
    'ProjectPlan', // - related to Processes
    'Webforms',
    'PBXManager', //ASTERISK PBX Manager // - related to Processes
    'Visitreport', // - related to Processes
    'ServiceContracts', // - related to Processes
    'Targets', // - related to Processes
    'Newsletter', // doe this depend on pdfmaker ? // - related to Processes
    'Transitions', //what is this module?
    'Geolocalization',
    'ProductLines', // - related to Processes
    'SLA',
    'Ddt', //delivery notes - related to Processes
    'JobOrder',
    'Employees' // related to users
);
//those moduels are useless or not required. dont install those
$ignore_modules = array(
    'M',        // its an empty module no idea what that is - just ignore
    'Mobile',   // same as M module just ignore no idea what that is - just ignore
    'Touch'
);

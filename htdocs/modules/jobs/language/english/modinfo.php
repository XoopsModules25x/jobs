<?php
// Module Info

// The name of this module
define("_MI_JOBS_NAME", "Job Listings");

define("_MI_JOBS_MENUADD", "Add a Job Listing");

// A brief description of this module
define("_MI_JOBS_DESC", "Job Listing Module");

// Names of blocks for this module (Not all module has blocks)
define("_MI_JOBS_BNAME", "Job Listing Block");
define("_MI_JOBS_BNAME_DESC", "Job Listing Block");

define("_MI_JOBS_BNAME2", "(Jobs) Company Logo Block");
define("_MI_JOBS_BNAME2_DESC", "Job Listing Block with Company logo");

define("_MI_JOBS_RES_BNAME", "Resume Listing Block");
define("_MI_JOBS_RES_BNAME_DESC", "A Block for Resume Listings.");
// Names of admin menu items
define("_MI_JOBS_ADMENU1", "Type Management");
define("_MI_JOBS_ADMENU2", "Categories");
define("_MI_JOBS_ADMENU3", "Permissions");
define("_MI_JOBS_ADMENU4", "Preferences");
define("_MI_JOBS_ADMENU5", "Docs");
define("_MI_JOBS_ADMENU6", "Companies");
define("_MI_JOBS_CONFSAVE", "Configuration saved");
define("_MI_JOBS_CANPOST", "Anonymous user can post Job Listings :");
define("_MI_JOBS_PERPAGE", "Job Listings per page :");
define("_MI_JOBS_RES_PERPAGE", "Resumes per page :");
define("_MI_JOBS_MONEY", "Currency symbol :");
define("_MI_JOBS_KOIVI", "Use Koivi Editor :");
define("_MI_JOBS_NUMNEW", "Number of new Job Listings :");
define("_MI_JOBS_MODERAT", "Moderate Job Listings :");
define("_MI_JOBS_RES_MODERAT", "Moderate Resumes :");
define("_MI_JOBS_DAYS", "Job listing Duration :");
define("_MI_JOBS_RES_DAYS", "Resume listing Duration :");
define("_MI_JOBS_RES_SIZE", "Resume Size :");
define("_MI_JOBS_MAXIIMGS", "Maximum Photo Size :");
define("_MI_JOBS_MAXWIDE", "Maximum Photo Width :");
define("_MI_JOBS_MAXHIGH", "Maximum Photo Height :");
define("_MI_JOBS_TIMEANN", "Job Listing duration :");
define("_MI_JOBS_RES_LIMIT", "Resume duration :");
define("_MI_JOBS_INBYTES", "in bytes");
define("_MI_JOBS_INPIXEL", "in pixels");
define("_MI_JOBS_INDAYS", "in days");
define("_MI_JOBS_TYPEBLOC", "Type of Block :");
define("_MI_JOBS_JOBRAND", "Random Job Listing");
define("_MI_JOBS_LASTTEN", "Last 10 Job Listings");
define("_MI_JOBS_NEWTIME", "New Job Listings from :");
define("_MI_JOBS_DISPLPRICE", "Display price :");
define("_MI_JOBS_DISPLPRICE2", "Display price :");
define("_MI_JOBS_INTHISCAT", "in this category");
define("_MI_JOBS_DISPLSUBCAT", "Display subcategories :");
define("_MI_JOBS_ONHOME", "on the Front Page of Module");
define("_MI_JOBS_NBDISPLSUBCAT", "Number of subcategories to show :");
define("_MI_JOBS_IF", "if");
define("_MI_JOBS_ISAT", "is at");
define("_MI_JOBS_VIEWNEWCLASS", "Show new Job Listings :");
define("_MI_JOBS_ORDREALPHA", "Sort alphabetically");
define("_MI_JOBS_ORDREPERSO", "Personalised Order");
define("_MI_JOBS_ORDRECLASS", "Category Order :");

////////////////////////////////////////////////////////
//added below for version 2.0
////////////////////////////////////////////////////////


define('_MI_GPERM_G_ADD', "Can add");
define('_MI_CAT2GROUPDESC', "Check categories which you allow to access");
define('_MI_GROUPPERMDESC', "Select group(s) allowed to submit listings.");
define('_MI_GROUPPERM', 'Submit Permissions');
define('_MI_JOBS_SUBMITFORM', 'Jobs Submit Permissions');
define('_MI_JOBS_SUBMITFORM_DESC', 'Select, who can submit jobs');
define('_MI_JOBS_RESUMEFORM', 'Resume Submit Permissions');
define('_MI_JOBS_VIEWFORM', 'View Jobs Permissions');
define('_MI_JOBS_VIEW_RESUMEFORM', 'View Resume Permissions');
define('_MI_JOBS_RESUMEFORM_DESC', 'Select, who can submit resumes');
define('_MI_JOBS_VIEWFORM_DESC', 'Select, who can view jobs');
define('_MI_JOBS_VIEW_RESUMEFORM_DESC', 'Select, who can view resumes');
define('_MI_JOBS_SUPPORT', 'Support this software');
define('_MI_JOBS_OP', 'Read my opinion');
define('_MI_JOBS_PREMIUM', 'Jobs Premium');
define('_MI_JOBS_PREMIUM_DESC', 'Who can select days listing will last');

// Notification event descriptions and mail templates


define ('_MI_JOBS_CATEGORY_NOTIFY', 'Category');
define ('_MI_JOBS_CATEGORY_NOTIFYDSC', 'Notification options that apply to the current category.');
define ('_MI_JOBS_NOTIFY', 'Listing');
define ('_MI_JOBS_NOTIFYDSC', 'Notification options that apply to the current listing.');
define ('_MI_JOBS_GLOBAL_NOTIFY', 'Whole Module ');
define ('_MI_JOBS_GLOBAL_NOTIFYDSC', 'Global advert notification options.');

//event

define ('_MI_JOBS_NEWPOST_NOTIFY', 'New Job Listing');
define ('_MI_JOBS_NEWPOST_NOTIFYCAP', 'Notify me of new Job listings in the current category.');
define ('_MI_JOBS_NEWPOST_NOTIFYDSC', 'Receive notification when a new Job listing is posted to the current category.');
define ('_MI_JOBS_NEWPOST_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE}: auto-notify : New Job listing in category');
define ('_MI_JOBS_VALIDATE_NEWPOST_NOTIFY', 'New Job Listing');
define ('_MI_JOBS_VALIDATE_NEWPOST_NOTIFYCAP', 'Notify me of new Job listings in the current category.');
define ('_MI_JOBS_VALIDATE_NEWPOST_NOTIFYDSC', 'Receive notification when a new Job listing is posted to the current category.');
define ('_MI_JOBS_VALIDATE_NEWPOST_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE}: auto-notify : New Job listing in category');
define ('_MI_JOBS_UPDATE_NEWPOST_NOTIFY', 'Listing Updated');
define ('_MI_JOBS_UPDATE_NEWPOST_NOTIFYCAP', 'Notify me of updated Job listings in the current category.');
define ('_MI_JOBS_UPDATE_NEWPOST_NOTIFYDSC', 'Receive notification when a Job listing is updated in the current category.');
define ('_MI_JOBS_UPDATE_NEWPOST_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE}: auto-notify : New Job listing in category');
define ('_MI_JOBS_DELETE_NEWPOST_NOTIFY', 'Job Listing Deleted');
define ('_MI_JOBS_DELETE_NEWPOST_NOTIFYCAP', 'Notify me of new Job listings in the current category.');
define ('_MI_JOBS_DELETE_NEWPOST_NOTIFYDSC', 'Receive notification when a Job listing is deleted from the current category.');
define ('_MI_JOBS_DELETE_NEWPOST_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE}: auto-notify : New Job listing in category');
define ('_MI_JOBS_GLOBAL_NEWPOST_NOTIFY', 'New Job Listing');
define ('_MI_JOBS_GLOBAL_NEWPOST_NOTIFYCAP', 'Notify me of new Job listings in all categories.');
define ('_MI_JOBS_GLOBAL_NEWPOST_NOTIFYDSC', 'Receive notification when a new Job listing is posted to all categories.');
define ('_MI_JOBS_GLOBAL_NEWPOST_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE}: auto-notify : New Job listing in category');
define ('_MI_JOBS_GLOBAL_VALIDATE_NEWPOST_NOTIFY', 'New Job Listing');
define ('_MI_JOBS_GLOBAL_VALIDATE_NEWPOST_NOTIFYCAP', 'Notify me of new Job listings in all categories.');
define ('_MI_JOBS_GLOBAL_VALIDATE_NEWPOST_NOTIFYDSC', 'Receive notification when a new Job listing is posted to all categories.');
define ('_MI_JOBS_GLOBAL_VALIDATE_NEWPOST_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE}: auto-notify : New Job listing in category');
define ('_MI_JOBS_GLOBAL_UPDATE_NEWPOST_NOTIFY', 'Job Listing Updated');
define ('_MI_JOBS_GLOBAL_UPDATE_NEWPOST_NOTIFYCAP', 'Notify me of updated Job listings in all categories.');
define ('_MI_JOBS_GLOBAL_UPDATE_NEWPOST_NOTIFYDSC', 'Receive notification when a Job listing is updated in all categories.');
define ('_MI_JOBS_GLOBAL_UPDATE_NEWPOST_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE}: auto-notify : Job Listing updated in categories');
define ('_MI_JOBS_GLOBAL_DELETE_NEWPOST_NOTIFY', 'Listing Deleted');
define ('_MI_JOBS_GLOBAL_DELETE_NEWPOST_NOTIFYCAP', 'Notify me of deleted Job listings in all categories.');
define ('_MI_JOBS_GLOBAL_DELETE_NEWPOST_NOTIFYDSC', 'Receive notification when a Job listing is deleted in all categories.');
define ('_MI_JOBS_GLOBAL_DELETE_NEWPOST_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE}: auto-notify : Job Listing deleted in categories');

//resumes
define ('_MI_JOBS_RES_CATEGORY_NOTIFY', 'Category');
define ('_MI_JOBS_RES_CATEGORY_NOTIFYDSC', 'Notification options that apply to the current category.');
define ('_MI_JOBS_RES_NOTIFY', 'Listing');
define ('_MI_JOBS_RES_NOTIFYDSC', 'Notification options that apply to the current listing.');
define ('_MI_JOBS_RES_GLOBAL_NOTIFY', 'All Resume Listings ');
define ('_MI_JOBS_RES_GLOBAL_NOTIFYDSC', 'Global advert notification options.');

//event

define ('_MI_JOBS_RES_NEWPOST_NOTIFY', 'New Resume Listing');
define ('_MI_JOBS_RES_NEWPOST_NOTIFYCAP', 'Notify me of new Resume listings in the current category.');
define ('_MI_JOBS_RES_NEWPOST_NOTIFYDSC', 'Receive notification when a new listing is posted to the current category.');
define ('_MI_JOBS_RES_NEWPOST_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE}: auto-notify : New listing in category');
define ('_MI_JOBS_RES_VALIDATE_NEWPOST_NOTIFY', 'New Listing');
define ('_MI_JOBS_RES_VALIDATE_NEWPOST_NOTIFYCAP', 'Notify me of new listings in the current category.');
define ('_MI_JOBS_RES_VALIDATE_NEWPOST_NOTIFYDSC', 'Receive notification when a new listing is posted to the current category.');
define ('_MI_JOBS_RES_VALIDATE_NEWPOST_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE}: auto-notify : New listing in category');
define ('_MI_JOBS_RES_UPDATE_NEWPOST_NOTIFY', 'Listing Updated');
define ('_MI_JOBS_RES_UPDATE_NEWPOST_NOTIFYCAP', 'Notify me of updated listings in the current category.');
define ('_MI_JOBS_RES_UPDATE_NEWPOST_NOTIFYDSC', 'Receive notification when an listing is updated in the current category.');
define ('_MI_JOBS_RES_UPDATE_NEWPOST_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE}: auto-notify : New listing in category');
define ('_MI_JOBS_RES_DELETE_NEWPOST_NOTIFY', 'Listing Deleted');
define ('_MI_JOBS_RES_DELETE_NEWPOST_NOTIFYCAP', 'Notify me of new listings in the current category.');
define ('_MI_JOBS_RES_DELETE_NEWPOST_NOTIFYDSC', 'Receive notification when an listing is deleted from the current category.');
define ('_MI_JOBS_RES_DELETE_NEWPOST_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE}: auto-notify : New listing in category');
define ('_MI_JOBS_RES_GLOBAL_NEWPOST_NOTIFY', 'New Resume Listing');
define ('_MI_JOBS_RES_GLOBAL_NEWPOST_NOTIFYCAP', 'Notify me of new Resume listings in all categories.');
define ('_MI_JOBS_RES_GLOBAL_NEWPOST_NOTIFYDSC', 'Receive notification when a new listing is posted to all categories.');
define ('_MI_JOBS_RES_GLOBAL_NEWPOST_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE}: auto-notify : New listing in category');
define ('_MI_JOBS_RES_GLOBAL_VALIDATE_NEWPOST_NOTIFY', 'New Listing');
define ('_MI_JOBS_RES_GLOBAL_VALIDATE_NEWPOST_NOTIFYCAP', 'Notify me of new listings in all categories.');
define ('_MI_JOBS_RES_GLOBAL_VALIDATE_NEWPOST_NOTIFYDSC', 'Receive notification when a new listing is posted to all categories.');
define ('_MI_JOBS_RES_GLOBAL_VALIDATE_NEWPOST_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE}: auto-notify : New listing in category');
define ('_MI_JOBS_RES_GLOBAL_UPDATE_NEWPOST_NOTIFY', 'Listing Updated');
define ('_MI_JOBS_RES_GLOBAL_UPDATE_NEWPOST_NOTIFYCAP', 'Notify me of updated listings in all categories.');
define ('_MI_JOBS_RES_GLOBAL_UPDATE_NEWPOST_NOTIFYDSC', 'Receive notification when an listing is updated in all categories.');
define ('_MI_JOBS_RES_GLOBAL_UPDATE_NEWPOST_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE}: auto-notify : Listing updated in categories');
define ('_MI_JOBS_RES_GLOBAL_DELETE_NEWPOST_NOTIFY', 'Listing Deleted');
define ('_MI_JOBS_RES_GLOBAL_DELETE_NEWPOST_NOTIFYCAP', 'Notify me of deleted listings in all categories.');
define ('_MI_JOBS_RES_GLOBAL_DELETE_NEWPOST_NOTIFYDSC', 'Receive notification when an listing is deleted in all categories.');
define ('_MI_JOBS_RES_GLOBAL_DELETE_NEWPOST_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE}: auto-notify : Listing deleted in categories');

define("_MI_JOBS_CSORT_ORDER", "Category Default Sort Order");
define("_MI_JOBS_LSORT_ORDER", "Listing Default Sort Order");
define("_MI_JOBS_ORDER_TITLE", "Sort listings by title");
define("_MI_JOBS_ORDER_COMPANY", "Sort listings by company");
define("_MI_JOBS_ORDER_TOWN", "Sort listings by town");
define("_MI_JOBS_ORDER_EXP", "Sort listings by experience");
define("_MI_JOBS_ORDER_DATE", "Sort listings by date (default)");
define("_MI_JOBS_ORDER_POP", "Sort listings by hits");
define("_MI_JOBS_RES_SHOW", "Show Resumes");
define("_MI_JOBS_SHOW_COMPANY", "Show Company");
define("_MI_JOBS_SHOW_STATE", "Use the State field");
define("_MI_JOBS_MULTIPLE_SUBMITTERS", "Allow multiple submitters per Company");

define("_MI_JOBS_NOT_PREMIUM", "Number of Pictures - Not Premium");
define("_MI_JOBS_NOT_PREMIUM_DESC", "Number of pictures a non-Premium user can have in his page");

define("_MI_JOBS_NUMBPICT_TITLE", "Number of Pictures - Premium");
define("_MI_JOBS_NUMBPICT_DESC", "Number of pictures a Premium user can have in his page");

define("_MI_JOBS_SMNAME1", "Submit");
define("_MI_JOBS_THUMW_TITLE", "Thumb Width");
define("_MI_JOBS_THUMBW_DESC", "Thumbnails width in pixels<br />This means your picture thumbnail will have<br />at most this size in width<br />All proportions are maintained");
define("_MI_JOBS_THUMBH_TITLE", "Thumb Height");
define("_MI_JOBS_THUMBH_DESC", "Thumbnails Height in pixels<br />This means your picture thumbnail will have<br />at most this size in height<br />All proportions are maintained");
define("_MI_JOBS_RESIZEDW_TITLE", "Resized picture width");
define("_MI_JOBS_RESIZEDW_DESC", "Resized picture width in pixels<br />This means your picture will have<br />at most this size in width<br />All proportions are maintained<br /> The original picture if bigger than this size will <br />be resized so it wont break your template");
define("_MI_JOBS_RESIZEDH_TITLE", "Resized picture height");
define("_MI_JOBS_RESIZEDH_DESC", "Resized picture height in pixels<br />This means your picture will have<br />at most this size in height<br />All proportions are maintained<br /> The original picture if bigger than this size will <br />be resized so it wont break your template design");
define("_MI_JOBS_ORIGW_TITLE", "Max original picture width");
define("_MI_JOBS_ORIGW_DESC", "Maximum original picture width in pixels<br />This means user's original picture can't exceed <br />this size in height<br />or else it won't be uploaded");
define("_MI_JOBS_ORIGH_TITLE", "Max original picture height");
define("_MI_JOBS_ORIGH_DESC", "Maximum original picture height in pixels<br />This means user's original picture can't exceed <br />this size in height<br />or else it won't be uploaded");
define("_MI_JOBS_UPLOAD_TITLE", "Path Uploads");
define("_MI_JOBS_UPLOAD_DESC", "Path to your uploads directory<br />in linux should look like /var/www/uploads<br />in windows like C:/Program Files/www");
define("_MI_JOBS_LINKUPLOAD_TI", "Link to your uploads directory");
define("_MI_JOBS_LINKUPLOAD_DE", "This is the address of the root of your uploads <br />like http://www.yoursite.com/uploads");
define("_MI_JOBS_MAXFILEBYTES_T", "Max size in bytes");
define("_MI_JOBS_MAXFILEBYTES_D", "This the maximum size a file of your pictue can have in bytes <br />like 512000 for 500 KB");
define("_MI_JOBS_EDITOR", "Editor to use for adding listing:");
define("_MI_JOBS_LIST_EDITORS", "Select the editor to use.");
define("_MI_JOBS_LIGHTBOX", "Lightbox effects");
define("_MI_JOBS_LIGHTBOX_DESC", "Use the lightbox effects when viewing photos.");
define("_MI_JOBS_DBUPDATED", "The Database has been Updated");
define("_MI_JOBS_RES_EDITOR", "Editor to use for creating resume:");

//Added for 3.0 RC3
define("_MI_JOBS_ADMIN_MAIL", "Send copies of contact form to admin");
define("_MI_JOBS_ADMIN_MAIL_DESC", "Sends copy of the contact form to the admin with senders IP (to monitor spam)");

define("_MI_JOBS_USE_CAPTCHA", "Use Captcha");
//Added for 4.0

define("_MI_JOBS_INDEX_CODE", "Extra Index Page Code");
define("_MI_JOBS_INDEX_CODE_DESC", "Put your adsense or other code here");
define("_MI_JOBS_USE_INDEX_CODE", "Use Extra Index Page Code");
define("_MI_JOBS_USE_INDEX_CODE_DESC", "Put additional code between listings<br />on the index page<br />and the categories page.<br /><br />Banners, Adsense code, etc...");
define("_MI_JOBS_INDEX_CODE_PLACE", "Code will show in this place in the list ");
define("_MI_JOBS_INDEX_CODE_PLACE_DESC", "Ex. If you choose 4 there will be 4 listings before this code.<br /> Code will be displayed in the 5th slot.");
define("_MI_JOBS_USE_BANNER", "Use Xoops Banner Code");
define("_MI_JOBS_USE_BANNER_DESC", "Will allow you to insert xoopsbanners in between listings.<br />If you choose Yes<br />Do Not insert any code below");

define("_MI_JOBS_BNAME3", "Jobs Premium Block");
define("_MI_JOBS_BNAME3_DESC", "Job Block for Premium Listing");

// added for 4.1
// added for optional search
define("_MI_JOBS_OFFER_SEARCH", "Offer search within listings");
define("_MI_JOBS_OFFER_SEARCH_DESC", "Select yes to provide a search box");

// 4.2
define("_MI_JOBS_RESUME_SEARCH", "Offer search within Resumes");
define("_MI_JOBS_RESUME_SEARCH_DESC", "Select yes to provide a search box");
define("_MI_JOBS_RESUME_CODE", "Use Above code in the Resume Listings");
define("_MI_JOBS_RESUME_CODE_DESC", "Put additional code between listings<br />on the resume index page<br />and the resume categories page.<br /><br />Banners, Adsense code, etc...");

define("_MI_JOBS_RSORT_ORDER", "Resume Default Sort Order");

define("_MI_JOBS_RES_MODERAT_UP", "Moderate Updated Resume Listings");
define("_MI_JOBS_MODERAT_UP", "Moderate Updated Job Listings");
define("_MI_JOBS_RESUME_ONE", "Allow more than one Resume Listing");

// 4.3

define ('_MI_JOBS_COMPANY_NOTIFY', 'New Listing');
define ('_MI_JOBS_COMPANYCAT_NOTIFY', 'Company');
define ('_MI_JOBS_COMPANY_NOTIFYDSC', 'Notification options that apply to the current Company.');

define ('_MI_JOBS_COMPANY_NOTIFYCAP', 'Notify me of new listings posted by this Company.');
//define ('_MI_JOBS_COMPANY_NOTIFYDSC', 'Receive notification when a new listing is posted by this Company.');
define ('_MI_JOBS_COMPANY_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE}: auto-notify : New listing by Company');

define ('_MI_JOBS_RESUMES', 'Resumes');

// 4.3 RC2

define("_MI_JOBS_ADMENU7", "State/Region");
define("_MI_JOBS_COUNTRIES", "Will this module be used for more than one country? ");

// 4.4 Beta 1

define("_MI_JOBS_ADMENU8", "Jobs");
define("_MI_JOBS_ADMENU9", "Resumes");

// 4.4RC2
define("_MI_JOBS_NBJOBLISTING", "Number of job listings per page in the admin");
define("_MI_JOBS_NBRESLISTING","Number of resume listings per page in the admin");

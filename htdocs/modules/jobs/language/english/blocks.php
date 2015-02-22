<?php

$mydirname  = basename(dirname(dirname(dirname(__FILE__))));
$block_lang = '_MB_' . strtoupper($mydirname);
// Blocks

define("_MB_JOBS_TITLE", "Job Listings");
define("_MB_JOBS_ALLANN2", "View all Job Listings");

define($block_lang . "_TITLE2", "Resume Listings");
define($block_lang . "_ALL_LISTINGS", "View all Resumes");
define($block_lang . "_ADDNOW", "Add your Listing for FREE!");
define($block_lang . "_ADDRESNOW", "Add your Resume FREE!");
define($block_lang . "_TITLE3", "Jobs Company Logo Block");

define($block_lang . "_DISP", "Display");
define($block_lang . "_LISTINGS", "Listings");
define($block_lang . "_CHARS", "Length of the title");
define($block_lang . "_LENGTH", " characters");

define($block_lang . "_HITS", "Hits");
define($block_lang . "_DATE", "Date");
define($block_lang . "_ORDER", "Order by");

define($block_lang . "_SALARY", "Salary");
define($block_lang . "_TYPEPRICE", "Order by");
define($block_lang . "_LOCAL2", "Location");
define($block_lang . "_ITEM", "Title");

//Added for 4.0 RC2
define($block_lang . "_TITLE4", "Jobs Premium Block");
define($block_lang . "_SPONSORED_LISTINGS", "Sponsored Listings");

define($block_lang . "_COMPANY", "Company");

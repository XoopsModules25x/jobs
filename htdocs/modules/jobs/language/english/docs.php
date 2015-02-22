<?php

$mydirname = basename(dirname(dirname(dirname(__FILE__))));
$docs_lang = '_DOC_' . strtoupper($mydirname);

define($docs_lang . "_DOCUMENTATION", "Jobs Module Documentation");
define($docs_lang . "_VERSION", "Jobs 4.0  -  Only for Xoops 2.3.x versions of Xoops");
define($docs_lang . "_COMPANY_DOCS", "Companies");
define(
    $docs_lang . "_DOC_1", "1. If 'show company' is set to yes in the preferences, when a user
tries to add a listing they will be redirected to add <br>
company first. After they finish with their company info they will be
redirected to the add listing page, all the info <br>
from their company will be automatically imported into the add listing
page. "
);
define(
    $docs_lang . "_DOC_2", "2. After a user adds their company there will be a new link on the Jobs
front page 'view your listings' with their company listed below."
);
define(
    $docs_lang . "_DOC_3", "3. To edit their company info they will click on their company link
mentioned above and on the next page there will be a <br>
link 'Modify your Company Information' they can edit their info there."
);
define(
    $docs_lang . "_DOC_4", "4. On the add company page the user can add other users that can modify
their listings."
);

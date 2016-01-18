<?php
// 4.3 : release date May. 23, 2010
if (!defined('XOOPS_ROOT_PATH')) {
    exit;
}
$mydirname   = basename(dirname(__FILE__));
$cloned_lang = '_MI_' . strtoupper($mydirname);

$modversion['name']        = _MI_JOBS_NAME;
$modversion['version']     = '4.4';
$modversion['description'] = _MI_JOBS_DESC;
$modversion['credits']     = "Jobs Module for Xoops by John Mordo Created from Myads jp 2.04";
$modversion['author']      = "John Mordo - user jlm69 on Xoops";
$modversion['help']        = 'page=help';
$modversion['license']     = 'GNU GPL 2.0 or later';
$modversion['license_url'] = "www.gnu.org/licenses/gpl-2.0.html";
$modversion['official']    = 0;
$modversion['image']       = "images/jobs_slogo.png";
$modversion['dirname']     = $mydirname;

$modversion['dirmoduleadmin'] = '/Frameworks/moduleclasses/moduleadmin';
$modversion['icons16']        = '../../Frameworks/moduleclasses/icons/16';
$modversion['icons32']        = '../../Frameworks/moduleclasses/icons/32';
//about
$modversion['release_date']        = '2014/02/19';
$modversion["module_website_url"]  = "http://www.xoops.org/";
$modversion["module_website_name"] = "XOOPS";
$modversion["module_status"]       = "RC 3";
$modversion['min_php']             = '5.5';
$modversion['min_xoops']           = "2.5.7.2";
$modversion['min_admin']           = '1.1';
$modversion['min_db']              = array(
    'mysql'  => '5.0.7', 'mysqli' => '5.0.7'
);

// Admin things
$modversion['hasAdmin']    = 1;
$modversion['system_menu'] = 1;
$modversion['adminindex']  = "admin/index.php";
$modversion['adminmenu']   = "admin/menu.php";

// Templates
$modversion['templates'][1]['file']         = 'jobs_index.html';
$modversion['templates'][1]['description']  = '';
$modversion['templates'][2]['file']         = 'jobs_adlist.html';
$modversion['templates'][2]['description']  = '';
$modversion['templates'][3]['file']         = 'jobs_category.html';
$modversion['templates'][3]['description']  = '';
$modversion['templates'][4]['file']         = 'jobs_item.html';
$modversion['templates'][4]['description']  = '';
$modversion['templates'][5]['file']         = 'jobs_res_adlist.html';
$modversion['templates'][5]['description']  = '';
$modversion['templates'][6]['file']         = 'jobs_index2.html';
$modversion['templates'][6]['description']  = '';
$modversion['templates'][7]['file']         = 'jobs_res_category.html';
$modversion['templates'][7]['description']  = '';
$modversion['templates'][8]['file']         = 'jobs_resume.html';
$modversion['templates'][8]['description']  = '';
$modversion['templates'][9]['file']         = 'jobs_members.html';
$modversion['templates'][9]['description']  = '';
$modversion['templates'][10]['file']        = 'jobs_replies.html';
$modversion['templates'][10]['description'] = '';
$modversion['templates'][11]['file']        = 'jobs_add_company.html';
$modversion['templates'][11]['description'] = '';
$modversion['templates'][12]['file']        = 'jobs_addlisting.html';
$modversion['templates'][12]['description'] = '';
$modversion['templates'][13]['file']        = 'jobs_addresume.html';
$modversion['templates'][13]['description'] = '';
$modversion['templates'][14]['file']        = 'jobs_editcomp.html';
$modversion['templates'][14]['description'] = '';
$modversion['templates'][15]['file']        = 'jobs_create_resume.html';
$modversion['templates'][15]['description'] = '';
$modversion['templates'][16]['file']        = 'jobs_view_created.html';
$modversion['templates'][16]['description'] = '';
$modversion['templates'][17]['file']        = 'jobs_view_photos.html';
$modversion['templates'][17]['description'] = '';
$modversion['templates'][18]['file']        = 'jobs_premium.html';
$modversion['templates'][18]['description'] = '';
$modversion['templates'][19]['file']        = 'jobs_choose_company.html';
$modversion['templates'][19]['description'] = '';
$modversion['templates'][20]['file']        = 'jobs_modify.html';
$modversion['templates'][20]['description'] = '';
$modversion['templates'][21]['file']        = 'jobs_modcompany.html';
$modversion['templates'][21]['description'] = '';
$modversion['templates'][22]['file']        = 'jobs_modresume.html';
$modversion['templates'][22]['description'] = '';
//$modversion['templates'][23]['file'] = 'jobs_index_search.html';
//$modversion['templates'][23]['description'] = '';

// Blocks

$modversion['blocks'][1]['file']        = "jobs.php";
$modversion['blocks'][1]['name']        = _MI_JOBS_BNAME;
$modversion['blocks'][1]['description'] = _MI_JOBS_BNAME_DESC;
$modversion['blocks'][1]['show_func']   = "jobs_show";
$modversion['blocks'][1]['edit_func']   = "jobs_edit";
$modversion['blocks'][1]['options']     = "date|10|25|0";
$modversion['blocks'][1]['template']    = "jobs_block_new.html";

$modversion['blocks'][2]['file']        = "resumes.php";
$modversion['blocks'][2]['name']        = _MI_JOBS_RES_BNAME;
$modversion['blocks'][2]['description'] = _MI_JOBS_RES_BNAME_DESC;
$modversion['blocks'][2]['show_func']   = "resume_show";
$modversion['blocks'][2]['edit_func']   = "resume_edit";
$modversion['blocks'][2]['options']     = "date|10|25|0";
$modversion['blocks'][2]['template']    = "resume_block_new.html";

$modversion['blocks'][3]['file']        = "jobs2.php";
$modversion['blocks'][3]['name']        = _MI_JOBS_BNAME2;
$modversion['blocks'][3]['description'] = _MI_JOBS_BNAME2_DESC;
$modversion['blocks'][3]['show_func']   = "jobs_b2_show";
$modversion['blocks'][3]['edit_func']   = "jobs_b2_edit";
$modversion['blocks'][3]['options']     = "date|10|25|0";
$modversion['blocks'][3]['template']    = "jobs_b2.html";

$modversion['blocks'][4]['file']        = "jobs_b_premium.php";
$modversion['blocks'][4]['name']        = _MI_JOBS_BNAME3;
$modversion['blocks'][4]['description'] = _MI_JOBS_BNAME3_DESC;
$modversion['blocks'][4]['show_func']   = "jobs_block_premium_show";
$modversion['blocks'][4]['edit_func']   = "jobs_block_premium_edit";
$modversion['blocks'][4]['options']     = "date|10|25|0";
$modversion['blocks'][4]['template']    = "jobs_block_premium.html";

// Menu
$modversion['hasMain'] = 1;

// Sql file (must contain sql generated by phpMyAdmin or phpPgAdmin)
// All tables should not have any prefix!
$modversion['sqlfile']['mysql'] = "sql/mysql.sql";

// Tables created by sql file (without prefix!)

$modversion['tables'][1]  = "jobs_categories";
$modversion['tables'][2]  = "jobs_companies";
$modversion['tables'][3]  = "jobs_created_resumes";
$modversion['tables'][4]  = "jobs_listing";
$modversion['tables'][5]  = "jobs_price";
$modversion['tables'][6]  = "jobs_region";
$modversion['tables'][7]  = "jobs_resume";
$modversion['tables'][8]  = "jobs_res_categories";
$modversion['tables'][9]  = "jobs_replies";
$modversion['tables'][10] = "jobs_type";
$modversion['tables'][11] = "jobs_pictures";

// Search
$modversion['hasSearch']      = 1;
$modversion['search']['file'] = "include/search.inc.php";
$modversion['search']['func'] = "jobs_search";

// Config Settings
$modversion['hasconfig'] = 1;

// $xoopsModuleConfig['jobs_money']
$modversion['config'][1]['name']        = "" . $mydirname . "_money";
$modversion['config'][1]['title']       = $cloned_lang . "_MONEY";
$modversion['config'][1]['description'] = '';
$modversion['config'][1]['formtype']    = 'textbox';
$modversion['config'][1]['valuetype']   = 'text';
$modversion['config'][1]['default']     = '$';
$modversion['config'][1]['options']     = array();

//$xoopsModuleConfig['jobs_moderated']
$modversion['config'][2]['name']        = "" . $mydirname . "_moderated";
$modversion['config'][2]['title']       = $cloned_lang . "_MODERAT";
$modversion['config'][2]['description'] = '';
$modversion['config'][2]['formtype']    = 'yesno';
$modversion['config'][2]['valuetype']   = 'int';
$modversion['config'][2]['default']     = '0';
$modversion['config'][2]['options']     = array();

//$xoopsModuleConfig['jobs_moderate_up']
$modversion['config'][3]['name']        = "" . $mydirname . "_moderate_up";
$modversion['config'][3]['title']       = $cloned_lang . "_MODERAT_UP";
$modversion['config'][3]['description'] = '';
$modversion['config'][3]['formtype']    = 'yesno';
$modversion['config'][3]['valuetype']   = 'int';
$modversion['config'][3]['default']     = '0';
$modversion['config'][3]['options']     = array();

//$xoopsModuleConfig['jobs_show_company']
$modversion['config'][4]['name']        = "" . $mydirname . "_show_company";
$modversion['config'][4]['title']       = $cloned_lang . "_SHOW_COMPANY";
$modversion['config'][4]['description'] = '';
$modversion['config'][4]['formtype']    = 'yesno';
$modversion['config'][4]['valuetype']   = 'int';
$modversion['config'][4]['default']     = '1';
$modversion['config'][4]['options']     = array();

//$xoopsModuleConfig['jobs_show_state']
$modversion['config'][5]['name']        = "" . $mydirname . "_show_state";
$modversion['config'][5]['title']       = $cloned_lang . "_SHOW_STATE";
$modversion['config'][5]['description'] = '';
$modversion['config'][5]['formtype']    = 'yesno';
$modversion['config'][5]['valuetype']   = 'int';
$modversion['config'][5]['default']     = '1';
$modversion['config'][5]['options']     = array();

//$xoopsModuleConfig['jobs_multiple_submitters']
$modversion['config'][6]['name']        = "" . $mydirname . "_multiple_submitters";
$modversion['config'][6]['title']       = $cloned_lang . "_MULTIPLE_SUBMITTERS";
$modversion['config'][6]['description'] = '';
$modversion['config'][6]['formtype']    = 'yesno';
$modversion['config'][6]['valuetype']   = 'int';
$modversion['config'][6]['default']     = '1';
$modversion['config'][6]['options']     = array();

// $xoopsModuleConfig['jobs_perpage']
$modversion['config'][7]['name']        = "" . $mydirname . "_perpage";
$modversion['config'][7]['title']       = $cloned_lang . "_PERPAGE";
$modversion['config'][7]['description'] = '';
$modversion['config'][7]['formtype']    = 'select';
$modversion['config'][7]['valuetype']   = 'int';
$modversion['config'][7]['default']     = '10';
$modversion['config'][7]['options']     = array('10'=> 10, '15'=> 15, '20'=> 20, '25'=> 25, '30'=> 30, '35'=> 35, '40'=> 40, '50'=> 50);

// $xoopsModuleConfig['jobs_new_jobs']
$modversion['config'][8]['name']        = "" . $mydirname . "_new_jobs";
$modversion['config'][8]['title']       = $cloned_lang . "_VIEWNEWCLASS";
$modversion['config'][8]['description'] = '';
$modversion['config'][8]['formtype']    = 'yesno';
$modversion['config'][8]['valuetype']   = 'int';
$modversion['config'][8]['default']     = '1';
$modversion['config'][8]['options']     = array();

// $xoopsModuleConfig['jobs_new_jobs_count']
$modversion['config'][9]['name']        = "" . $mydirname . "_new_jobs_count";
$modversion['config'][9]['title']       = $cloned_lang . "_NUMNEW";
$modversion['config'][9]['description'] = $cloned_lang . "_ONHOME";
$modversion['config'][9]['formtype']    = 'textbox';
$modversion['config'][9]['valuetype']   = 'int';
$modversion['config'][9]['default']     = '10';
$modversion['config'][9]['options']     = array();

// $xoopsModuleConfig['jobs_countday']
$modversion['config'][10]['name']        = "" . $mydirname . "_countday";
$modversion['config'][10]['title']       = $cloned_lang . "_NEWTIME";
$modversion['config'][10]['description'] = $cloned_lang . "_INDAYS";
$modversion['config'][10]['formtype']    = 'textbox';
$modversion['config'][10]['valuetype']   = 'int';
$modversion['config'][10]['default']     = '3';
$modversion['config'][10]['options']     = array();

// $xoopsModuleConfig['jobsdays']
$modversion['config'][11]['name']        = "" . $mydirname . "_days";
$modversion['config'][11]['title']       = $cloned_lang . "_DAYS";
$modversion['config'][11]['description'] = $cloned_lang . "_INDAYS";
$modversion['config'][11]['formtype']    = 'textbox';
$modversion['config'][11]['valuetype']   = 'int';
$modversion['config'][11]['default']     = '14';
$modversion['config'][11]['options']     = array();

// $xoopsModuleConfig['jobs_block']
$modversion['config'][12]['name']        = "" . $mydirname . "_block";
$modversion['config'][12]['title']       = $cloned_lang . "_TYPEBLOC";
$modversion['config'][12]['description'] = '';
$modversion['config'][12]['formtype']    = 'select';
$modversion['config'][12]['valuetype']   = 'text';
$modversion['config'][12]['default']     = '1';
$modversion['config'][12]['options']     = array('_MI_JOBS_LASTTEN'=> '1', '_MI_JOBS_JOBRAND'=> '2');

// $xoopsModuleConfig['jobs_display_subcat']
$modversion['config'][13]['name']        = "" . $mydirname . "_display_subcat";
$modversion['config'][13]['title']       = $cloned_lang . "_DISPLSUBCAT";
$modversion['config'][13]['description'] = '';
$modversion['config'][13]['formtype']    = 'yesno';
$modversion['config'][13]['valuetype']   = 'int';
$modversion['config'][13]['default']     = '1';
$modversion['config'][13]['options']     = array();

// $xoopsModuleConfig['jobs_days']
$modversion['config'][14]['name']        = "" . $mydirname . "_subcat_num";
$modversion['config'][14]['title']       = $cloned_lang . "_NBDISPLSUBCAT";
$modversion['config'][14]['description'] = '';
$modversion['config'][14]['formtype']    = 'textbox';
$modversion['config'][14]['valuetype']   = 'int';
$modversion['config'][14]['default']     = '4';
$modversion['config'][14]['options']     = array();

// $xoopsModuleConfig['jobs_cat_sortorder']
$modversion['config'][15]['name']        = "" . $mydirname . "_cat_sortorder";
$modversion['config'][15]['title']       = $cloned_lang . "_CSORT_ORDER";
$modversion['config'][15]['description'] = '';
$modversion['config'][15]['formtype']    = 'select';
$modversion['config'][15]['valuetype']   = 'text';
$modversion['config'][15]['default']     = 'title DESC';
$modversion['config'][15]['options']     = array(
    $cloned_lang . "_ORDREALPHA"=> 'title DESC', $cloned_lang . "_ORDREPERSO"=> 'ordre DESC'
);

// $xoopsModuleConfig['jobs_listing_sortorder']
$modversion['config'][16]['name']        = "" . $mydirname . "_listing_sortorder";
$modversion['config'][16]['title']       = $cloned_lang . "_LSORT_ORDER";
$modversion['config'][16]['description'] = '';
$modversion['config'][16]['formtype']    = 'select';
$modversion['config'][16]['valuetype']   = 'text';
$modversion['config'][16]['default']     = 'date DESC';
$modversion['config'][16]['options']     = array('_MI_JOBS_ORDER_TITLE'=> 'title DESC', '_MI_JOBS_ORDER_COMPANY'=> 'company DESC', '_MI_JOBS_ORDER_TOWN'=> 'town DESC', '_MI_JOBS_ORDER_DATE'=> 'premium,date', '_MI_JOBS_ORDER_POP'=> 'view DESC');

// $xoopsModuleConfig['jobs_resume_sortorder']
$modversion['config'][17]['name']        = "" . $mydirname . "_resume_sortorder";
$modversion['config'][17]['title']       = $cloned_lang . "_RSORT_ORDER";
$modversion['config'][17]['description'] = '';
$modversion['config'][17]['formtype']    = 'select';
$modversion['config'][17]['valuetype']   = 'text';
$modversion['config'][17]['default']     = 'date DESC';
$modversion['config'][17]['options']     = array('_MI_JOBS_ORDER_TITLE'=> 'title DESC', '_MI_JOBS_ORDER_EXP'=> 'exp DESC', '_MI_JOBS_ORDER_TOWN'=> 'town DESC', '_MI_JOBS_ORDER_DATE'=> 'date DESC', '_MI_JOBS_ORDER_POP'=> 'view DESC');

//$xoopsModuleConfig['jobs_show_resume']
$modversion['config'][18]['name']        = "" . $mydirname . "_show_resume";
$modversion['config'][18]['title']       = $cloned_lang . "_RES_SHOW";
$modversion['config'][18]['description'] = '';
$modversion['config'][18]['formtype']    = 'yesno';
$modversion['config'][18]['valuetype']   = 'int';
$modversion['config'][18]['default']     = '1';
$modversion['config'][18]['options']     = array();

//$xoopsModuleConfig['jobs_moderate_resume']
$modversion['config'][19]['name']        = "" . $mydirname . "_moderate_resume";
$modversion['config'][19]['title']       = $cloned_lang . "_RES_MODERAT";
$modversion['config'][19]['description'] = '';
$modversion['config'][19]['formtype']    = 'yesno';
$modversion['config'][19]['valuetype']   = 'int';
$modversion['config'][19]['default']     = '0';
$modversion['config'][19]['options']     = array();

//$xoopsModuleConfig['jobs_moderate_res_up']
$modversion['config'][20]['name']        = "" . $mydirname . "_moderate_res_up";
$modversion['config'][20]['title']       = $cloned_lang . "_RES_MODERAT_UP";
$modversion['config'][20]['description'] = '';
$modversion['config'][20]['formtype']    = 'yesno';
$modversion['config'][20]['valuetype']   = 'int';
$modversion['config'][20]['default']     = '0';
$modversion['config'][20]['options']     = array();

// $xoopsModuleConfig['resdays']
$modversion['config'][21]['name']        = "" . $mydirname . "_res_days";
$modversion['config'][21]['title']       = $cloned_lang . "_RES_DAYS";
$modversion['config'][21]['description'] = $cloned_lang . "_INDAYS";
$modversion['config'][21]['formtype']    = 'textbox';
$modversion['config'][21]['valuetype']   = 'int';
$modversion['config'][21]['default']     = '180';
$modversion['config'][21]['options']     = array();

// $xoopsModuleConfig['resume_perpage']
$modversion['config'][22]['name']        = "" . $mydirname . "_resume_perpage";
$modversion['config'][22]['title']       = $cloned_lang . "_RES_PERPAGE";
$modversion['config'][22]['description'] = '';
$modversion['config'][22]['formtype']    = 'select';
$modversion['config'][22]['valuetype']   = 'int';
$modversion['config'][22]['default']     = '10';
$modversion['config'][22]['options']     = array('10'=> 10, '15'=> 15, '20'=> 20, '25'=> 25, '30'=> 30, '35'=> 35, '40'=> 40, '50'=> 50);

// $xoopsModuleConfig['resumesize']
$modversion['config'][23]['name']        = "" . $mydirname . "_resumesize";
$modversion['config'][23]['title']       = $cloned_lang . "_RES_SIZE";
$modversion['config'][23]['description'] = $cloned_lang . "_INBYTES";
$modversion['config'][23]['formtype']    = 'textbox';
$modversion['config'][23]['valuetype']   = 'int';
$modversion['config'][23]['default']     = '10000';
$modversion['config'][23]['options']     = array();

// $xoopsModuleConfig['jobs_nb_pict']
$modversion['config'][24]['name']        = "" . $mydirname . "_not_premium";
$modversion['config'][24]['title']       = $cloned_lang . "_NOT_PREMIUM";
$modversion['config'][24]['description'] = $cloned_lang . "_NOT_PREMIUM_DESC";
$modversion['config'][24]['formtype']    = 'textbox';
$modversion['config'][24]['valuetype']   = 'int';
$modversion['config'][24]['default']     = '1';

// $xoopsModuleConfig['jobs_nb_pict']
$modversion['config'][25]['name']        = "" . $mydirname . "_nb_pict";
$modversion['config'][25]['title']       = $cloned_lang . "_NUMBPICT_TITLE";
$modversion['config'][25]['description'] = $cloned_lang . "_NUMBPICT_DESC";
$modversion['config'][25]['formtype']    = 'textbox';
$modversion['config'][25]['valuetype']   = 'int';
$modversion['config'][25]['default']     = '12';

// $xoopsModuleConfig['jobs_path_upload']
$modversion['config'][26]['name']        = "" . $mydirname . "_path_upload";
$modversion['config'][26]['title']       = $cloned_lang . "_UPLOAD_TITLE";
$modversion['config'][26]['description'] = $cloned_lang . "_UPLOAD_DESC";
$modversion['config'][26]['formtype']    = 'textbox';
$modversion['config'][26]['valuetype']   = 'text';
$modversion['config'][26]['default']     = XOOPS_ROOT_PATH . "/modules/$mydirname/photo";

// $xoopsModuleConfig['jobs_link_upload']
$modversion['config'][27]['name']        = "" . $mydirname . "_link_upload";
$modversion['config'][27]['title']       = $cloned_lang . "_LINKUPLOAD_TI";
$modversion['config'][27]['description'] = $cloned_lang . "_LINKUPLOAD_DESC";
$modversion['config'][27]['formtype']    = 'textbox';
$modversion['config'][27]['valuetype']   = 'text';
$modversion['config'][27]['default']     = XOOPS_URL . "/modules/$mydirname/photo";

// $xoopsModuleConfig['jobs_thumb_width']
$modversion['config'][28]['name']        = "" . $mydirname . "_thumb_width";
$modversion['config'][28]['title']       = $cloned_lang . "_THUMW_TITLE";
$modversion['config'][28]['description'] = $cloned_lang . "_THUMBW_DESC";
$modversion['config'][28]['formtype']    = 'textbox';
$modversion['config'][28]['valuetype']   = 'text';
$modversion['config'][28]['default']     = '125';

// $xoopsModuleConfig['jobs_thumb_height']
$modversion['config'][29]['name']        = "" . $mydirname . "_thumb_height";
$modversion['config'][29]['title']       = $cloned_lang . "_THUMBH_TITLE";
$modversion['config'][29]['description'] = $cloned_lang . "_THUMBH_DESC";
$modversion['config'][29]['formtype']    = 'textbox';
$modversion['config'][29]['valuetype']   = 'text';
$modversion['config'][29]['default']     = '175';

// $xoopsModuleConfig['jobs_resized_width']
$modversion['config'][30]['name']        = "" . $mydirname . "_resized_width";
$modversion['config'][30]['title']       = $cloned_lang . "_RESIZEDW_TITLE";
$modversion['config'][30]['description'] = $cloned_lang . "_RESIZEDW_DESC";
$modversion['config'][30]['formtype']    = 'textbox';
$modversion['config'][30]['valuetype']   = 'text';
$modversion['config'][30]['default']     = '500';

// $xoopsModuleConfig['jobs_resized_height']
$modversion['config'][31]['name']        = "" . $mydirname . "_resized_height";
$modversion['config'][31]['title']       = $cloned_lang . "_RESIZEDH_TITLE";
$modversion['config'][31]['description'] = $cloned_lang . "_RESIZEDH_DESC";
$modversion['config'][31]['formtype']    = 'textbox';
$modversion['config'][31]['valuetype']   = 'text';
$modversion['config'][31]['default']     = '400';

// $xoopsModuleConfig['jobs_max_original_width']
$modversion['config'][32]['name']        = "" . $mydirname . "_max_original_width";
$modversion['config'][32]['title']       = $cloned_lang . "_ORIGW_TITLE";
$modversion['config'][32]['description'] = $cloned_lang . "_ORIGW_DESC";
$modversion['config'][32]['formtype']    = 'textbox';
$modversion['config'][32]['valuetype']   = 'text';
$modversion['config'][32]['default']     = '2048';

// $xoopsModuleConfig['jobs_max_original_height']
$modversion['config'][33]['name']        = "" . $mydirname . "_max_original_height";
$modversion['config'][33]['title']       = $cloned_lang . "_ORIGH_TITLE";
$modversion['config'][33]['description'] = $cloned_lang . "_ORIGH_DESC";
$modversion['config'][33]['formtype']    = 'textbox';
$modversion['config'][33]['valuetype']   = 'text';
$modversion['config'][33]['default']     = '1600';

// $xoopsModuleConfig['jobs_maxfilesize']
$modversion['config'][34]['name']        = "" . $mydirname . "_maxfilesize";
$modversion['config'][34]['title']       = $cloned_lang . "_MAXFILEBYTES_T";
$modversion['config'][34]['description'] = $cloned_lang . "_MAXFILEBYTES_D";
$modversion['config'][34]['formtype']    = 'textbox';
$modversion['config'][34]['valuetype']   = 'text';
$modversion['config'][34]['default']     = '512000';

// Use WYSIWYG Editors for Jobs?
$modversion['config'][35]['name']        = "" . $mydirname . "_form_options";
$modversion['config'][35]['title']       = $cloned_lang . "_EDITOR";
$modversion['config'][35]['description'] = $cloned_lang . "_LIST_EDITORS";
$modversion['config'][35]['formtype']    = 'select';
$modversion['config'][35]['valuetype']   = 'text';
$modversion['config'][35]['default']     = 'dhtmltextarea';
xoops_load('xoopseditorhandler');
$editor_handler                      = XoopsEditorHandler::getInstance();
$modversion['config'][35]['options'] = array_flip($editor_handler->getList());

// Use WYSIWYG Editors for resumes?
$modversion['config'][36]['name']        = "" . $mydirname . "_resume_options";
$modversion['config'][36]['title']       = $cloned_lang . "_RES_EDITOR";
$modversion['config'][36]['description'] = $cloned_lang . "_LIST_EDITORS";
$modversion['config'][36]['formtype']    = 'select';
$modversion['config'][36]['valuetype']   = 'text';
$modversion['config'][36]['default']     = 'dhtmltextarea';
xoops_load('xoopseditorhandler');
$editor_handler                      = XoopsEditorHandler::getInstance();
$modversion['config'][36]['options'] = array_flip($editor_handler->getList());

// $xoopsModuleConfig['jobs_lightbox']
$modversion['config'][37]['name']        = "" . $mydirname . "_lightbox";
$modversion['config'][37]['title']       = $cloned_lang . "_LIGHTBOX";
$modversion['config'][37]['description'] = $cloned_lang . "_LIGHTBOX_DESC";
$modversion['config'][37]['formtype']    = 'yesno';
$modversion['config'][37]['valuetype']   = 'int';
$modversion['config'][37]['default']     = '0';
$modversion['config'][37]['options']     = array();

// $xoopsModuleConfig['jobs_admin_mail']
$modversion['config'][38]['name']        = "" . $mydirname . "_admin_mail";
$modversion['config'][38]['title']       = $cloned_lang . "_ADMIN_MAIL";
$modversion['config'][38]['description'] = $cloned_lang . "_ADMIN_MAIL_DESC";
$modversion['config'][38]['formtype']    = 'yesno';
$modversion['config'][38]['valuetype']   = 'int';
$modversion['config'][38]['default']     = '0';
$modversion['config'][38]['options']     = array();

// $xoopsModuleConfig['jobs_use_captcha']
$modversion['config'][39]['name']        = "" . $mydirname . "_use_captcha";
$modversion['config'][39]['title']       = $cloned_lang . "_USE_CAPTCHA";
$modversion['config'][39]['description'] = $cloned_lang . "_USE_CAPTCHA_DESC";
$modversion['config'][39]['formtype']    = 'yesno';
$modversion['config'][39]['valuetype']   = 'int';
$modversion['config'][39]['default']     = '1';
$modversion['config'][39]['options']     = array();

// $xoopsModuleConfig['jobs_use_index_code']
$modversion['config'][40]['name']        = "" . $mydirname . "_use_index_code";
$modversion['config'][40]['title']       = $cloned_lang . "_USE_INDEX_CODE";
$modversion['config'][40]['description'] = $cloned_lang . "_USE_INDEX_CODE_DESC";
$modversion['config'][40]['formtype']    = 'yesno';
$modversion['config'][40]['valuetype']   = 'int';
$modversion['config'][40]['default']     = '1';
$modversion['config'][40]['options']     = array();

// $xoopsModuleConfig['jobs_use_banner']
$modversion['config'][41]['name']        = "" . $mydirname . "_use_banner";
$modversion['config'][41]['title']       = $cloned_lang . "_USE_BANNER";
$modversion['config'][41]['description'] = $cloned_lang . "_USE_BANNER_DESC";
$modversion['config'][41]['formtype']    = 'yesno';
$modversion['config'][41]['valuetype']   = 'int';
$modversion['config'][41]['default']     = '1';
$modversion['config'][41]['options']     = array();

// $xoopsModuleConfig['jobs_index_code']
$modversion['config'][42]['name']        = "" . $mydirname . "_index_code";
$modversion['config'][42]['title']       = $cloned_lang . "_INDEX_CODE";
$modversion['config'][42]['description'] = $cloned_lang . "_INDEX_CODE_DESC";
$modversion['config'][42]['formtype']    = 'textarea';
$modversion['config'][42]['valuetype']   = 'text';
$modversion['config'][42]['default']     = '';

// $xoopsModuleConfig['jobs_max_original_width']
$modversion['config'][43]['name']        = "" . $mydirname . "_index_code_place";
$modversion['config'][43]['title']       = $cloned_lang . "_INDEX_CODE_PLACE";
$modversion['config'][43]['description'] = $cloned_lang . "_INDEX_CODE_PLACE_DESC";
$modversion['config'][43]['formtype']    = 'textbox';
$modversion['config'][43]['valuetype']   = 'text';
$modversion['config'][43]['default']     = '5';

// $xoopsModuleConfig['jobs_resume_code']
$modversion['config'][44]['name']        = "" . $mydirname . "_resume_code";
$modversion['config'][44]['title']       = $cloned_lang . "_RESUME_CODE";
$modversion['config'][44]['description'] = $cloned_lang . "_RESUME_CODE_DESC";
$modversion['config'][44]['formtype']    = 'yesno';
$modversion['config'][44]['valuetype']   = 'int';
$modversion['config'][44]['default']     = '1';
$modversion['config'][44]['options']     = array();

// $xoopsModuleConfig['jobs_offer_search'] - added for optional search
$modversion['config'][45]['name']        = "" . $mydirname . "_offer_search";
$modversion['config'][45]['title']       = $cloned_lang . "_OFFER_SEARCH";
$modversion['config'][45]['description'] = $cloned_lang . "_OFFER_SEARCH_DESC";
$modversion['config'][45]['formtype']    = 'yesno';
$modversion['config'][45]['valuetype']   = 'int';
$modversion['config'][45]['default']     = '1';
$modversion['config'][45]['options']     = array();

// $xoopsModuleConfig['jobs_resume_search'] - added for optional search
$modversion['config'][46]['name']        = "" . $mydirname . "_resume_search";
$modversion['config'][46]['title']       = $cloned_lang . "_RESUME_SEARCH";
$modversion['config'][46]['description'] = $cloned_lang . "_RESUME_SEARCH_DESC";
$modversion['config'][46]['formtype']    = 'yesno';
$modversion['config'][46]['valuetype']   = 'int';
$modversion['config'][46]['default']     = '1';
$modversion['config'][46]['options']     = array();

// $xoopsModuleConfig['jobs_resume_one']
$modversion['config'][47]['name']        = "" . $mydirname . "_resume_one";
$modversion['config'][47]['title']       = $cloned_lang . "_RESUME_ONE";
$modversion['config'][47]['description'] = '';
$modversion['config'][47]['formtype']    = 'yesno';
$modversion['config'][47]['valuetype']   = 'int';
$modversion['config'][47]['default']     = '0';
$modversion['config'][47]['options']     = array();

// $xoopsModuleConfig['jobs_resume_one']
$modversion['config'][48]['name']        = "" . $mydirname . "_countries";
$modversion['config'][48]['title']       = $cloned_lang . "_COUNTRIES";
$modversion['config'][48]['description'] = '';
$modversion['config'][48]['formtype']    = 'yesno';
$modversion['config'][48]['valuetype']   = 'int';
$modversion['config'][48]['default']     = '0';
$modversion['config'][48]['options']     = array();

// $xoopsModuleConfig['jobs_days']
$modversion['config'][49]['name']        = "" . $mydirname . "_joblisting_num";
$modversion['config'][49]['title']       = $cloned_lang . "_NBJOBLISTING";
$modversion['config'][49]['description'] = '';
$modversion['config'][49]['formtype']    = 'textbox';
$modversion['config'][49]['valuetype']   = 'int';
$modversion['config'][49]['default']     = '15';
$modversion['config'][49]['options']     = array();

// $xoopsModuleConfig['jobs_days']
$modversion['config'][50]['name']        = "" . $mydirname . "_reslisting_num";
$modversion['config'][50]['title']       = $cloned_lang . "_NBRESLISTING";
$modversion['config'][50]['description'] = '';
$modversion['config'][50]['formtype']    = 'textbox';
$modversion['config'][50]['valuetype']   = 'int';
$modversion['config'][50]['default']     = '4';
$modversion['config'][50]['options']     = array();

//Notification
//$modversion["notification"] = array();
$modversion['hasNotification'] = 1;

$modversion['notification']['lookup_file'] = 'include/notification.inc.php';
$modversion['notification']['lookup_func'] = 'jobs_notify_iteminfo';

$modversion['notification']['category'][1]['name']           = 'global';
$modversion['notification']['category'][1]['title']          = _MI_JOBS_GLOBAL_NOTIFY;
$modversion['notification']['category'][1]['description']    = _MI_JOBS_GLOBAL_NOTIFYDSC;
$modversion['notification']['category'][1]['subscribe_from'] = array('index.php', 'jobscat.php', 'members.php');

$modversion['notification']['category'][2]['name']           = 'category';
$modversion['notification']['category'][2]['title']          = _MI_JOBS_CATEGORY_NOTIFY;
$modversion['notification']['category'][2]['description']    = _MI_JOBS_CATEGORY_NOTIFYDSC;
$modversion['notification']['category'][2]['subscribe_from'] = 'jobscat.php';
$modversion['notification']['category'][2]['item_name']      = 'cid';
$modversion['notification']['category'][2]['allow_bookmark'] = 1;

$modversion['notification']['category'][3]['name']           = 'job_listing';
$modversion['notification']['category'][3]['title']          = _MI_JOBS_NOTIFY;
$modversion['notification']['category'][3]['description']    = _MI_JOBS_NOTIFYDSC;
$modversion['notification']['category'][3]['subscribe_from'] = array('viewjobs.php');
$modversion['notification']['category'][3]['item_name']      = 'lid';
$modversion['notification']['category'][3]['allow_bookmark'] = 1;

$modversion['notification']['category'][4]['name']           = 'company_listing';
$modversion['notification']['category'][4]['title']          = _MI_JOBS_COMPANYCAT_NOTIFY;
$modversion['notification']['category'][4]['description']    = _MI_JOBS_COMPANY_NOTIFYDSC;
$modversion['notification']['category'][4]['subscribe_from'] = array('members.php');
$modversion['notification']['category'][4]['item_name']      = 'comp_id';
$modversion['notification']['category'][4]['allow_bookmark'] = 1;

//$modversion['notification']['category'][4]['name'] = 'res_global';
//$modversion['notification']['category'][4]['title'] = _MI_JOBS_RES_GLOBAL_NOTIFY;
//$modversion['notification']['category'][4]['description'] = _MI_JOBS_RES_GLOBAL_NOTIFYDSC;
//$modversion['notification']['category'][4]['subscribe_from'] = array('resume.php');


//event
//jobs notifications new listings in this category
$modversion['notification']['event'][1]['name']          = 'new_jobs_cat';
$modversion['notification']['event'][1]['category']      = 'category';
$modversion['notification']['event'][1]['title']         = _MI_JOBS_NEWPOST_NOTIFY;
$modversion['notification']['event'][1]['caption']       = _MI_JOBS_NEWPOST_NOTIFYCAP;
$modversion['notification']['event'][1]['description']   = _MI_JOBS_NEWPOST_NOTIFYDSC;
$modversion['notification']['event'][1]['mail_template'] = 'jobs_listing_newpost_notify';
$modversion['notification']['event'][1]['mail_subject']  = _MI_JOBS_NEWPOST_NOTIFYSBJ;

//new listings in all categories posted
$modversion['notification']['event'][2]['name']          = 'new_job';
$modversion['notification']['event'][2]['category']      = 'global';
$modversion['notification']['event'][2]['title']         = _MI_JOBS_GLOBAL_NEWPOST_NOTIFY;
$modversion['notification']['event'][2]['caption']       = _MI_JOBS_GLOBAL_NEWPOST_NOTIFYCAP;
$modversion['notification']['event'][2]['description']   = _MI_JOBS_GLOBAL_NEWPOST_NOTIFYDSC;
$modversion['notification']['event'][2]['mail_template'] = 'jobs_listing_newpost_notify';
$modversion['notification']['event'][2]['mail_subject']  = _MI_JOBS_GLOBAL_NEWPOST_NOTIFYSBJ;

//new listings in all categories posted
$modversion['notification']['event'][3]['name']          = 'new_resume';
$modversion['notification']['event'][3]['category']      = 'res_global';
$modversion['notification']['event'][3]['title']         = _MI_JOBS_RES_GLOBAL_NEWPOST_NOTIFY;
$modversion['notification']['event'][3]['caption']       = _MI_JOBS_RES_GLOBAL_NEWPOST_NOTIFYCAP;
$modversion['notification']['event'][3]['description']   = _MI_JOBS_RES_GLOBAL_NEWPOST_NOTIFYDSC;
$modversion['notification']['event'][3]['mail_template'] = 'jobs_listing_newpost_notify';
$modversion['notification']['event'][3]['mail_subject']  = _MI_JOBS_RES_GLOBAL_NEWPOST_NOTIFYSBJ;

//jobs notifications new listings in this category
$modversion['notification']['event'][4]['name']          = 'new_resume_cat';
$modversion['notification']['event'][4]['category']      = 'resume';
$modversion['notification']['event'][4]['title']         = _MI_JOBS_RES_NEWPOST_NOTIFY;
$modversion['notification']['event'][4]['caption']       = _MI_JOBS_RES_NEWPOST_NOTIFYCAP;
$modversion['notification']['event'][4]['description']   = _MI_JOBS_RES_NEWPOST_NOTIFYDSC;
$modversion['notification']['event'][4]['mail_template'] = 'jobs_listing_newpost_notify';
$modversion['notification']['event'][4]['mail_subject']  = _MI_JOBS_RES_NEWPOST_NOTIFYSBJ;

//jobs notifications new listings in this category
$modversion['notification']['event'][5]['name']          = 'new_jobs_comp';
$modversion['notification']['event'][5]['category']      = 'company_listing';
$modversion['notification']['event'][5]['title']         = _MI_JOBS_COMPANY_NOTIFY;
$modversion['notification']['event'][5]['caption']       = _MI_JOBS_COMPANY_NOTIFYCAP;
$modversion['notification']['event'][5]['description']   = _MI_JOBS_COMPANY_NOTIFYDSC;
$modversion['notification']['event'][5]['mail_template'] = 'jobs_company_newpost_notify';
$modversion['notification']['event'][5]['mail_subject']  = _MI_JOBS_COMPANY_NOTIFYSBJ;

//new listings in all categories posted

// On Update
if (!empty($_POST['fct']) && !empty($_POST['op']) && $_POST['fct'] == 'modulesadmin' && $_POST['op'] == 'update_ok'
    && $_POST['dirname'] == $modversion['dirname']
) {
    include dirname(__FILE__) . "/include/onupdate.inc.php";
}

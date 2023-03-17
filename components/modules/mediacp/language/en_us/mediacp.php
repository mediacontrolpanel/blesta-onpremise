<?php
/**
 * en_us language for the MediaCP module.
 */
// Basics
$lang['Mediacp.name'] = 'MediaCP';
$lang['Mediacp.description'] = 'Media Control Panel provides a control panel for selling audio and video streaming services.';
$lang['Mediacp.module_row'] = 'Server';
$lang['Mediacp.module_row_plural'] = 'Servers';
$lang['Mediacp.module_group'] = 'Server Group';


// Module management
$lang['Mediacp.add_module_row'] = 'Add Server';
$lang['Mediacp.add_module_group'] = 'Add Server Group';
$lang['Mediacp.manage.module_rows_title'] = 'Servers';

$lang['Mediacp.manage.module_rows_heading.hostname'] = 'Hostname';
$lang['Mediacp.manage.module_rows_heading.port'] = 'Port';
$lang['Mediacp.manage.module_rows_heading.usessl'] = 'Use SSL';
$lang['Mediacp.manage.module_rows_heading.apikey'] = 'API Key';
$lang['Mediacp.manage.module_rows_heading.options'] = 'Options';
$lang['Mediacp.manage.module_rows.edit'] = 'Edit';
$lang['Mediacp.manage.module_rows.delete'] = 'Delete';
$lang['Mediacp.manage.module_rows.confirm_delete'] = 'Are you sure you want to delete this Server';

$lang['Mediacp.manage.module_rows_no_results'] = 'There are no Servers.';

$lang['Mediacp.manage.module_groups_title'] = 'Groups';
$lang['Mediacp.manage.module_groups_heading.name'] = 'Name';
$lang['Mediacp.manage.module_groups_heading.module_rows'] = 'Servers';
$lang['Mediacp.manage.module_groups_heading.options'] = 'Options';

$lang['Mediacp.manage.module_groups.edit'] = 'Edit';
$lang['Mediacp.manage.module_groups.delete'] = 'Delete';
$lang['Mediacp.manage.module_groups.confirm_delete'] = 'Are you sure you want to delete this Server';

$lang['Mediacp.manage.module_groups.no_results'] = 'There is no Server Group';



// Add row
$lang['Mediacp.add_row.box_title'] = 'MediaCP - Add Server';
////$lang['Mediacp.add_row.name_servers_title'] = 'Name Servers';
////$lang['Mediacp.add_row.notes_title'] = 'Notes';
////$lang['Mediacp.add_row.name_server_btn'] = 'Add Additional Name Server';
////$lang['Mediacp.add_row.name_server_col'] = 'Name Server';
////$lang['Mediacp.add_row.name_server_host_col'] = 'Hostname';
////$lang['Mediacp.add_row.name_server'] = 'Name server %1$s'; // %1$s is the name server number (e.g. 3)
////$lang['Mediacp.add_row.remove_name_server'] = 'Remove';
$lang['Mediacp.add_row.add_btn'] = 'Add Server';


// Edit row
$lang['Mediacp.edit_row.box_title'] = 'MediaCP - Edit Server';
////$lang['Mediacp.edit_row.name_servers_title'] = 'Name Servers';
////$lang['Mediacp.edit_row.notes_title'] = 'Notes';
////$lang['Mediacp.edit_row.name_server_btn'] = 'Add Additional Name Server';
////$lang['Mediacp.edit_row.name_server_col'] = 'Name Server';
////$lang['Mediacp.edit_row.name_server_host_col'] = 'Hostname';
////$lang['Mediacp.edit_row.name_server'] = 'Name server %1$s'; // %1$s is the name server number (e.g. 3)
////$lang['Mediacp.edit_row.remove_name_server'] = 'Remove';
$lang['Mediacp.edit_row.edit_btn'] = 'Update Server';


// Row meta
$lang['Mediacp.row_meta.hostname'] = 'Hostname';
$lang['Mediacp.row_meta.port'] = 'Port';
$lang['Mediacp.row_meta.usessl'] = 'Use SSL';
$lang['Mediacp.row_meta.apikey'] = 'API Key';


$lang['Mediacp.row_meta.tooltip.hostname'] = 'i.e., yourdomain.com';
$lang['Mediacp.row_meta.tooltip.port'] = 'Defaults 2020';


// Errors
$lang['Mediacp.!error.hostname.valid'] = 'Invalid Hostname';
$lang['Mediacp.!error.port.valid'] = 'Invalid Port';
$lang['Mediacp.!error.usessl.valid'] = 'Invalid Use SSL';
$lang['Mediacp.!error.apikey.valid'] = 'Invalid API Key';
$lang['Mediacp.!error.module_row.missing'] = 'An internal error occurred. The module row is unavailable.';



// Restart Service
$lang['Mediacp.login'] = 'Login to Panel';
$lang['Mediacp.restart'] = 'Restart Service';
$lang['Mediacp.restart.header'] = 'Restart Service';
$lang['Mediacp.restart.submit'] = 'Submit';


// Stop Service
$lang['Mediacp.stop'] = 'Stop Service';
$lang['Mediacp.stop.header'] = 'Stop Service';
$lang['Mediacp.stop.submit'] = 'Submit';

// Service info
$lang['Mediacp.service_info.name'] = 'Service Name';
$lang['Mediacp.service_info.portbase'] = 'Portbase';
$lang['Mediacp.service_info.username'] = 'Username';
$lang['Mediacp.service_info.password'] = 'Password';
$lang['Mediacp.service_info.customer_id'] = 'Customer ID';
$lang['Mediacp.service_info.service_id'] = 'Service ID';
////// These are the definitions for if you are trying to include a login link in the service info pages
////$lang['Mediacp.service_info.options'] = 'Options';
////$lang['Mediacp.service_info.option_login'] = 'Login';

// Service Fields
$lang['Mediacp.service_fields.name'] = 'Service Name';
$lang['Mediacp.service_fields.portbase'] = 'Portbase';
$lang['Mediacp.service_fields.username'] = 'Username';
$lang['Mediacp.service_fields.password'] = 'Password';
$lang['Mediacp.service_fields.customer_id'] = 'Customer ID';
$lang['Mediacp.service_fields.service_id'] = 'Service ID';




// Package Fields
$lang['Mediacp.package_fields.media_service'] = 'Media Service';
$lang['Mediacp.package_fields.autodj'] = 'AutoDJ';
$lang['Mediacp.package_fields.servicetype'] = 'Video Service Type';
$lang['Mediacp.package_fields.connections'] = 'Connections';
$lang['Mediacp.package_fields.bitrate'] = 'Bitrate (Kbps)';
$lang['Mediacp.package_fields.bandwidth'] = 'Bandwidth';
$lang['Mediacp.package_fields.quota'] = 'Quota';
$lang['Mediacp.package_fields.streamtargets'] = 'Stream Targets (Video Services)';


$lang['Mediacp.package_field.tooltip.servicetype'] = 'Live Streaming, TV Station, Ondemand Streaming, Stream Relay';
$lang['Mediacp.package_field.tooltip.connections'] = 'Number of listeners / viewers';
$lang['Mediacp.package_field.tooltip.bitrate'] = 'Kbps';
$lang['Mediacp.package_field.tooltip.bandwidth'] = 'MB';
$lang['Mediacp.package_field.tooltip.quota'] = 'MB';
$lang['Mediacp.package_field.tooltip.streamtargets'] = 'Unlimited or numeric value';

// Cron Tasks


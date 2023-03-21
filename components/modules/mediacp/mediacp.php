<?php
////use Blesta\Core\Util\Validate\Server;
/**
 * MediaCP Module
 *
 * @link https://www.mediacp.net MediaCP
 */
class Mediacp extends Module
{

    /**
     * Initializes the module
     */
    public function __construct()
    {
        // Load the language required by this module
        Language::loadLang('mediacp', null, dirname(__FILE__) . DS . 'language' . DS);

        // Load components required by this module
        Loader::loadComponents($this, ['Input']);

        // Load module config
        $this->loadConfig(dirname(__FILE__) . DS . 'config.json');

        Configure::load('mediacp', dirname(__FILE__) . DS . 'config' . DS);
    }

    /**
     * Performs any necessary bootstraping actions
     */
    public function install()
    {
    }

    /**
     * Performs migration of data from $current_version (the current installed version)
     * to the given file set version. Sets Input errors on failure, preventing
     * the module from being upgraded.
     *
     * @param string $current_version The current installed version of this module
     */
    public function upgrade($current_version)
    {
////        if (version_compare($current_version, '1.1.0', '<')) {
////            // Preform actions here such as re-adding cron tasks, setting new meta fields, and more
////        }
    }

    /**
     * Performs any necessary cleanup actions. Sets Input errors on failure
     * after the module has been uninstalled.
     *
     * @param int $module_id The ID of the module being uninstalled
     * @param bool $last_instance True if $module_id is the last instance
     *  across all companies for this module, false otherwise
     */
    public function uninstall($module_id, $last_instance)
    {
    }

    /**
     * Returns the rendered view of the manage module page.
     *
     * @param mixed $module A stdClass object representing the module and its rows
     * @param array $vars An array of post data submitted to or on the manager module
     *  page (used to repopulate fields after an error)
     * @return string HTML content containing information to display when viewing the manager module page
     */
    public function manageModule($module, array &$vars)
    {
        // Load the view into this object, so helpers can be automatically added to the view
        $this->view = new View('manage', 'default');
        $this->view->base_uri = $this->base_uri;
        $this->view->setDefaultView('components' . DS . 'modules' . DS . 'mediacp' . DS);

        // Load the helpers required for this view
        Loader::loadHelpers($this, ['Form', 'Html', 'Widget']);

        $this->view->set('module', $module);

        return $this->view->fetch();
    }

    /**
     * Returns the rendered view of the add module row page.
     *
     * @param array $vars An array of post data submitted to or on the add module
     *  row page (used to repopulate fields after an error)
     * @return string HTML content containing information to display when viewing the add module row page
     */
    public function manageAddRow(array &$vars)
    {
        // Load the view into this object, so helpers can be automatically added to the view
        $this->view = new View('add_row', 'default');
        $this->view->base_uri = $this->base_uri;
        $this->view->setDefaultView('components' . DS . 'modules' . DS . 'mediacp' . DS);

        // Load the helpers required for this view
        Loader::loadHelpers($this, ['Form', 'Html', 'Widget']);

        if (!empty($vars)) {
            // Set unset checkboxes
            $checkbox_fields = ['usessl'];

            foreach ($checkbox_fields as $checkbox_field) {
                if (!isset($vars[$checkbox_field])) {
                    $vars[$checkbox_field] = 'false';
                }
            }
        }

        $this->view->set('vars', (object) $vars);

        return $this->view->fetch();
    }

    /**
     * Returns the rendered view of the edit module row page.
     *
     * @param stdClass $module_row The stdClass representation of the existing module row
     * @param array $vars An array of post data submitted to or on the edit
     *  module row page (used to repopulate fields after an error)
     * @return string HTML content containing information to display when viewing the edit module row page
     */
    public function manageEditRow($module_row, array &$vars)
    {
        // Load the view into this object, so helpers can be automatically added to the view
        $this->view = new View('edit_row', 'default');
        $this->view->base_uri = $this->base_uri;
        $this->view->setDefaultView('components' . DS . 'modules' . DS . 'mediacp' . DS);

        // Load the helpers required for this view
        Loader::loadHelpers($this, ['Form', 'Html', 'Widget']);

        if (empty($vars)) {
            $vars = $module_row->meta;
        } else {
            // Set unset checkboxes
            $checkbox_fields = ['usessl'];

            foreach ($checkbox_fields as $checkbox_field) {
                if (!isset($vars[$checkbox_field])) {
                    $vars[$checkbox_field] = 'false';
                }
            }
        }

        $this->view->set('vars', (object) $vars);

        return $this->view->fetch();
    }

    /**
     * Adds the module row on the remote server. Sets Input errors on failure,
     * preventing the row from being added. Returns a set of data, which may be
     * a subset of $vars, that is stored for this module row.
     *
     * @param array $vars An array of module info to add
     * @return array A numerically indexed array of meta fields for the module row containing:
     *  - key The key for this meta field
     *  - value The value for this key
     *  - encrypted Whether or not this field should be encrypted (default 0, not encrypted)
     */
    public function addModuleRow(array &$vars)
    {
        $meta_fields = ['hostname','port','usessl','apikey'];
        $encrypted_fields = [];

        // Set unset checkboxes
        $checkbox_fields = ['usessl'];

        foreach ($checkbox_fields as $checkbox_field) {
            if (!isset($vars[$checkbox_field])) {
                $vars[$checkbox_field] = 'false';
            }
        }

        $this->Input->setRules($this->getRowRules($vars));

        // Validate module row
        if ($this->Input->validates($vars)) {
            // Build the meta data for this row
            $meta = [];
            foreach ($vars as $key => $value) {
                if (in_array($key, $meta_fields)) {
                    $meta[] = [
                        'key' => $key,
                        'value' => $value,
                        'encrypted' => in_array($key, $encrypted_fields) ? 1 : 0
                    ];
                }
            }

            return $meta;
        }
    }

    /**
     * Edits the module row on the remote server. Sets Input errors on failure,
     * preventing the row from being updated. Returns a set of data, which may be
     * a subset of $vars, that is stored for this module row.
     *
     * @param stdClass $module_row The stdClass representation of the existing module row
     * @param array $vars An array of module info to update
     * @return array A numerically indexed array of meta fields for the module row containing:
     *  - key The key for this meta field
     *  - value The value for this key
     *  - encrypted Whether or not this field should be encrypted (default 0, not encrypted)
     */
    public function editModuleRow($module_row, array &$vars)
    {
        $meta_fields = ['hostname','port','usessl','apikey'];
        $encrypted_fields = [];

        // Set unset checkboxes
        $checkbox_fields = ['usessl'];

        foreach ($checkbox_fields as $checkbox_field) {
            if (!isset($vars[$checkbox_field])) {
                $vars[$checkbox_field] = 'false';
            }
        }

        $this->Input->setRules($this->getRowRules($vars));

        // Validate module row
        if ($this->Input->validates($vars)) {
            // Build the meta data for this row
            $meta = [];
            foreach ($vars as $key => $value) {
                if (in_array($key, $meta_fields)) {
                    $meta[] = [
                        'key' => $key,
                        'value' => $value,
                        'encrypted' => in_array($key, $encrypted_fields) ? 1 : 0
                    ];
                }
            }

            return $meta;
        }
    }

    /**
     * Builds and returns the rules required to add/edit a module row (e.g. server).
     *
     * @param array $vars An array of key/value data pairs
     * @return array An array of Input rules suitable for Input::setRules()
     */
    private function getRowRules(&$vars)
    {
////// Defined below are a few common and useful validation functions.  To use them you can change the 'rule' line to
////// something along the lines of:
//////                    'rule' => [[$this, 'validateHostName']],
////// For more information on writing validation rules, see the
////// docs at https://docs.blesta.com/display/dev/Error+Checking
////
        $rules = [
            'hostname' => [
                'valid' => [
                    'rule' => true,
                    'message' => Language::_('Mediacp.!error.hostname.valid', true)
                ]
            ],
            'port' => [
                'valid' => [
                    'rule' => true,
                    'message' => Language::_('Mediacp.!error.port.valid', true)
                ]
            ],
            'usessl' => [
                'valid' => [
                    'rule' => true,
                    'message' => Language::_('Mediacp.!error.usessl.valid', true)
                ]
            ],
            'apikey' => [
                'valid' => [
                    'rule' => true,
                    'message' => Language::_('Mediacp.!error.apikey.valid', true)
                ]
            ]
        ];

        return $rules;
    }
////
////    /**
////     * Validates that the given hostname is valid.
////     *
////     * @param string $host_name The host name to validate
////     * @return bool True if the hostname is valid, false otherwise
////     */
////    public function validateHostName($host_name)
////    {
////// Be sure to uncomment the Server use statement at the top of this file if you are going to uncomment this method
////        $validator = new Server();
////        return $validator->isDomain($host_name) || $validator->isIp($host_name);
////    }
////
////    /**
////     * Validates that at least 2 name servers are set in the given array of name servers.
////     *
////     * @param array $name_servers An array of name servers
////     * @return bool True if the array count is >= 2, false otherwise
////     */
////    public function validateNameServerCount($name_servers)
////    {
////        if (is_array($name_servers) && count($name_servers) >= 2) {
////            return true;
////        }
////
////        return false;
////    }
////
////    /**
////     * Validates that the nameservers given are formatted correctly.
////     *
////     * @param array $name_servers An array of name servers
////     * @return bool True if every name server is formatted correctly, false otherwise
////     */
////    public function validateNameServers($name_servers)
////    {
////// Be sure that you have also uncommented validateHostName() before you uncomment this method
////        if (is_array($name_servers)) {
////            foreach ($name_servers as $name_server) {
////                if (!$this->validateHostName($name_server)) {
////                    return false;
////                }
////            }
////        }
////
////        return true;
////    }
////
////
////    /**
////     * Validates whether or not the connection details are valid by attempting to fetch
////     * the number of accounts that currently reside on the server.
////     *
////     * @param string $password The ISPmanager server password
////     * @param string $hostname The ISPmanager server hostname
////     * @param string $user_name The ISPmanager server user name
////     * @param mixed $use_ssl Whether or not to use SSL
////     * @param int $account_count The number of existing accounts on the server
////     * @return bool True if the connection is valid, false otherwise
////     */
////    public function validateConnection($password, $hostname, $user_name, $use_ssl, &$account_count)
////    {
////        try {
////// Be sure that you've uncommented the getApi() method if you're uncommenting this code
//////            $api = $this->getApi($hostname, $user_name, $password, $use_ssl);
////
////            $params = compact('hostname', 'user_name', 'password', 'use_ssl');
////            $masked_params = $params;
////            $masked_params['user_name'] = '***';
////            $masked_params['password'] = '***';
////
////            $this->log($hostname . '|user', serialize($masked_params), 'input', true);
////
////            $response = $api->getAccounts();
////
////            $success = false;
////            if (!isset($response->error)) {
////                $account_count = isset($response->response) ? count($response->response) : 0;
////                $success = true;
////            }
////
////            $this->log($hostname . '|user', serialize($response), 'output', $success);
////
////            if ($success) {
////                return true;
////            }
////        } catch (Exception $e) {
////            // Trap any errors encountered, could not validate connection
////        }
////
////        return false;
////    }


    /**
     * Validates input data when attempting to add a package, returns the meta
     * data to save when adding a package. Performs any action required to add
     * the package on the remote server. Sets Input errors on failure,
     * preventing the package from being added.
     *
     * @param array An array of key/value pairs used to add the package
     * @return array A numerically indexed array of meta fields to be stored for this package containing:
     *
     *  - key The key for this meta field
     *  - value The value for this key
     *  - encrypted Whether or not this field should be encrypted (default 0, not encrypted)
     * @see Module::getModule()
     * @see Module::getModuleRow()
     */
    public function addPackage(array $vars = null)
    {
        // Set rules to validate input data
        $this->Input->setRules($this->getPackageRules($vars));

        // Build meta data to return
        $meta = [];
        if ($this->Input->validates($vars)) {
            if (!isset($vars['meta'] )) {
                return [];
            }

            // Return all package meta fields
            foreach ($vars['meta'] as $key => $value) {
                $meta[] = [
                    'key' => $key,
                    'value' => $value,
                    'encrypted' => 0
                ];
            }
        }

        return $meta;
    }

    /**
     * Validates input data when attempting to edit a package, returns the meta
     * data to save when editing a package. Performs any action required to edit
     * the package on the remote server. Sets Input errors on failure,
     * preventing the package from being edited.
     *
     * @param stdClass $package A stdClass object representing the selected package
     * @param array An array of key/value pairs used to edit the package
     * @return array A numerically indexed array of meta fields to be stored for this package containing:
     *
     *  - key The key for this meta field
     *  - value The value for this key
     *  - encrypted Whether or not this field should be encrypted (default 0, not encrypted)
     * @see Module::getModule()
     * @see Module::getModuleRow()
     */
    public function editPackage($package, array $vars = null)
    {
        // Set rules to validate input data
        $this->Input->setRules($this->getPackageRules($vars));

        // Build meta data to return
        $meta = [];
        if ($this->Input->validates($vars)) {
            if (!isset($vars['meta'] )) {
                return [];
            }

            // Return all package meta fields
            foreach ($vars['meta'] as $key => $value) {
                $meta[] = [
                    'key' => $key,
                    'value' => $value,
                    'encrypted' => 0
                ];
            }
        }

        return $meta;
    }

    /**
     * Deletes the package on the remote server. Sets Input errors on failure,
     * preventing the package from being deleted.
     *
     * @param stdClass $package A stdClass object representing the selected package
     * @see Module::getModule()
     * @see Module::getModuleRow()
     */
    public function deletePackage($package)
    {
    }

    /**
     * Builds and returns rules required to be validated when adding/editing a package.
     *
     * @param array $vars An array of key/value data pairs
     * @return array An array of Input rules suitable for Input::setRules()
     */
    private function getPackageRules(array $vars)
    {
////// For info on writing validation rules, see the
////// docs at https://docs.blesta.com/display/dev/Error+Checking
////
        // Validate the package fields
        $rules = [
            'media_service' => [
                'valid' => [
                    'rule' => true,
                    'message' => Language::_('Mediacp.!error.media_service.valid', true)
                ]
            ],
            'autodj' => [
                'valid' => [
                    'rule' => true,
                    'message' => Language::_('Mediacp.!error.autodj.valid', true)
                ]
            ],
            'servicetype' => [
                'valid' => [
                    'rule' => true,
                    'message' => Language::_('Mediacp.!error.servicetype.valid', true)
                ]
            ],
            'connections' => [
                'valid' => [
                    'rule' => true,
                    'message' => Language::_('Mediacp.!error.connections.valid', true)
                ]
            ],
            'bitrate' => [
                'valid' => [
                    'rule' => true,
                    'message' => Language::_('Mediacp.!error.bitrate.valid', true)
                ]
            ],
            'bandwidth' => [
                'valid' => [
                    'rule' => true,
                    'message' => Language::_('Mediacp.!error.bandwidth.valid', true)
                ]
            ],
            'quota' => [
                'valid' => [
                    'rule' => true,
                    'message' => Language::_('Mediacp.!error.quota.valid', true)
                ]
            ],
            'streamtargets' => [
                'valid' => [
                    'rule' => true,
                    'message' => Language::_('Mediacp.!error.streamtargets.valid', true)
                ]
            ]
        ];

        return $rules;
    }

    /**
     * Returns all fields used when adding/editing a package, including any
     * javascript to execute when the page is rendered with these fields.
     *
     * @param $vars stdClass A stdClass object representing a set of post fields
     * @return ModuleFields A ModuleFields object, containg the fields to
     *  render as well as any additional HTML markup to include
     */
    public function getPackageFields($vars = null)
    {
        Loader::loadHelpers($this, ['Html']);

        $fields = new ModuleFields();

        // Set the Media Service field
        $media_service = $fields->label(Language::_('Mediacp.package_fields.media_service', true), 'mediacp_media_service');
        $media_service->attach(
            $fields->fieldSelect(
                'meta[media_service]',
                $this->getMediaServiceValues(),
                (isset($vars->meta['media_service']) ? $vars->meta['media_service'] : null),
                ['id' => 'mediacp_media_service']
            )
        );
        $fields->setField($media_service);

        // Set the AutoDJ field
        $autodj = $fields->label(Language::_('Mediacp.package_fields.autodj', true), 'mediacp_autodj');
        $autodj->attach(
            $fields->fieldSelect(
                'meta[autodj]',
                $this->getAutoDJValues(),
                (isset($vars->meta['autodj']) ? $vars->meta['autodj'] : null),
                ['id' => 'mediacp_autodj']
            )
        );
        $fields->setField($autodj);

        // Set the Video Service Type field
        $servicetype = $fields->label(Language::_('Mediacp.package_fields.servicetype', true), 'mediacp_servicetype');
        $servicetype->attach(
            $fields->fieldSelect(
                'meta[servicetype]',
                $this->getVideoServiceTypesValues(),
                (isset($vars->meta['servicetype']) ? $vars->meta['servicetype'] : null),
                ['id' => 'mediacp_servicetype']
            )
        );
        // Add tooltip
        $tooltip = $fields->tooltip(Language::_('Mediacp.package_field.tooltip.servicetype', true));
        $servicetype->attach($tooltip);
        $fields->setField($servicetype);

        // Set the Connections field
        $connections = $fields->label(Language::_('Mediacp.package_fields.connections', true), 'mediacp_connections');
        $connections->attach(
            $fields->fieldText(
                'meta[connections]',
                (isset($vars->meta['connections']) ? $vars->meta['connections'] : 100),
                ['id' => 'mediacp_connections']
            )
        );
        // Add tooltip
        $tooltip = $fields->tooltip(Language::_('Mediacp.package_field.tooltip.connections', true));
        $connections->attach($tooltip);
        $fields->setField($connections);

        // Set the Bitrate field
        $bitrate = $fields->label(Language::_('Mediacp.package_fields.bitrate', true), 'mediacp_bitrate');
        $bitrate->attach(
            $fields->fieldSelect(
                'meta[bitrate]',
                $this->getBitrateSelectionValues(),
                (isset($vars->meta['bitrate']) ? $vars->meta['bitrate'] : 128),
                ['id' => 'mediacp_bitrate']
            )
        );
        // Add tooltip
        $tooltip = $fields->tooltip(Language::_('Mediacp.package_field.tooltip.bitrate', true));
        $bitrate->attach($tooltip);
        $fields->setField($bitrate);

        // Set the Bandwidth field
        $bandwidth = $fields->label(Language::_('Mediacp.package_fields.bandwidth', true), 'mediacp_bandwidth');
        $bandwidth->attach(
            $fields->fieldText(
                'meta[bandwidth]',
                (isset($vars->meta['bandwidth']) ? $vars->meta['bandwidth'] : 'Unlimited'),
                ['id' => 'mediacp_bandwidth']
            )
        );
        // Add tooltip
        $tooltip = $fields->tooltip(Language::_('Mediacp.package_field.tooltip.bandwidth', true));
        $bandwidth->attach($tooltip);
        $fields->setField($bandwidth);

        // Set the Quota field
        $quota = $fields->label(Language::_('Mediacp.package_fields.quota', true), 'mediacp_quota');
        $quota->attach(
            $fields->fieldText(
                'meta[quota]',
                (isset($vars->meta['quota']) ? $vars->meta['quota'] : 'Unlimited'),
                ['id' => 'mediacp_quota']
            )
        );
        // Add tooltip
        $tooltip = $fields->tooltip(Language::_('Mediacp.package_field.tooltip.quota', true));
        $quota->attach($tooltip);
        $fields->setField($quota);

        // Set the Stream Targets (Video Services) field
        $streamtargets = $fields->label(Language::_('Mediacp.package_fields.streamtargets', true), 'mediacp_streamtargets');
        $streamtargets->attach(
            $fields->fieldText(
                'meta[streamtargets]',
                (isset($vars->meta['streamtargets']) ? $vars->meta['streamtargets'] : 'Unlimited'),
                ['id' => 'mediacp_streamtargets']
            )
        );
        // Add tooltip
        $tooltip = $fields->tooltip(Language::_('Mediacp.package_field.tooltip.streamtargets', true));
        $streamtargets->attach($tooltip);
        $fields->setField($streamtargets);

        return $fields;
    }

    public function getMediaServiceValues()
    {
        $values = [
            'shoutcast198' => 'Audio - Shoutcast 198',
            'shoutcast2' => 'Audio - Shoutcast 2',
            'icecast' => 'Audio - Icecast 2',
            'icecast_kh' => 'Audio - Icecast 2 KH',
            'AudioTranscoder' => 'Audio - Transcoder',
            'WowzaMedia' => 'Video - Wowza Streaming Engine',
            'Flussonic' => 'Video - Flussonic',
            'NginxRtmp' => 'Video - Nginx-Rtmp',
        ];

        return $values;
    }

    public function getAutoDJValues()
    {
        $values = [
            '' => 'No AutoDJ Service',
            'liquidsoap' => 'Liquidsoap',
            'ices04' => 'Ices 0.4',
            'ices20' => 'Ices 2.0',
            'sctransv1' => 'Shoutcast Transcoder V1',
            'sctransv2' => 'Shoutcast Transcoder V2',
        ];

        return $values;
    }

    public function getVideoServiceTypesValues()
    {
        $values = [
            'Live Streaming',
            'TV Station',
            'Ondemand Streaming',
            'Stream Relay',
        ];

        return $values;
    }
    public function getBitrateSelectionValues()
    {
        $ret = [];
        $bitrates = explode(",", "24,32,40,48,56,64,80,96,112,128,160,192,224,256,320,400,480,560,640,720,800,920,1024,1280,1536,1792,2048,2560,3072,3584,4096,4068,5120,5632,6144,6656,7168,7680,8192,9216,10240,11264,12228,13312,14336,99999");
        foreach($bitrates as $bitrate) $ret[$bitrate] = "{$bitrate} Kbps";
        return $ret;
    }
    /**
     * Adds the service to the remote server. Sets Input errors on failure,
     * preventing the service from being added.
     *
     * @param stdClass $package A stdClass object representing the selected package
     * @param array $vars An array of user supplied info to satisfy the request
     * @param stdClass $parent_package A stdClass object representing the parent
     *  service's selected package (if the current service is an addon service)
     * @param stdClass $parent_service A stdClass object representing the parent
     *  service of the service being added (if the current service is an addon service
     *  service and parent service has already been provisioned)
     * @param string $status The status of the service being added. These include:
     *  - active
     *  - canceled
     *  - pending
     *  - suspended
     * @return array A numerically indexed array of meta fields to be stored for this service containing:
     *  - key The key for this meta field
     *  - value The value for this key
     *  - encrypted Whether or not this field should be encrypted (default 0, not encrypted)
     * @see Module::getModule()
     * @see Module::getModuleRow()
     */
    public function addService(
        $package,
        array $vars = null,
        $parent_package = null,
        $parent_service = null,
        $status = 'pending'
    ) {

        $row = $this->getModuleRow();

        if (!$row) {
            $this->Input->setErrors(
                ['module_row' => ['missing' => Language::_('Mediacp.!error.module_row.missing', true)]]
            );

            return;
        }


        $this->validateService($package, $vars);
        if ($this->Input->errors()) {
            return;
        }

        # Load Client Details
        Loader::loadModels($this, ['Clients']);
        $client = $this->Clients->get($vars['client_id'], false);




        // Only provision the service if 'use_module' is true
        if ($vars['use_module'] == 'true') {

            # Setup API
            $api = $this->getApi($row->meta->hostname, $row->meta->port, $row->meta->usessl, $row->meta->apikey);


            # Create Customer Account or catch already exists gracefully
            $userPassword = $this->generatePassword();
            $response = $api->apiRequest("/api/0/user/store", $params = [
                'name' => $client->first_name . ' ' . $client->last_name,
                'username' => $client->email,
                'user_email' => $client->email,
                'password' => $userPassword,
            ], 'POST');

            # If user already exists, we'll just grab that users information instead
            if ( isset($response->response()->errors->username) || $response->response()->errors->username == 'Username must be unique' ){
                $response = $api->apiRequest("/api/0/user/show", ['username'=>$client->email],'GET');
                $user = $response->response();
                $userPassword = '';
            }else{
                $user = $response->response()->user;
            }

            if ( !$user || empty($user->id) ) {
                $this->Input->setErrors(['api' => ['error' => 'MediaCP User was not created successfully']]);
                return;
            }


            # Create Service
            $params = $this->buildServiceParameters((array)$vars, $package, $user);
            $request = $api->apiRequest("/api/0/media-service/store", $params, 'POST');
            if ( $request->response()->status != 1 ){
                $this->Input->setErrors(['api' => ['error' => "Unable to create service.\n\n{$request->response()->error}\n\nDebugging: " . print_r($response,true)]]);
                return;
            }

            # Lookup service details
            $request = $api->apiRequest("/api/{$request->response()->service_id}/media-service/show", [], 'GET');
            $service = $request->response();


            $vars['name'] = $service->unique_id;
            $vars['portbase'] = $service->portbase;
            $vars['username'] = $client->email;
            $vars['password'] = $userPassword;
            $vars['customer_id'] = $service->user_id;
            $vars['service_id'] = $service->id;
            $vars['service_password'] = $service->adminpassword;
        }

        // Return service fields
        return [
            [
                'key' => 'name',
                'value' => $vars['name'],
                'encrypted' => 0
            ],
            [
                'key' => 'portbase',
                'value' => $vars['portbase'],
                'encrypted' => 0
            ],
            [
                'key' => 'username',
                'value' => $vars['username'],
                'encrypted' => 0
            ],
            [
                'key' => 'password',
                'value' => $vars['password'],
                'encrypted' => 0
            ],
            [
                'key' => 'customer_id',
                'value' => $vars['customer_id'],
                'encrypted' => 0
            ],
            [
                'key' => 'service_id',
                'value' => $vars['service_id'],
                'encrypted' => 0
            ],
            [
                'key' => 'service_password',
                'value' => $vars['service_password'],
                'encrypted' => 0
            ]
        ];
    }

    public function buildServiceParameters($vars, $package, $user)
    {
        $unique_id = (int) !empty($vars['name']) ? $vars['name'] : null;
        $portbase = (int) !empty($vars['portbase']) ? $vars['portbase'] : null;

        $server = new stdClass();
        $server->userid = $user->id;
        if ( !empty($unique_id) ) $server->unique_id = $unique_id;
        if ( !empty($portbase) && $portbase > 1024 && $portbase < 65000 ) $server->portbase = $portbase;
        $server->plugin = $package->meta->media_service;
        $server->maxuser = $package->meta->connections;
        $server->bitrate = $package->meta->bitrate >= 24 ? $package->meta->bitrate : 128;
        $server->bandwidth = strtolower($package->meta->bandwidth) == 'unlimited' ? 0 : $package->meta->bandwidth;
        $server->quota = strtolower($package->meta->quota) == 'unlimited' ? 0 : $package->meta->quota;
        $server->stream_targets_limit = strtolower($package->meta->streamtargets) == 'unlimited' ? -1 : $package->meta->streamtargets;

        switch($server->plugin){
            case 'shoutcast198':
            case 'shoutcast2':
                $server->password = $this->generatePassword();
                $server->adminpassword = $this->generatePassword();
                break;

            case 'icecast':
            case 'icecast_kh':
                $server->source_password = $this->generatePassword();
                $server->password = $this->generatePassword();
                break;
        }


        # Source Plugin
        if (in_array($server->plugin, ['shoutcast198','shoutcast2','icecast','icecast_kh']) && !empty($package->meta->autodj_type)) {
            $server->sourceplugin = $package->meta->autodj_type;
        }

        return (array) $server;
    }

    /**
     * Edits the service on the remote server. Sets Input errors on failure,
     * preventing the service from being edited.
     *
     * @param stdClass $package A stdClass object representing the current package
     * @param stdClass $service A stdClass object representing the current service
     * @param array $vars An array of user supplied info to satisfy the request
     * @param stdClass $parent_package A stdClass object representing the parent
     *  service's selected package (if the current service is an addon service)
     * @param stdClass $parent_service A stdClass object representing the parent
     *  service of the service being edited (if the current service is an addon service)
     * @return array A numerically indexed array of meta fields to be stored for this service containing:
     *  - key The key for this meta field
     *  - value The value for this key
     *  - encrypted Whether or not this field should be encrypted (default 0, not encrypted)
     * @see Module::getModule()
     * @see Module::getModuleRow()
     */
    public function editService($package, $service, array $vars = null, $parent_package = null, $parent_service = null)
    {

        $row = $this->getModuleRow();

        if (!$row) {
            $this->Input->setErrors(
                ['module_row' => ['missing' => Language::_('Mediacp.!error.module_row.missing', true)]]
            );

            return;
        }

        $serviceFields = $this->serviceFieldsToObject($service->fields);



        $this->validateService($package, $vars, true);

        if ($this->Input->errors()) {
            return;
        }

        // Only update the service if 'use_module' is true


        // Only provision the service if 'use_module' is true
        if ($vars['use_module'] == 'true') {

            # Load Client Details
            Loader::loadModels($this, ['Clients']);
            $client = $this->Clients->get($vars['client_id'], false);

            # Setup API
            $api = $this->getApi($row->meta->hostname, $row->meta->port, $row->meta->usessl, $row->meta->apikey);

            # Lookup user information
            $response = $api->apiRequest("/api/0/user/show/{$serviceFields->customer_id}");

            $user = $response->response();

            if ( !$user || empty($user->id) ) {
                $this->Input->setErrors(['api' => ['error' => 'MediaCP User was not located successfully']]);
                return;
            }


            # Update Service
            $params = $this->buildServiceParameters((array)$vars, $package, $user);
            $request = $api->apiRequest("/api/{$serviceFields->service_id}/media-service/update", $params, 'POST');

            if ( $request->response()->status != 1 ){
                $this->Input->setErrors(['api' => ['error' => "Unable to update service.\n\n{$request->response()->error}\n\nDebugging: " . print_r($response,true)]]);
                return;
            }

            # Lookup service details
            $request = $api->apiRequest("/api/{$serviceFields->service_id}/media-service/show");
            $service = $request->response();
            $vars['name'] = $service->unique_id;
            $vars['portbase'] = $service->portbase;
            $vars['username'] = $client->email;
            $vars['customer_id'] = $service->user_id;
            $vars['service_id'] = $service->id;
            $vars['service_password'] = $service->password;
        }

        // Return all the service fields
        $encrypted_fields = [];
        $return = [];
        $fields = ['name','portbase','username','password','customer_id','service_id','service_password'];
        foreach ($fields as $field) {
            if (isset($vars[$field]) || isset($serviceFields->$field)) {
                $return[] = [
                    'key' => $field,
                    'value' => $vars[$field] ?? $serviceFields->$field,
                    'encrypted' => (in_array($field, $encrypted_fields) ? 1 : 0)
                ];
            }
        }

        return $return;
    }

    /**
     * Suspends the service on the remote server. Sets Input errors on failure,
     * preventing the service from being suspended.
     *
     * @param stdClass $package A stdClass object representing the current package
     * @param stdClass $service A stdClass object representing the current service
     * @param stdClass $parent_package A stdClass object representing the parent
     *  service's selected package (if the current service is an addon service)
     * @param stdClass $parent_service A stdClass object representing the parent
     *  service of the service being suspended (if the current service is an addon service)
     * @return mixed null to maintain the existing meta fields or a numerically
     *  indexed array of meta fields to be stored for this service containing:
     *  - key The key for this meta field
     *  - value The value for this key
     *  - encrypted Whether or not this field should be encrypted (default 0, not encrypted)
     * @see Module::getModule()
     * @see Module::getModuleRow()
     */
    public function suspendService($package, $service, $parent_package = null, $parent_service = null)
    {

        if (($row = $this->getModuleRow())) {

            $serviceFields = $this->serviceFieldsToObject($service->fields);
            # Setup API
            $api = $this->getApi($row->meta->hostname, $row->meta->port, $row->meta->usessl, $row->meta->apikey);

            $response = $api->apiRequest("/api/{$serviceFields->service_id}/media-service/suspend", [], 'GET');
            if ( $response->status() != 200 ){
                $this->Input->setErrors(['api' => ['error' => "Unable to suspend service.\n\n{$response->response()->error}\n\nDebugging: " . print_r($response,true)]]);
                return;
            }

        }

        return null;
    }

    /**
     * Unsuspends the service on the remote server. Sets Input errors on failure,
     * preventing the service from being unsuspended.
     *
     * @param stdClass $package A stdClass object representing the current package
     * @param stdClass $service A stdClass object representing the current service
     * @param stdClass $parent_package A stdClass object representing the parent
     *  service's selected package (if the current service is an addon service)
     * @param stdClass $parent_service A stdClass object representing the parent
     *  service of the service being unsuspended (if the current service is an addon service)
     * @return mixed null to maintain the existing meta fields or a numerically
     *  indexed array of meta fields to be stored for this service containing:
     *  - key The key for this meta field
     *  - value The value for this key
     *  - encrypted Whether or not this field should be encrypted (default 0, not encrypted)
     * @see Module::getModule()
     * @see Module::getModuleRow()
     */
    public function unsuspendService($package, $service, $parent_package = null, $parent_service = null)
    {

        if (($row = $this->getModuleRow())) {

            $serviceFields = $this->serviceFieldsToObject($service->fields);
            # Setup API
            $api = $this->getApi($row->meta->hostname, $row->meta->port, $row->meta->usessl, $row->meta->apikey);

            $response = $api->apiRequest("/api/{$serviceFields->service_id}/media-service/unsuspend", [], 'GET');
            if ( $response->status() != 200 ){
                $this->Input->setErrors(['api' => ['error' => "Unable to unsuspend service.\n\n{$response->response()->error}\n\nDebugging: " . print_r($response,true)]]);
                return;
            }

        }

        return null;
    }

    /**
     * Cancels the service on the remote server. Sets Input errors on failure,
     * preventing the service from being canceled.
     *
     * @param stdClass $package A stdClass object representing the current package
     * @param stdClass $service A stdClass object representing the current service
     * @param stdClass $parent_package A stdClass object representing the parent
     *  service's selected package (if the current service is an addon service)
     * @param stdClass $parent_service A stdClass object representing the parent
     *  service of the service being canceled (if the current service is an addon service)
     * @return mixed null to maintain the existing meta fields or a numerically
     *  indexed array of meta fields to be stored for this service containing:
     *  - key The key for this meta field
     *  - value The value for this key
     *  - encrypted Whether or not this field should be encrypted (default 0, not encrypted)
     * @see Module::getModule()
     * @see Module::getModuleRow()
     */
    public function cancelService($package, $service, $parent_package = null, $parent_service = null)
    {

        if (($row = $this->getModuleRow())) {

            $serviceFields = $this->serviceFieldsToObject($service->fields);
            # Setup API
            $api = $this->getApi($row->meta->hostname, $row->meta->port, $row->meta->usessl, $row->meta->apikey);

            $response = $api->apiRequest("/api/{$serviceFields->service_id}/media-service/delete", [], 'GET');
            if ( $response->status() != 200 ){
                $this->Input->setErrors(['api' => ['error' => "Unable to delete service.\n\n{$response->response()->error}\n\nDebugging: " . print_r($response,true)]]);
                return;
            }

            # Delete user account if they have no other services
            $servers = $api->apiRequest("/api/0/media-service/list", ['user_id'=>$serviceFields->customer_id], 'GET');

            if ( !isset($servers->response()->message) && is_array($servers->response()) && count($servers->response()) === 0 ){
                $api->apiRequest("/api/0/user/delete/{$serviceFields->customer_id}", [], 'GET');
            }

        }

        return null;
    }

    /**
     * Allows the module to perform an action when the service is ready to renew.
     * Sets Input errors on failure, preventing the service from renewing.
     *
     * @param stdClass $package A stdClass object representing the current package
     * @param stdClass $service A stdClass object representing the current service
     * @param stdClass $parent_package A stdClass object representing the parent
     *  service's selected package (if the current service is an addon service)
     * @param stdClass $parent_service A stdClass object representing the parent
     *  service of the service being renewed (if the current service is an addon service)
     * @return mixed null to maintain the existing meta fields or a numerically
     *  indexed array of meta fields to be stored for this service containing:
     *  - key The key for this meta field
     *  - value The value for this key
     *  - encrypted Whether or not this field should be encrypted (default 0, not encrypted)
     * @see Module::getModule()
     * @see Module::getModuleRow()
     */
    public function renewService($package, $service, $parent_package = null, $parent_service = null)
    {
////        if (($row = $this->getModuleRow())) {
////            $api = $this->getApi(
////                $row->meta->host_name,
////                $row->meta->user_name,
////                $row->meta->password,
////                $row->meta->use_ssl
////            );
////
////            $service_fields = $this->serviceFieldsToObject($service->fields);
////        }
////
        return null;
    }

    /**
     * Attempts to validate service info. This is the top-level error checking method. Sets Input errors on failure.
     *
     * @param stdClass $package A stdClass object representing the selected package
     * @param array $vars An array of user supplied info to satisfy the request
     * @return bool True if the service validates, false otherwise. Sets Input errors when false.
     */
    public function validateService($package, array $vars = null)
    {
        $this->Input->setRules($this->getServiceRules($vars));
        return $this->Input->validates($vars);
    }

    /**
     * Attempts to validate an existing service against a set of service info updates. Sets Input errors on failure.
     *
     * @param stdClass $service A stdClass object representing the service to validate for editing
     * @param array $vars An array of user-supplied info to satisfy the request
     * @return bool True if the service update validates or false otherwise. Sets Input errors when false.
     */
    public function validateServiceEdit($service, array $vars = null)
    {
        $this->Input->setRules($this->getServiceRules($vars, true));
        return $this->Input->validates($vars);
    }

    /**
     * Returns the rule set for adding/editing a service
     *
     * @param array $vars A list of input vars
     * @param bool $edit True to get the edit rules, false for the add rules
     * @return array Service rules
     */
    private function getServiceRules(array $vars = null, $edit = false)
    {
////// For info on writing validation rules, see the
////// docs at https://docs.blesta.com/display/dev/Error+Checking
////
        // Validate the service fields
        $rules = [
            'name' => [
                'valid' => [
                    'if_set' => $edit,
                    'rule' => true,
                    'message' => Language::_('Mediacp.!error.name.valid', true)
                ]
            ],
            'portbase' => [
                'valid' => [
                    'if_set' => $edit,
                    'rule' => true,
                    'message' => Language::_('Mediacp.!error.portbase.valid', true)
                ]
            ],
            'username' => [
                'valid' => [
                    'if_set' => $edit,
                    'rule' => true,
                    'message' => Language::_('Mediacp.!error.username.valid', true)
                ]
            ],
            'password' => [
                'valid' => [
                    'if_set' => $edit,
                    'rule' => true,
                    'message' => Language::_('Mediacp.!error.password.valid', true)
                ]
            ],
            'customer_id' => [
                'valid' => [
                    'if_set' => $edit,
                    'rule' => true,
                    'message' => Language::_('Mediacp.!error.customer_id.valid', true)
                ]
            ],
            'service_id' => [
                'valid' => [
                    'if_set' => $edit,
                    'rule' => true,
                    'message' => Language::_('Mediacp.!error.service_id.valid', true)
                ]
            ],
            'service_password' => [
                'valid' => [
                    'if_set' => $edit,
                    'rule' => true,
                    'message' => Language::_('Mediacp.!error.service_password.valid', true)
                ]
            ]
        ];

        // Unset irrelevant rules when editing a service
        if ($edit) {
            $edit_fields = [];

            foreach ($rules as $field => $rule) {
                if (!in_array($field, $edit_fields)) {
                    unset($rules[$field]);
                }
            }
        }

        return $rules;
    }

    /**
     * Updates the package for the service on the remote server. Sets Input
     * errors on failure, preventing the service's package from being changed.
     *
     * @param stdClass $package_from A stdClass object representing the current package
     * @param stdClass $package_to A stdClass object representing the new package
     * @param stdClass $service A stdClass object representing the current service
     * @param stdClass $parent_package A stdClass object representing the parent
     *  service's selected package (if the current service is an addon service)
     * @param stdClass $parent_service A stdClass object representing the parent
     *  service of the service being changed (if the current service is an addon service)
     * @return mixed null to maintain the existing meta fields or a numerically
     *  indexed array of meta fields to be stored for this service containing:
     *  - key The key for this meta field
     *  - value The value for this key
     *  - encrypted Whether or not this field should be encrypted (default 0, not encrypted)
     * @see Module::getModule()
     * @see Module::getModuleRow()
     */
    public function changeServicePackage(
        $package_from,
        $package_to,
        $service,
        $parent_package = null,
        $parent_service = null
    ) {
        if (($row = $this->getModuleRow())) {
////            $api = $this->getApi(
////                $row->meta->host_name,
////                $row->meta->user_name,
////                $row->meta->password,
////                ($row->meta->use_ssl == 'true')
////            );
////
        }

        return null;
    }
////
////    /**
////     * Initializes the MediacpApi and returns an instance of that object.
////     *
////     * @param string $hostname Placeholder description
////     * @param string $port Placeholder description
////     * @param string $usessl Placeholder description
////     * @param string $apikey Placeholder description
////     * @return MediacpApi The MediacpApi instance
////     */
    private function getApi($hostname,$port,$usessl = 'true',$apikey)
    {
        Loader::load(dirname(__FILE__) . DS . 'apis' . DS . 'mediacp_api.php');

//        See the apis/mediacp_api.php and apis/mediacp_response.php files
        $api = new MediacpApi($this->getModule(), $hostname,$port,$usessl == 'true',$apikey);

        return $api;
    }

    /**
     * Generates a username from the given host name.
     *
     * @param string $host_name The host name to use to generate the username
     * @return string The username generated from the given hostname
     */
    private function generateUsername($host_name)
    {
        // Remove everything except letters and numbers from the domain
        // ensure no number appears in the beginning
        $username = ltrim(preg_replace('/[^a-z0-9]/i', '', $host_name), '0123456789');

        $length = strlen($username);
        $pool = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $pool_size = strlen($pool);

        if ($length < 5) {
            for ($i = $length; $i < 8; $i++) {
                $username .= substr($pool, mt_rand(0, $pool_size - 1), 1);
            }
            $length = strlen($username);
        }

        $username = substr($username, 0, min($length, 8));

        // Check for an existing user account
        $row = $this->getModuleRow();

        if ($row) {
            $api = $this->getApi($row->meta->host_name, $row->meta->user_name, $row->meta->password, $row->meta->use_ssl);

            // Username exists, create another instead
            if ($api->accountExists($username)) {
                for ($i = 0; strlen((string)$i) < 8; $i++) {
                    $new_username = substr($username, 0, -strlen((string)$i)) . $i;
                    if (!$api->accountExists($new_username)) {
                        $username = $new_username;
                        break;
                    }
                }
            }
        }

        return $username;
    }

    /**
     * Generates a password.
     *
     * @param int $min_length The minimum character length for the password (5 or larger)
     * @param int $max_length The maximum character length for the password (14 or fewer)
     * @return string The generated password
     */
    private function generatePassword($min_length = 10, $max_length = 14)
    {
        $pool = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $pool_size = strlen($pool);
        $length = mt_rand(max($min_length, 5), min($max_length, 14));
        $password = '';

        for ($i = 0; $i < $length; $i++) {
            $password .= substr($pool, mt_rand(0, $pool_size - 1), 1);
        }

        return $password;
    }


    /**
     * Returns all tabs to display to a client when managing a service whose
     * package uses this module
     *
     * @param stdClass $package A stdClass object representing the selected package
     * @return array An array of tabs in the format of method => title.
     *  Example: array('methodName' => "Title", 'methodName2' => "Title2")
     */
    public function getClientTabs($package)
    {
        return [
        #   'tabClientServiceInfo' => Language::_('Mediacp.client_service_info', true),
            'login' => Language::_('Mediacp.login', true)
        ];
    }

    /**
     * Returns all tabs to display to an admin when managing a service whose
     * package uses this module
     *
     * @param stdClass $package A stdClass object representing the selected package
     * @return array An array of tabs in the format of method => title.
     *  Example: array('methodName' => "Title", 'methodName2' => "Title2")
     */
    public function getAdminTabs($package)
    {
        return [
        ];
    }


    /**
     * login
     *
     * @param stdClass $package A stdClass object representing the current package
     * @param stdClass $service A stdClass object representing the current service
     * @param array $get Any GET parameters
     * @param array $post Any POST parameters
     * @param array $files Any FILES parameters
     * @return string The string representing the contents of this tab
     */
    public function login(
        $package,
        $service,
        array $get = null,
        array $post = null,
        array $files = null
    ) {
        $this->view = new View('stop', 'default');
        $this->view->base_uri = $this->base_uri;
        // Load the helpers required for this view
        Loader::loadHelpers($this, ['Form', 'Html']);

        $service_fields = $this->serviceFieldsToObject($service->fields);

        if (!empty($post)) {

            // Perform any post actions

            if ($this->Services->errors()) {
                $this->Input->setErrors($this->Services->errors());
            }

            $vars = (object)$post;
        }

        $this->view->set('service_fields', $service_fields);
        $this->view->set('service_id', $service->id);
        $this->view->set('client_id', $service->client_id);
        $this->view->set('vars', (isset($vars) ? $vars : new stdClass()));

        $module = $this->getModuleRow();

        $url = ($module->meta->usessl=="true"?"https://":"http://") . $module->meta->hostname . ":" . $module->meta->port . "/?username={$service_fields->username}&password={$service_fields->password}";
        header("Location: {$url}");
        exit;
    }

    /**
     * Returns all fields to display to an admin attempting to add a service with the module
     *
     * @param stdClass $package A stdClass object representing the selected package
     * @param $vars stdClass A stdClass object representing a set of post fields
     * @return ModuleFields A ModuleFields object, containg the fields to render
     *  as well as any additional HTML markup to include
     */
    public function getAdminAddFields($package, $vars = null)
    {
        Loader::loadHelpers($this, ['Html']);

        $fields = new ModuleFields();

        // Set the Service Name field
        $name = $fields->label(Language::_('Mediacp.service_fields.name', true), 'mediacp_name');
        $name->attach(
            $fields->fieldText(
                'name',
                (isset($vars->name) ? $vars->name : null),
                ['id' => 'mediacp_name']
            )
        );
        $fields->setField($name);

        // Set the Portbase field
        $portbase = $fields->label(Language::_('Mediacp.service_fields.portbase', true), 'mediacp_portbase');
        $portbase->attach(
            $fields->fieldText(
                'portbase',
                (isset($vars->portbase) ? $vars->portbase : null),
                ['id' => 'mediacp_portbase']
            )
        );
        $fields->setField($portbase);


        return $fields;
    }

    /**
     * Returns all fields to display to an admin attempting to edit a service with the module
     *
     * @param stdClass $package A stdClass object representing the selected package
     * @param $vars stdClass A stdClass object representing a set of post fields
     * @return ModuleFields A ModuleFields object, containg the fields to render
     *  as well as any additional HTML markup to include
     */
    public function getAdminEditFields($package, $vars = null)
    {
        Loader::loadHelpers($this, ['Html']);

        $fields = new ModuleFields();

        // Set the Service Name field
        $name = $fields->label(Language::_('Mediacp.service_fields.name', true), 'mediacp_name');
        $name->attach(
            $fields->fieldText(
                'name',
                (isset($vars->name) ? $vars->name : null),
                ['id' => 'mediacp_name']
            )
        );
        $fields->setField($name);

        // Set the Portbase field
        $portbase = $fields->label(Language::_('Mediacp.service_fields.portbase', true), 'mediacp_portbase');
        $portbase->attach(
            $fields->fieldText(
                'portbase',
                (isset($vars->portbase) ? $vars->portbase : null),
                ['id' => 'mediacp_portbase']
            )
        );
        $fields->setField($portbase);

        // Set the Username field
        $username = $fields->label(Language::_('Mediacp.service_fields.username', true), 'mediacp_username');
        $username->attach(
            $fields->fieldText(
                'username',
                (isset($vars->username) ? $vars->username : null),
                ['id' => 'mediacp_username']
            )
        );
        $fields->setField($username);

        // Set the Password field
        $password = $fields->label(Language::_('Mediacp.service_fields.password', true), 'mediacp_password');
        $password->attach(
            $fields->fieldText(
                'password',
                (isset($vars->password) ? $vars->password : null),
                ['id' => 'mediacp_password']
            )
        );
        $fields->setField($password);

        // Set the Customer ID field
        $customer_id = $fields->label(Language::_('Mediacp.service_fields.customer_id', true), 'mediacp_customer_id');
        $customer_id->attach(
            $fields->fieldText(
                'customer_id',
                (isset($vars->customer_id) ? $vars->customer_id : null),
                ['id' => 'mediacp_customer_id']
            )
        );
        $fields->setField($customer_id);

        // Set the Service ID field
        $service_id = $fields->label(Language::_('Mediacp.service_fields.service_id', true), 'mediacp_service_id');
        $service_id->attach(
            $fields->fieldText(
                'service_id',
                (isset($vars->service_id) ? $vars->service_id : null),
                ['id' => 'mediacp_service_id']
            )
        );
        $fields->setField($service_id);

        // Set the Service ID field
        $service_password = $fields->label(Language::_('Mediacp.service_fields.service_password', true), 'service_password');
        $service_password->attach(
            $fields->fieldText(
                'service_password',
                (isset($vars->service_password) ? $vars->service_password : null),
                ['id' => 'mediacp_service_password']
            )
        );
        $fields->setField($service_password);

        return $fields;
    }

    /**
     * Returns all fields to display to a client attempting to add a service with the module
     *
     * @param stdClass $package A stdClass object representing the selected package
     * @param $vars stdClass A stdClass object representing a set of post fields
     * @return ModuleFields A ModuleFields object, containg the fields to render
     *  as well as any additional HTML markup to include
     */
    public function getClientAddFields($package, $vars = null)
    {
        Loader::loadHelpers($this, ['Html']);

        $fields = new ModuleFields();

        // Set the Service Name field
        $name = $fields->label(Language::_('Mediacp.service_fields.name', true), 'mediacp_name');
        $name->attach(
            $fields->fieldText(
                'name',
                (isset($vars->name) ? $vars->name : null),
                ['id' => 'mediacp_name']
            )
        );
        $fields->setField($name);

        // Set the Portbase field
        $portbase = $fields->label(Language::_('Mediacp.service_fields.portbase', true), 'mediacp_portbase');
        $portbase->attach(
            $fields->fieldText(
                'portbase',
                (isset($vars->portbase) ? $vars->portbase : null),
                ['id' => 'mediacp_portbase']
            )
        );
        $fields->setField($portbase);

        // Set the Username field
        $username = $fields->label(Language::_('Mediacp.service_fields.username', true), 'mediacp_username');
        $username->attach(
            $fields->fieldText(
                'username',
                (isset($vars->username) ? $vars->username : null),
                ['id' => 'mediacp_username']
            )
        );
        $fields->setField($username);

        // Set the Password field
        $password = $fields->label(Language::_('Mediacp.service_fields.password', true), 'mediacp_password');
        $password->attach(
            $fields->fieldText(
                'password',
                (isset($vars->password) ? $vars->password : null),
                ['id' => 'mediacp_password']
            )
        );
        $fields->setField($password);

        // Set the Customer ID field
        $customer_id = $fields->label(Language::_('Mediacp.service_fields.customer_id', true), 'mediacp_customer_id');
        $customer_id->attach(
            $fields->fieldText(
                'customer_id',
                (isset($vars->customer_id) ? $vars->customer_id : null),
                ['id' => 'mediacp_customer_id']
            )
        );
        $fields->setField($customer_id);

        // Set the Service ID field
        $service_id = $fields->label(Language::_('Mediacp.service_fields.service_id', true), 'mediacp_service_id');
        $service_id->attach(
            $fields->fieldText(
                'service_id',
                (isset($vars->service_id) ? $vars->service_id : null),
                ['id' => 'mediacp_service_id']
            )
        );
        $fields->setField($service_id);

        // Set the Service Password field
        $service_password = $fields->label(Language::_('Mediacp.service_fields.service_id', true), 'mediacp_service_password');
        $service_password->attach(
            $fields->fieldText(
                'service_id',
                (isset($vars->service_id) ? $vars->service_password : null),
                ['id' => 'mediacp_service_password']
            )
        );
        $fields->setField($service_password);

        return $fields;
    }

    /**
     * Returns all fields to display to a client attempting to edit a service with the module
     *
     * @param stdClass $package A stdClass object representing the selected package
     * @param $vars stdClass A stdClass object representing a set of post fields
     * @return ModuleFields A ModuleFields object, containg the fields to render
     *  as well as any additional HTML markup to include
     */
    public function getClientEditFields($package, $vars = null)
    {
        Loader::loadHelpers($this, ['Html']);

        $fields = new ModuleFields();

        // Set the Service Name field
        $name = $fields->label(Language::_('Mediacp.service_fields.name', true), 'mediacp_name');
        $name->attach(
            $fields->fieldText(
                'name',
                (isset($vars->name) ? $vars->name : null),
                ['id' => 'mediacp_name']
            )
        );
        $fields->setField($name);

        // Set the Portbase field
        $portbase = $fields->label(Language::_('Mediacp.service_fields.portbase', true), 'mediacp_portbase');
        $portbase->attach(
            $fields->fieldText(
                'portbase',
                (isset($vars->portbase) ? $vars->portbase : null),
                ['id' => 'mediacp_portbase']
            )
        );
        $fields->setField($portbase);

        // Set the Username field
        $username = $fields->label(Language::_('Mediacp.service_fields.username', true), 'mediacp_username');
        $username->attach(
            $fields->fieldText(
                'username',
                (isset($vars->username) ? $vars->username : null),
                ['id' => 'mediacp_username']
            )
        );
        $fields->setField($username);

        // Set the Password field
        $password = $fields->label(Language::_('Mediacp.service_fields.password', true), 'mediacp_password');
        $password->attach(
            $fields->fieldText(
                'password',
                (isset($vars->password) ? $vars->password : null),
                ['id' => 'mediacp_password']
            )
        );
        $fields->setField($password);

        // Set the Customer ID field
        $customer_id = $fields->label(Language::_('Mediacp.service_fields.customer_id', true), 'mediacp_customer_id');
        $customer_id->attach(
            $fields->fieldText(
                'customer_id',
                (isset($vars->customer_id) ? $vars->customer_id : null),
                ['id' => 'mediacp_customer_id']
            )
        );
        $fields->setField($customer_id);

        // Set the Service ID field
        $service_id = $fields->label(Language::_('Mediacp.service_fields.service_id', true), 'mediacp_service_id');
        $service_id->attach(
            $fields->fieldText(
                'service_id',
                (isset($vars->service_id) ? $vars->service_id : null),
                ['id' => 'mediacp_service_id']
            )
        );
        $fields->setField($service_id);

        // Set the Service Password field
        $service_password = $fields->label(Language::_('Mediacp.service_fields.service_id', true), 'mediacp_service_password');
        $service_password->attach(
            $fields->fieldText(
                'service_id',
                (isset($vars->service_id) ? $vars->service_password : null),
                ['id' => 'mediacp_service_password']
            )
        );
        $fields->setField($service_password);

        return $fields;
    }



    /**
     * Fetches the HTML content to display when viewing the service info in the
     * client interface.
     *
     * @param stdClass $service A stdClass object representing the service
     * @param stdClass $package A stdClass object representing the service's package
     * @return string HTML content containing information to display when viewing the service info
     */
    public function getClientServiceInfo($service, $package)
    {
        $row = $this->getModuleRow();

        // Load the view into this object, so helpers can be automatically added to the view
        $this->view = new View('client_service_info', 'default');
        $this->view->base_uri = $this->base_uri;
        $this->view->setDefaultView('components' . DS . 'modules' . DS . 'mediacp' . DS);

        // Load the helpers required for this view
        Loader::loadHelpers($this, ['Form', 'Html']);

        $this->view->set('module_row', $row);
        $this->view->set('package', $package);
        $this->view->set('service', $service);
        $this->view->set('service_fields', $this->serviceFieldsToObject($service->fields));
        $this->view->set('isVideoService', !in_array($package->meta->media_service, ['shoutcast198','shoutcast2','icecast','icecast_kh']) );

        return $this->view->fetch();
    }
    /**
     * Fetches the HTML content to display when viewing the service info in the
     * admin interface.
     *
     * @param stdClass $service A stdClass object representing the service
     * @param stdClass $package A stdClass object representing the service's package
     * @return string HTML content containing information to display when viewing the service info
     */
    public function getAdminServiceInfo($service, $package)
    {
        $row = $this->getModuleRow();

        // Load the view into this object, so helpers can be automatically added to the view
        $this->view = new View('admin_service_info', 'default');
        $this->view->base_uri = $this->base_uri;
        $this->view->setDefaultView('components' . DS . 'modules' . DS . 'mediacp' . DS);

        // Load the helpers required for this view
        Loader::loadHelpers($this, ['Form', 'Html']);

        $this->view->set('module_row', $row);
        $this->view->set('package', $package);
        $this->view->set('service', $service);
        $this->view->set('service_fields', $this->serviceFieldsToObject($service->fields));
        $this->view->set('isVideoService', !in_array($package->meta->media_service, ['shoutcast198','shoutcast2','icecast','icecast_kh']) );

        return $this->view->fetch();
    }

}

<?php
/**
 * OO AJAX Implementation for PHP
 *
 * @category   HTML
 * @package    AJAX
 * @author     Joshua Eichorn <josh@bluga.net>
 * @copyright  2005 Joshua Eichorn
 * @license    http://www.opensource.org/licenses/lgpl-license.php  LGPL
 * @version    Release: @package_version@
 */

/**
 * Require the main AJAX library
 */
include_once 'HTML/AJAX.php';

/**
 * Class for creating an external AJAX server
 *
 * Can be used in 2 different modes, registerClass mode where you create an instance of the server and add the classes that will be registered
 * and then run handle request
 *
 * Or you can extend it and add init{className} methods for each class you want to export
 *
 * Client js generation is exposed through 2 _GET params client and stub
 *  Setting the _GET param client to `all` will give you all the js classes needed
 *  Setting the _GET param stub to `all` will give you stubs of all registered classes, you can also set it too just 1 class
 *
 * @category   HTML
 * @package    AJAX
 * @author     Joshua Eichorn <josh@bluga.net>
 * @copyright  2005 Joshua Eichorn
 * @license    http://www.opensource.org/licenses/lgpl-license.php  LGPL
 * @version    Release: @package_version@
 * @link       http://pear.php.net/package/PackageName
 */
class HTML_AJAX_Server 
{

    /**
     * Client options array if set to true the code looks at _GET
     * @var bool|array
     */
    var $options = true;

    /**
     * HTML_AJAX instance
     * @var HTML_AJAX
     */
    var $ajax;

    /**
     * Set to true if your extending the server to add init{className methods}
     * @var boolean
     * @access  public
     */
    var $initMethods = false;

    /**
     * Location on filesystem of client javascript library
     * @var false|string if false the default pear data dir location is used
     */
    var $clientJsLocation = false;

    /** 
     * An array of options that tell the server howto Cache output
     *
     * The rules are functions that make etag hash used to see if the client needs to download updated content
     * If you extend this class you can make your own rule function the naming convention is _cacheRule{RuleName}
     *
     * <code>
     * array(
     *  'httpCacheClient' => true,   // send 304 headers for responses to ?client=* requests
     *  'ClientCacheRule' => 'File', // create a hash from file names and modified times, options: file|content
     *  'ClientCacheExpects'=> 'files', // what type of content to send to the hash function, options: files|classes|content
     *  'httpCacheStub'   => true,   // send 304 headers for responses to ?stub=* requests
     *  'StubCacheRule'   => 'Api',  // create a hash from the exposed api, options: api|content
     *  'StubCacheExpects'=> 'classes', // what type of content to send to the hash function, options: files|classes|content
     * )
     * </code>
     *
     * @var array
     * @access  public
     */
    var $cacheOptions = array(
        'httpCacheClient'       => true, 
        'ClientCacheRule'       => 'file',
        'ClientCacheExpects'    => 'files',
        'httpCacheStub'         => true, 
        'StubCacheRule'         => 'api', 
        'StubCacheExpects'      => 'classes', 
        );

    /**
     * Javascript library names and there path 
     *
     * the return of $this->clientJsLocation(), is prepended before running readfile on them
     *
     * @access  public
     * @var array
     */
    var $javascriptLibraries = array(
        'all'           =>  'HTML_AJAX.js',
        'html_ajax'     =>  'HTML_AJAX.js',
        'html_ajax_lite'=>  'HTML_AJAX_lite.js',
        'json'          =>  'serializer/JSON.js',
        'request'       =>  'Request.js',
        'main'          =>  array('Main.js','clientPool.js'),
        'httpclient'    =>  'HttpClient.js',
        'dispatcher'    =>  'Dispatcher.js',
        'util'          =>  'util.js',
        'loading'       =>  'Loading.js',
        'phpserializer' =>  'serializer/phpSerializer.js',
        'urlserializer' =>  'serializer/UrlSerializer.js',
        'haserializer'  =>  'serializer/haSerializer.js',
        'priorityqueue' =>  'priorityQueue.js',
        'clientpool'    =>  'clientPool.js',
        'iframe'        =>  'IframeXHR.js',
        'behavior'      =>  array('behavior/behavior.js','behavior/cssQuery-p.js')
    );

    /**
     * Custom paths to use for javascript libraries, if not set {@link clientJsLocation} is used to find the system path
     *
     * @access public
     * @var array
     * @see registerJsLibrary
     */
    var $javascriptLibraryPaths = array();

    /**
     * Array of className => init methods to call, generated from constructor from initClassName methods
     *
     * @access protected
     */
    var $_initLookup = array();
    

    /**
     * Constructor creates the HTML_AJAX instance
     */
    function __construct() 
    {
        $this->ajax = new HTML_AJAX();

        // parameters for HTML::AJAX
        $parameters = array('stub', 'client');

        // keep in the query string all the parameters that don't belong to AJAX
        // we remove all string like "parameter=something&". Final '&' can also
        // be '&amp;' (to be sure) and is optional. '=something' is optional too.
        $querystring = preg_replace('/(' . join('|', $parameters) . ')(?:=[^&]*(?:&(?:amp;)?|$))?/', '', $_SERVER['QUERY_STRING']);

        // call the server with this query string
        $this->ajax->serverUrl = htmlentities($_SERVER['PHP_SELF']) .'?'. htmlentities($querystring);
        
        $methods = get_class_methods($this);
        foreach($methods as $method) {
            if (preg_match('/^init([a-zA-Z0-9_]+)$/',$method,$match)) {
                $this->_initLookup[strtolower($match[1])] = $method;
            }
        }
    }

    /**
     * Handle a client request, either generating a client or having HTML_AJAX handle the request
     *
     * @return boolean true if request was handled, false otherwise
     */
    function handleRequest() 
    {
        if ($this->options == true) {
            $this->_loadOptions();
        }
        //basically a hook for iframe but allows processing of data earlier
        $this->ajax->populatePayload();
        if (!isset($_GET['c']) && (count($this->options['client']) > 0 || count($this->options['stub']) > 0) ) {
            $this->generateClient();
            return true;
        } else {
            $this->_init($this->_cleanIdentifier($_GET['c']));
            return $this->ajax->handleRequest();
        }
    }

    /**
     * Register method passthrough to HTML_AJAX
     *
     * @see HTML_AJAX::registerClass for docs
     */
    function registerClass(&$instance, $exportedName = false, $exportedMethods = false) 
    {
        $this->ajax->registerClass($instance,$exportedName,$exportedMethods);
    }

    /**
     * Register a new js client library
     *
     * @param string          $libraryName name you'll reference the library as
     * @param string|array    $fileName   actual filename with no path, for example customLib.js
     * @param string|false    $path   Optional, if not set the result from jsClientLocation is used
     */
    function registerJSLibrary($libraryName,$fileName,$path = false) {
        $libraryName = strtolower($libraryName);
        $this->javascriptLibraries[$libraryName] = $fileName;

        if ($path !== false) {
            $this->javascriptLibraryPaths[$libraryName] = $path;
        }
    }

    /**
     * Register init methods from an external class
     *
     * @param object    $instance an external class with initClassName methods
     */
    function registerInitObject(&$instance) {
        $instance->server = $this;
        $methods = get_class_methods($instance);
        foreach($methods as $method) {
            if (preg_match('/^init([a-zA-Z0-9_]+)$/',$method,$match)) {
                $this->_initLookup[strtolower($match[1])] = array(&$instance,$method);
            }
        }
    }

    /**
     * Generate client js
     *
     * @todo    this is going to need tests to cover all the options
     */
    function generateClient() 
    {
        $headers = array();

        ob_start();

        // create a list list of js files were going to need to output
        $fileList = array();

        if(!is_array($this->options['client'])) {
            $this->options['client'] = array();
        }
        foreach($this->options['client'] as $library) {
            if (isset($this->javascriptLibraries[$library])) {
                $lib = (array)$this->javascriptLibraries[$library];
                foreach($lib as $file) {
                    if (isset($this->javascriptLibraryPaths[$library])) {
                        $fileList[] = $this->javascriptLibraryPaths[$library].$file;
                    }
                    else {
                        $fileList[] = $this->clientJsLocation().$file;
                    }
                }
            }
        }

        // do needed class init if were running an init server
        if(!is_array($this->options['stub'])) {
            $this->options['stub'] = array();
        }
        $classList = $this->options['stub'];
        if ($this->initMethods) {
            if (isset($this->options['stub'][0]) && $this->options['stub'][0] === 'all') {
                    $this->_initAll();
            } else {
                foreach($this->options['stub'] as $stub) {
                    $this->_init($stub);
                }
            }
        }
        if (isset($this->options['stub'][0]) && $this->options['stub'][0] === 'all') {
            $classList = array_keys($this->ajax->_exportedInstances);
        }

        // if were doing stub and client we have to wait for both ETags before we can compare with the client
        $combinedOutput = false;
        if ($classList != false && count($classList) > 0 && count($fileList) > 0) {
            $combinedOutput = true;
        }


        if ($classList != false && count($classList) > 0) {

            // were setup enough to make a stubETag if the input it wants is a class list
            if ($this->cacheOptions['httpCacheStub'] && 
                $this->cacheOptions['StubCacheExpects'] == 'classes') 
            {
                $stubETag = $this->_callCacheRule('Stub',$classList);
            }

            // if were not in combined output compare etags, if method returns true were done
            if (!$combinedOutput && isset($stubETag)) {
                if ($this->_compareEtags($stubETag)) {
                    ob_end_clean();
                    return;
                }
            }

            // output the stubs for all the classes in our list
            foreach($classList as $class) {
                    echo $this->ajax->generateClassStub($class);
            }

            // if were cacheing and the rule expects content make a tag and check it, if the check is true were done
            if ($this->cacheOptions['httpCacheStub'] && 
                $this->cacheOptions['StubCacheExpects'] == 'content') 
            {
                $stubETag = $this->_callCacheRule('Stub',ob_get_contents());
            }

            // if were not in combined output compare etags, if method returns true were done
            if (!$combinedOutput && isset($stubETag)) {
                if ($this->_compareEtags($stubETag)) {
                    ob_end_clean();
                    return;
                }
            }
        }

        if (count($fileList) > 0) {
            // if were caching and need a file list build our jsETag
            if ($this->cacheOptions['httpCacheClient'] && 
                $this->cacheOptions['ClientCacheExpects'] === 'files') 
            {
                $jsETag = $this->_callCacheRule('Client',$fileList);

            }

            // if were not in combined output compare etags, if method returns true were done
            if (!$combinedOutput && isset($jsETag)) {
                if ($this->_compareEtags($jsETag)) {
                    ob_end_clean();
                    return;
                }
            }

            // output the needed client js files
            foreach($fileList as $file) {
                $this->_readFile($file);
            }

            // if were caching and need content build the etag
            if ($this->cacheOptions['httpCacheClient'] && 
                $this->cacheOptions['ClientCacheExpects'] === 'content') 
            {
                $jsETag = $this->_callCacheRule('Client',ob_get_contents());
            }

            // if were not in combined output compare etags, if method returns true were done
            if (!$combinedOutput && isset($jsETag)) {
                if ($this->_compareEtags($jsETag)) {
                    ob_end_clean();
                    return;
                }
            }
            // were in combined output, merge the 2 ETags and compare
            else if (isset($jsETag) && isset($stubETag)) {
                if ($this->_compareEtags(md5($stubETag.$jsETag))) {
                    ob_end_clean();
                    return;
                }
            }
        }


        // were outputting content, add our length header and send the output
        $length = ob_get_length();
        $output = ob_get_contents();
        ob_end_clean();
        if ($length > 0 && $this->ajax->_sendContentLength()) { 
            $headers['Content-Length'] = $length;
        }
        $headers['Content-Type'] = 'text/javascript; charset=utf-8';
        call_user_func($this->ajax->_callbacks['headers'], $headers);
        echo($output);
    }

    /**
     * Run readfile on input with basic error checking
     *
     * @param   string  $file   file to read
     * @access  private
     * @todo    is addslashes enough encoding for js?
     */
    function _readFile($file) 
    {
        if (file_exists($file)) {
            readfile($file);
        } else {
            $file = addslashes($file);
            echo "alert('Unable to find javascript file: $file');";
        }
    }

    /**
     * Get the location of the client js
     * To override the default pear datadir location set $this->clientJsLocation
     *
     * @return  string
     */
    function clientJsLocation() 
    {
        if (!$this->clientJsLocation) {
            $path = '@data-dir@'.DIRECTORY_SEPARATOR.'HTML_AJAX'.DIRECTORY_SEPARATOR.'js'.DIRECTORY_SEPARATOR;
            if(strpos($path, '@'.'data-dir@') === 0)
            {
                $path = realpath(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'js').DIRECTORY_SEPARATOR;
            }
            return $path;
        } else {
            return $this->clientJsLocation;
        }
    }

    /**
     * Load options from _GET
     *
     * @access private
     */
    function _loadOptions() 
    {
        $this->options = array('client'=>array(),'stub'=>array());
        if (isset($_GET['client'])) {
            $this->options['client'] = array();
             if (strstr($_GET['client'],',')) {
                $clients = explode(',',$_GET['client']);
            } else {
                $clients = array($_GET['client']);
            }
            
            $client = array();
            foreach($clients as $val) {
                $cleanVal = $this->_cleanIdentifier($val);
                if (!empty($cleanVal)) {
                    $client[] = strtolower($cleanVal);
                }
            }

            if (count($client) > 0) {
                $this->options['client'] = $client;
            }
        }
        if (isset($_GET['stub'])) {
            if (strstr($_GET['stub'],',')) {
                $stubs = explode(',',$_GET['stub']);
            } else {
                $stubs = array($_GET['stub']);
            }
            $stub = array();
            foreach($stubs as $val) {
                $cleanVal = $this->_cleanIdentifier($val);
                if (!empty($cleanVal)) {
                    $stub[] = strtolower($cleanVal);
                }
            }

            if (count($stub) > 0) {
                $this->options['stub'] = $stub;
            }
        }
    }

    /**
     * Clean an identifier like a class name making it safe to use
     *
     * @param   string  $input
     * @return  string
     * @access  private
     */
    function _cleanIdentifier($input) {
            return trim(preg_replace('/[^A-Za-z_0-9]/','',$input));
    }

    /**
     * Run every init method on the class
     *
     * @access private
     */
    function _initAll() 
    {
        if ($this->initMethods) {
            foreach($this->_initLookup as $class => $method) {
                $this->_init($class);
            }
        }
    }

    /**
     * Init one class
     *
     * @param   string  $className
     * @access private
     */
    function _init($className) 
    {
        if ($this->initMethods) {
            if (isset($this->_initLookup[$className])) {
                $method = $this->_initLookup[$className];
                if (is_array($method)) {
                    call_user_func($method);
                }
                else {
                    $this->$method();
                }
            } else {
                @trigger_error("Could find an init method for class: " . $className);
            }
        }
    }

    /**
     * Generate a hash from a list of files
     *
     * @param   array   $files  file list
     * @return  string  a hash that can be used as an etag
     * @access  private
     */
    function _cacheRuleFile($files) {
        $signature = "";
        foreach($files as $file) {
            if (file_exists($file)) {
                $signature .= $file.filemtime($file);
            }
        }
        return md5($signature);
    }

    /**
     * Generate a hash from the api of registered classes
     *
     * @param   array   $classes class list
     * @return  string  a hash that can be used as an etag
     * @access  private
     */
    function _cacheRuleApi($classes) {
        $signature = "";
        foreach($classes as $class) {
            if (isset($this->ajax->_exportedInstances[$class])) {
                $signature .= $class.implode(',',$this->ajax->_exportedInstances[$class]['exportedMethods']);
            }
        }
        return md5($signature);
    }

    /**
     * Generate a hash from the raw content
     *
     * @param   array   $content
     * @return  string  a hash that can be used as an etag
     * @access  private
     */
    function _cacheRuleContent($content) {
        return md5($content);
    }

    /**
     * Send cache control headers
     * @access  private
     */
    function _sendCacheHeaders($etag,$notModified) {
        header('Cache-Control: must-revalidate');
        header('ETag: '.$etag);
        if ($notModified) {
            header('HTTP/1.0 304 Not Modified',false,304);
        }
    }

    /**
     * Compare eTags
     *
     * @param   string  $serverETag server eTag
     * @return  boolean
     * @access  private
     */
    function _compareEtags($serverETag) {
        if (isset($_SERVER['HTTP_IF_NONE_MATCH'])) {
    		if (strcmp($_SERVER['HTTP_IF_NONE_MATCH'],$serverETag) == 0) {
                $this->_sendCacheHeaders($serverETag,true);
                return true;
            }
    	}
        $this->_sendCacheHeaders($serverETag,false);
        return false;
    }

    /**
     * Call a cache rule and return its retusn
     *
     * @param   string  $rule Stub|Client
     * @param   mixed   $payload
     * @return  boolean
     * @access  private
     * @todo    decide if error checking is needed
     */
    function _callCacheRule($rule,$payload) {
        $method = '_cacheRule'.$this->cacheOptions[$rule.'CacheRule'];
        return call_user_func(array(&$this,$method),$payload);
    }
}
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
?>

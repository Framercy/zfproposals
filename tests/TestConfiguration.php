<?php
/**
 * @package UnitTests
 *
 * This file defines configuration for running the unit tests for the Zend
 * Framework.  Some tests have dependencies to PHP extensions or databases
 * which may not necessary installed on the target system.  For these cases,
 * the ability to disable or configure testing is provided below.  Tests for
 * components which should run universally, such as "Zend" or
 * "Zend_InputFilter", are always run by the master suite and cannot be
 * disabled.
 */

/**
 * Zend_Cache
 *
 * TESTS_ZEND_CACHE_SQLITE_ENABLED    => sqlite extension has to be enabled
 * TESTS_ZEND_CACHE_APC_ENABLED       => apc extension has to be enabled
 * TESTS_ZEND_CACHE_MEMCACHED_ENABLED => memcache extension has to be enabled and
 *                                       a memcached server has to be available
 */
define('TESTS_ZEND_CACHE_SQLITE_ENABLED', false);
define('TESTS_ZEND_CACHE_APC_ENABLED', false);
define('TESTS_ZEND_CACHE_PLATFORM_ENABLED', false);
define('TESTS_ZEND_CACHE_MEMCACHED_ENABLED', false);
define('TESTS_ZEND_CACHE_MEMCACHED_HOST', '127.0.0.1');
define('TESTS_ZEND_CACHE_MEMCACHED_PORT', 11211);
define('TESTS_ZEND_CACHE_MEMCACHED_PERSISTENT', true);

/**
 * Zend_Db_Adapter_Pdo_Mysql and Zend_Db_Adapter_Mysqli
 * There are separate properties to enable tests for the PDO_MYSQL adapter and
 * the native Mysqli adapters, but the other properties are shared between the
 * two MySQL-related Zend_Db adapters.
 */
define('TESTS_ZEND_DB_ADAPTER_PDO_MYSQL_ENABLED',  false);
define('TESTS_ZEND_DB_ADAPTER_MYSQLI_ENABLED',  false);
define('TESTS_ZEND_DB_ADAPTER_MYSQL_HOSTNAME', '127.0.0.1');
define('TESTS_ZEND_DB_ADAPTER_MYSQL_USERNAME', null);
define('TESTS_ZEND_DB_ADAPTER_MYSQL_PASSWORD', null);
define('TESTS_ZEND_DB_ADAPTER_MYSQL_DATABASE', 'test');

/**
 * Zend_Db_Adapter_Pdo_Sqlite
 */
define('TESTS_ZEND_DB_ADAPTER_PDO_SQLITE_ENABLED',  false);
define('TESTS_ZEND_DB_ADAPTER_PDO_SQLITE_USERNAME', null);
define('TESTS_ZEND_DB_ADAPTER_PDO_SQLITE_PASSWORD', null);
define('TESTS_ZEND_DB_ADAPTER_PDO_SQLITE_DATABASE', ':memory:');

/**
 * Zend_Db_Adapter_Pdo_Mssql
 */
define('TESTS_ZEND_DB_ADAPTER_PDO_MSSQL_ENABLED',  false);
define('TESTS_ZEND_DB_ADAPTER_PDO_MSSQL_HOSTNAME', '127.0.0.1');
define('TESTS_ZEND_DB_ADAPTER_PDO_MSSQL_USERNAME', null);
define('TESTS_ZEND_DB_ADAPTER_PDO_MSSQL_PASSWORD', null);
define('TESTS_ZEND_DB_ADAPTER_PDO_MSSQL_DATABASE', 'test');

/**
 * Zend_Db_Adapter_Pdo_Pgsql
 */
define('TESTS_ZEND_DB_ADAPTER_PDO_PGSQL_ENABLED',  false);
define('TESTS_ZEND_DB_ADAPTER_PDO_PGSQL_HOSTNAME', '127.0.0.1');
define('TESTS_ZEND_DB_ADAPTER_PDO_PGSQL_USERNAME', null);
define('TESTS_ZEND_DB_ADAPTER_PDO_PGSQL_PASSWORD', null);
define('TESTS_ZEND_DB_ADAPTER_PDO_PGSQL_DATABASE', 'postgres');

/**
 * Zend_Db_Adapter_Oracle and Zend_Db_Adapter_Pdo_Oci
 * There are separate properties to enable tests for the PDO_OCI adapter and
 * the native Oracle adapter, but the other properties are shared between the
 * two Oracle-related Zend_Db adapters.
 */
define('TESTS_ZEND_DB_ADAPTER_PDO_OCI_ENABLED',  false);
define('TESTS_ZEND_DB_ADAPTER_ORACLE_ENABLED',  false);
define('TESTS_ZEND_DB_ADAPTER_ORACLE_HOSTNAME', '127.0.0.1');
define('TESTS_ZEND_DB_ADAPTER_ORACLE_USERNAME', null);
define('TESTS_ZEND_DB_ADAPTER_ORACLE_PASSWORD', null);
define('TESTS_ZEND_DB_ADAPTER_ORACLE_SID',      'xe');

/**
 * Zend_Db_Adapter_Db2
 */
define('TESTS_ZEND_DB_ADAPTER_DB2_ENABLED',  false);
define('TESTS_ZEND_DB_ADAPTER_DB2_HOSTNAME', '127.0.0.1');
define('TESTS_ZEND_DB_ADAPTER_DB2_USERNAME', null);
define('TESTS_ZEND_DB_ADAPTER_DB2_PASSWORD', null);
define('TESTS_ZEND_DB_ADAPTER_DB2_DATABASE', 'sample');

/**
 * Zend_Http_Client tests
 *
 * To enable the dynamic Zend_Http_Client tests, you will need to symbolically
 * link or copy the files in tests/Zend/Http/Client/_files to a directory
 * under your web server(s) document root and set this constant to point to the
 * URL of this directory.
 */
define('TESTS_ZEND_HTTP_CLIENT_BASEURI', false);

/**
 * HTTP proxy to be used for testing the Proxy adapter. Set to a string of
 * the form 'host:port'. Set to null to skip HTTP proxy tests.
 */
define('TESTS_ZEND_HTTP_CLIENT_HTTP_PROXY', false);
define('TESTS_ZEND_HTTP_CLIENT_HTTP_PROXY_USER', '');
define('TESTS_ZEND_HTTP_CLIENT_HTTP_PROXY_PASS', '');

/**
 * Zend_Gdata tests
 *
 * If the CLIENTLOGIN_ENABLED property below is false, the authenticated
 * tests are reported Skipped in the test run.  Set this property to true
 * to enable tests that require ClientLogin authentication.  Enter your
 * Google login credentials in the EMAIL and PASSWORD properties below.
 *
 * Edit TestConfiguration.php, not TestConfiguration.php.dist.
 * Never commit plaintext passwords to the source code repository.
 */
define('TESTS_ZEND_GDATA_CLIENTLOGIN_ENABLED', false);
define('TESTS_ZEND_GDATA_CLIENTLOGIN_EMAIL', 'example@example.com');
define('TESTS_ZEND_GDATA_CLIENTLOGIN_PASSWORD', 'xxxxxxxx');

/**
 * Zend_Date tests
 *
 * If the BCMATH_ENABLED property below is false, all arithmetic
 * operations will use ordinary PHP math operators and functions.
 * Otherwise, the bcmath functions will be used for unlimited precision.
 *
 * Edit TestConfiguration.php, not TestConfiguration.php.dist.
 */
define('TESTS_ZEND_LOCALE_BCMATH_ENABLED', true);

/**
 * PHPUnit Code Coverage / Test Report
 */
define('TESTS_GENERATE_REPORT', false);
define('TESTS_GENERATE_REPORT_TARGET', '/path/to/target');

set_include_path('/home/padraic/projects/zf/trunk/library' . PATH_SEPARATOR
               . get_include_path());

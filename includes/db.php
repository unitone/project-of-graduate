<?php

define( 'EZSQL_VERSION', 'WP1.25' );

define( 'OBJECT', 'OBJECT', true );

define( 'OBJECT_K', 'OBJECT_K' );

define( 'ARRAY_A', 'ARRAY_A' );

define( 'ARRAY_N', 'ARRAY_N' );

class db {

	var $ready = false;	

	var $last_error = '';

	var $last_query = '';

	var $num_queries = 0;

	var $rows_affected = 0;

	var $insert_id = 0;

	var $last_result;

	var $queries;

	var $field_types = array();

	var $real_escape = false;

	var $func_call;

	var $charset;

	var $collate;

	function db( $dbuser, $dbpassword, $dbname, $dbhost ) {
		return $this->__construct( $dbuser, $dbpassword, $dbname, $dbhost );
	}

	function __construct( $dbuser, $dbpassword, $dbname, $dbhost ) {
		register_shutdown_function( array( &$this, '__destruct' ) );

		$this->dbh = @mysql_connect( $dbhost, $dbuser, $dbpassword, true );
		
		if ( ! $this->dbh ) {
			$this->last_error = mysql_error();
			die( "无法连接MySQL,原因如下:<br />" . $this->last_error );
		}

		if ( defined( 'DB_CHARSET' ) )
			$this->charset = DB_CHARSET;

		if ( defined( 'DB_COLLATE' ) && DB_COLLATE )
			$this->collate = DB_COLLATE;
		else
			$this->collate = 'utf8_general_ci';

		if ( function_exists( 'mysql_set_charset' ) && ! empty( $this->charset ) ) {
				mysql_set_charset( $this->charset, $this->dbh );
				$this->real_escape = true;
			} else {
				$query = $this->prepare( 'SET NAMES %s', $this->charset );
				if ( ! empty( $this->collate ) )
					$query .= $this->prepare( ' COLLATE %s', $this->collate );
				mysql_query( $query, $this->dbh );
			}

		$this->ready = true;

		$this->select( $dbname, $this->dbh );

	}

	function __destruct() {
		return true;
	}

	function select( $db, $dbh = null ) {
		if ( is_null( $dbh ) )
			$dbh = $this->dbh;

		if ( ! @mysql_select_db( $db, $dbh ) ) {
			$this->ready = false;
			return;
		}

	}

	function _weak_escape( $string ) {
		return addslashes( $string );
	}

	function _real_escape( $string ) {
		if ( $this->dbh && $this->real_escape )
			return mysql_real_escape_string( $string, $this->dbh );
		else
			return addslashes( $string );
	}

	function _escape( $data ) {
		if ( is_array( $data ) ) {
			foreach ( (array) $data as $k => $v ) {
				if ( is_array($v) )
					$data[$k] = $this->_escape( $v );
				else
					$data[$k] = $this->_real_escape( $v );
			}
		} else {
			$data = $this->_real_escape( $data );
		}

		return $data;
	}

	function escape( $data ) {
		if ( is_array( $data ) ) {
			foreach ( (array) $data as $k => $v ) {
				if ( is_array( $v ) )
					$data[$k] = $this->escape( $v );
				else
					$data[$k] = $this->_weak_escape( $v );
			}
		} else {
			$data = $this->_weak_escape( $data );
		}

		return $data;
	}

	function escape_by_ref( &$string ) {
		$string = $this->_real_escape( $string );
	}

	function prepare( $query = null ) { // ( $query, *$args )
		if ( is_null( $query ) )
			return;

		$args = func_get_args();
		array_shift( $args );
		// If args were passed as an array (as in vsprintf), move them up
		if ( isset( $args[0] ) && is_array($args[0]) )
			$args = $args[0];
		$query = str_replace( "'%s'", '%s', $query ); // in case someone mistakenly already singlequoted it
		$query = str_replace( '"%s"', '%s', $query ); // doublequote unquoting
		$query = preg_replace( '|(?<!%)%s|', "'%s'", $query ); // quote the strings, avoiding escaped strings like %%s
		array_walk( $args, array( &$this, 'escape_by_ref' ) );
		return @vsprintf( $query, $args );
	}

	function flush() {
		$this->last_result = array();
		$this->col_info    = null;
		$this->last_query  = null;
	}

	function query($query) {
		if ( ! $this->ready )
			return false;

		// Initialise return
		// Flush cached values..
		// For reg expressions
		$return_val = 0;		
		$this->flush();		
		$query = trim($query);

		// Log how the function was called
		$this->func_call = "\$db->query(\"$query\")";

		// Keep track of the last query for debug..
		$this->last_query = $query;

		// Count how many queries there have been
		$this->num_queries++;

		// Perform the query via std mysql_query function..
		$this->result = @mysql_query( $query, $this->dbh );

		// If there is an error then take note of it..
		if ( $this->last_error = mysql_error( $this->dbh ) ) {
			die( "无法连接MySQL,原因如下:<br />" . $this->last_error );
		}

		// Query was an insert, delete, update, replace
		$is_insert = false;
		if ( preg_match( "/^(insert|delete|update|replace)\s+/i", $query ) ) {
			$this->rows_affected = @mysql_affected_rows();

			// Take note of the insert_id
			if ( preg_match( "/^(insert|replace)\s+/i", $query ) ) {
				$this->insert_id = @mysql_insert_id($this->dbh);
			}

			// Return number fo rows affected
			$return_val = $this->rows_affected;
		} else {// Query was a select
			// Take note of column info
			$i=0;
			while ( $i < @mysql_num_fields( $this->result ) ) {
				$this->col_info[$i] = @mysql_fetch_field( $this->result );
				$i++;
			}
			// Store Query Results
			$num_rows=0;
			while ( $row = @mysql_fetch_object( $this->result ) ) {
				// Store relults as an objects within main array
				$this->last_result[$num_rows] = $row;
				$num_rows++;
			}

			@mysql_free_result( $this->result );

			// Log number of rows the query returned
			// Return number of rows selected
			$this->num_rows = $num_rows;			
			$return_val = $this->num_rows;
		}

		return $return_val;
	}

	/**
	 * Insert a row into a table.
	 *
	 * <code>
	 * insert( 'table', array( 'column' => 'foo', 'field' => 'bar' ) )
	 * insert( 'table', array( 'column' => 'foo', 'field' => 1337 ), array( '%s', '%d' ) )
	 * </code>
	 *
	 */
	function insert( $table, $data, $format = null ) {
		return $this->_insert_replace_helper( $table, $data, $format, 'INSERT' );
	}

	/**
	 * Replace a row into a table.
	 *
	 * <code>
	 * replace( 'table', array( 'column' => 'foo', 'field' => 'bar' ) )
	 * replace( 'table', array( 'column' => 'foo', 'field' => 1337 ), array( '%s', '%d' ) )
	 * </code>
	 *
	 */
	function replace( $table, $data, $format = null ) {
		return $this->_insert_replace_helper( $table, $data, $format, 'REPLACE' );
	}

	function _insert_replace_helper( $table, $data, $format = null, $type = 'INSERT' ) {
		if ( ! in_array( strtoupper( $type ), array( 'REPLACE', 'INSERT' ) ) )
			return false;
		$formats = $format = (array) $format;
		$fields = array_keys( $data );
		$formatted_fields = array();
		foreach ( $fields as $field ) {
			if ( !empty( $format ) )
				$form = ( $form = array_shift( $formats ) ) ? $form : $format[0];
			elseif ( isset( $this->field_types[$field] ) )
				$form = $this->field_types[$field];
			else
				$form = '%s';
			$formatted_fields[] = $form;
		}
		$sql = "{$type} INTO `$table` (`" . implode( '`,`', $fields ) . "`) VALUES ('" . implode( "','", $formatted_fields ) . "')";
		return $this->query( $this->prepare( $sql, $data ) );
	}

	/**
	 * Update a row in the table
	 *
	 * <code>
	 * update( 'table', array( 'column' => 'foo', 'field' => 'bar' ), array( 'ID' => 1 ) )
	 * update( 'table', array( 'column' => 'foo', 'field' => 1337 ), array( 'ID' => 1 ), array( '%s', '%d' ), array( '%d' ) )
	 * </code>
	 *
	 */
	function update( $table, $data, $where, $format = null, $where_format = null ) {
		if ( ! is_array( $data ) || ! is_array( $where ) )
			return false;

		$formats = $format = (array) $format;
		$bits = $wheres = array();
		foreach ( (array) array_keys( $data ) as $field ) {
			if ( !empty( $format ) )
				$form = ( $form = array_shift( $formats ) ) ? $form : $format[0];
			elseif ( isset($this->field_types[$field]) )
				$form = $this->field_types[$field];
			else
				$form = '%s';
			$bits[] = "`$field` = {$form}";
		}

		$where_formats = $where_format = (array) $where_format;
		foreach ( (array) array_keys( $where ) as $field ) {
			if ( !empty( $where_format ) )
				$form = ( $form = array_shift( $where_formats ) ) ? $form : $where_format[0];
			elseif ( isset( $this->field_types[$field] ) )
				$form = $this->field_types[$field];
			else
				$form = '%s';
			$wheres[] = "`$field` = {$form}";
		}

		$sql = "UPDATE `$table` SET " . implode( ', ', $bits ) . ' WHERE ' . implode( ' AND ', $wheres );
		return $this->query( $this->prepare( $sql, array_merge( array_values( $data ), array_values( $where ) ) ) );
	}

	function get_var( $query, $x = 0, $y = 0 ) {
		$this->func_call = "\$db->get_var(\"$query\", $x, $y)";
		if ( $query )
			$this->query( $query );

		if ( ! empty( $this->last_result[$y] ) ) {
			$values = array_values( get_object_vars( $this->last_result[$y] ) );
		}

		return ( isset( $values[$x] ) && $values !== '' ) ? $values[$x] : null;
	}

	function get_row( $query = null, $output = OBJECT, $y = 0 ) {
		$this->func_call = "\$db->get_row(\"$query\",$output,$y)";
		if ( $query )
			$this->query( $query );
		else
			return null;

		if ( !isset( $this->last_result[$y] ) )
			return null;

		if ( $output == OBJECT ) {
			return $this->last_result[$y] ? $this->last_result[$y] : null;
		} elseif ( $output == ARRAY_A ) {
			return $this->last_result[$y] ? get_object_vars( $this->last_result[$y] ) : null;
		} elseif ( $output == ARRAY_N ) {
			return $this->last_result[$y] ? array_values( get_object_vars( $this->last_result[$y] ) ) : null;
		} else {
			echo " \$db->get_row(string query, output type, int offset) -- Output type must be one of: OBJECT, ARRAY_A, ARRAY_N";
		}
	}

	function get_col( $query = null , $x = 0 ) {
		if ( $query )
			$this->query( $query );

		$new_array = array();
		// Extract the column values
		for ( $i = 0, $j = count( $this->last_result ); $i < $j; $i++ ) {
			$new_array[$i] = $this->get_var( null, $x, $i );
		}
		return $new_array;
	}

	function get_results( $query = null, $output = OBJECT ) {
		$this->func_call = "\$db->get_results(\"$query\", $output)";

		if ( $query )
			$this->query( $query );
		else
			return null;

		$new_array = array();
		if ( $output == OBJECT ) {
			// Return an integer-keyed array of row objects
			return $this->last_result;
		} elseif ( $output == OBJECT_K ) {
			// Return an array of row objects with keys from column 1
			// (Duplicates are discarded)
			foreach ( $this->last_result as $row ) {
				$key = array_shift( get_object_vars( $row ) );
				if ( ! isset( $new_array[ $key ] ) )
					$new_array[ $key ] = $row;
			}
			return $new_array;
		} elseif ( $output == ARRAY_A || $output == ARRAY_N ) {
			// Return an integer-keyed array of...
			if ( $this->last_result ) {
				foreach( (array) $this->last_result as $row ) {
					if ( $output == ARRAY_N ) {
						// ...integer-keyed row arrays
						$new_array[] = array_values( get_object_vars( $row ) );
					} else {
						// ...column name-keyed row arrays
						$new_array[] = get_object_vars( $row );
					}
				}
			}
			return $new_array;
		}
		return null;
	}

}

if ( ! isset( $db ) ) {
	global $db;
	$db = new db( DB_USER, DB_PASSWORD, DB_NAME, DB_HOST );
}

?>
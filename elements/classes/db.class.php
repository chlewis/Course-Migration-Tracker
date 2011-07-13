<?php
/***************************************************************************
 *                               db.class.php
 *                            -------------------
 *   begin                : Monday, March 28, 2005
 *   author               : Chris Lewis
 *   email                : clewis@nmu.edu
 *   version			  : 1.0.1
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   This class handles all of the connections with MySQL and Oracle. 
 *   
 *   Function List:
 *   db_connect -- Connects to the appropriate server Oracle or MySQL
 *   db_close -- Closes the connection between the server and client (commits
 *               transactions if it is an Oracle DB
 *   db_query -- formats and processes a query
 *   db_numrows -- number of rows returned from a query
 *   db_affectedrows -- number of rows affected by a query
 *   db_numfields -- returns number of fields from a query
 *   db_fetchrow -- return row as an assoc. array
 *   db_insertid -- returns the id of last insert (only works for MySQL, sorry) 
 *   db_error -- shows any error either MySQL or Oracle
 *   format_SQL -- formats a query into proper SQL-99 format
 *
 ***************************************************************************/
 
class db {
	// Constructor
	function db_connect($dbserver="", $dbuser, $dbpassword, $database="", $type, $cstr="adcs") {
		$this->dbtype = $type;
		$this->dbcstr = $cstr;
		$this->user = $dbuser;
		$this->password = $dbpassword;
		$this->server = $dbserver;
		$this->dbname = $database;
		if($this->dbtype=="oracle") {
			if($this->dbcstr=="adcs") {
				$db = "(DESCRIPTION =(ADDRESS_LIST =(ADDRESS =(PROTOCOL = TCP)(HOST = aditrdbs.nmu.edu)(PORT = 1521)))(CONNECT_DATA =(SID = ORCL)))";
			} else if($this->dbcstr=="banprod") {
				$db = "(DESCRIPTION =(ADDRESS_LIST =(ADDRESS =(PROTOCOL = TCP)(HOST = banprod.nmu.edu)(PORT = 1521)))(CONNECT_DATA =(SID = PROD)))";
			}
			$this->db_connect_id = OCILogon($this->user, $this->password,$db);
			//$this->db_connect_id = oci_new_connect($this->user, $this->password,$db);
			if($this->db_connect_id) {
				return $this->db_connect_id;
			} else {
				return false;
			}
		} else {
			$this->db_connect_id = mysql_connect($this->server,$this->user,$this->password);
			//echo $this->server."<br>".$this->user."<br>".$this->password."<br>";
			if($this->db_connect_id) {
				if($database!=="") {
					$dbselect = mysql_select_db($this->dbname);
					if(!$dbselect) {
						mysql_close($this->db_connect_id);
						$this->db_connect_id = $dbselect;
					}
				}
				return $this->db_connect_id;
			} else {
				return false;
			}
		}
	}
	
	// Base methods
	function db_close() {
		if($this->db_connect_id) {
			if($this->dbtype=="oracle") { 
				// Commit outstanding transactions (Oracle only)
				if($this->in_transaction) { OCICommit($this->db_connect_id); }
				if($this->query_result) { @OCIFreeStatement($this->query_result); }
				$result = @OCILogoff($this->db_connect_id);
				return $result;
			} else {
				return mysql_close($this->db_connect_id);
			}
		} else {
			return false;
		}
	}
	
	function db_query($query="") {
		unset($this->query_result);
		if($this->dbtype=="oracle") {		
			$this->in_transaction = true;
			if($query!=="") {
				require_once('SQLres.php');
				$query = $this->format_SQL($query,$SQL99);
				$this->last_query = $query;
				$this->num_queries++;
				$this->query_result = @OCIParse($this->db_connect_id, $query);
				$success = @OCIExecute($this->query_result, OCI_DEFAULT);
			}
			if($success) {
				OCICommit($this->db_connect_id);
				$this->in_transaction = false;
				unset($this->row[$this->query_result]);
				unset($this->rowset[$this->query_result]);
				$this->last_query_text[$this->query_result] = $query;
				return $this->query_result;
			} else {
				if($this->in_transaction) {
					OCIRollback($this->db_connect_id);
					$this->in_transaction = false;
				}
				return false;
			}
		} else {
			if($query!=="") {
				$this->num_queries++;
				$this->in_transaction = true;
				require_once('SQLres.php');
				//$query = $this->format_SQL($query,$SQL99);
				$this->query_result = mysql_query($query, $this->db_connect_id);
			}
			if($this->query_result) {
				unset($this->row[$this->query_result]);
				unset($this->rowset[$this->query_result]);				
				return $this->query_result;
			} else {
				if($this->in_transaction) {
					$this->in_transaction = false;
				}
				return false;
			}
		}
	}
	
	function db_numrows($query_id=0) {
		if(!$query_id) { $query_id = $this->query_result; }
		if($this->dbtype=="oracle") { 
			if($query_id) {
				$result = @OCIFetchStatement($query_id, $this->rowset);
				@OCIExecute($query_id,OCI_DEFAULT);
				return $result;
				//return ocirowcount($query_id);
			} else { return false; }
		} else {
			if($query_id) { 
				return mysql_num_rows($query_id);
			} else { 
				return false; 
			}
		}
	}
	
	function db_affectedrows($query_id=0) {
		if($this->dbtype=="oracle") { 
			if(!$query_id) { $query_id = $this->query_result; }
			if($query_id) {
				$result = @OCIRowCount($query_id);
				return $result;
			} else { 
				return false; 
			}
		} else {
			if($this->db_connect_id) { 
				return mysql_affected_rows($this->db_connect_id);
			} else { 
				return false; 
			}
		}
	}
	
	function db_numfields($query_id=0) {
		if(!$query_id) { $query_id = $this->query_result; }
		if($query_id) {
			if($this->dbtype=="oracle") { 
				return @OCINumCols($query_id);
			} else {
				return mysql_num_fields($query_id);
			}
		} else {
			return false;
		}
	}
	
	function db_colname($query_id=0,$col_num=0) {
		if(!$query_id) { $query_id = $this->query_result; }
		if($query_id) {
			if($this->dbtype=="oracle") { 
				return @OCIColumnName($query_id,$col_num);
			} else {
				return mysql_field_name($query_id,$col_num);
			}
		} else {
			return false;
		}
	}
	
	function db_fetchrow($query_id=0) {
		if(!$query_id) { $query_id = $this->query_result; }
		if($query_id) {
			if($this->dbtype=="oracle") { 
				$result_row = "";
				$result = OCIFetchInto($query_id,$result_row,OCI_ASSOC+OCI_RETURN_NULLS);
				//$result = OCIFetchInto($query_id,$result_row,OCI_ASSOC);
				if($result_row=="") { return false; }
				for($i=0;$i<count($result_row);$i++) {
					list($key,$val) = each($result_row);
					$return_arr[strtolower($key)] = $val;
				}
				$this->row[$query_id] = $return_arr;
				return $this->row[$query_id];
			} else {
				$this->row[$query_id] = mysql_fetch_array($query_id, MYSQL_ASSOC);
				return $this->row[$query_id];
			}
		} else {
			return false;
		}
	}
	
	function db_insertid() { //Only works for MySQL I can't figure out a good way for Oracle.  Sorry.
		if($this->dbtype=="mysql") {
			if($this->db_connect_id) {
				return mysql_insert_id($this->db_connect_id);
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	function db_error() {
		if($this->dbtype=="oracle") { 
			if(!$query_id) { $query_id = $this->query_result; }
			$result  = @OCIError($query_id);
			return $result;
		} else {
			$result['message'] = mysql_error($this->db_connect_id);
			$result['code'] = mysql_errno($this->db_connect_id);
			return $result;
		}
	}
	
	function format_SQL($sql,$resarr) {
		$rtn = "";
		$sqlarr = array();
		$sqlarr = split(" ",$sql);
		for($i=0;$i<count($sqlarr);$i++) {
			for($j=0;$j<count($resarr);$j++) {
				if(strcasecmp($sqlarr[$i],$resarr[$j])==0) {
					$sqlarr[$i] = $resarr[$j];
					$j=count($resarr);
				}		
			}
		}
		for($i=0;$i<count($sqlarr);$i++) { $rtn = $rtn.$sqlarr[$i]." "; }
		return $rtn;
	}
};
?>
<?php
/**
 * Created by IntelliJ IDEA.
 * User: almustafa dekaulitz (sulaimanfahmi@gmail.com)
 * Date: 18/12/2018
 * Time: 11:11
 */

namespace Src;


class OracleConnector
{
    private $host;
    private $username;
    private $password;
    private $conn, $query;
    private $statement, $binding = array();

    public function __construct()
    {
        $this->host = $GLOBALS["host"];
        $this->username = $GLOBALS["username"];
        $this->password = $GLOBALS["password"] ?: null;
        if ($this->host == null || $this->password == null || $this->username == null)
            throw new \Exception("host or username or password is e");
        $this->conn = oci_connect($this->username, $this->password, $this->host);

    }

    public function getConnection()
    {
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStatement()
    {
        return $this->statement;
    }

    public function select($query, $binding = array())
    {
        $this->statement = oci_parse($this->conn, $query);
        $this->binding = $binding;
        return $this;
    }

    public function insert($query, $binding = array())
    {
        $this->statement = oci_parse($this->conn, $query);
        $this->binding = $binding;
        return $this;
    }

    public function execute()
    {
        if (!empty($this->binding)) {
            foreach ($this->binding as $key => $value) {
                oci_bind_by_name($this->statement, $key, $value);
            }
        }
        oci_execute($this->statement);
    }

    public function fetchAll()
    {
        return oci_fetch_object($this->statement);
    }

    public function closeConnection()
    {
        oci_free_statement($this->statement);
        oci_close($this->conn);
    }
}
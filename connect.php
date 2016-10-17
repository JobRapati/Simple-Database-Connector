<?php
/**
 * Created by PhpStorm.
 * User: Job
 * Date: 10/3/2016
 * Time: 11:19 AM
 */
//error_reporting(E_ALL);
//ini_set("display_errors", 1);

class dbconnect
{
    public $qresult = array();
    private $con;
    private $result;

    public function connect($host, $username, $password, $database)
    {
        define('DB_HOST', $host);
        define('DB_USERNAME', $username);
        define('password', $password);
        define('db', $database);
        $this->con = mysqli_connect(DB_HOST, DB_USERNAME, password, db);
    }

    public function select(array $columns, $table)
    {
        if ($this->con == null) {
            throw new ErrorException("No connection is set. Please use the connect function first!");
            return;
        }
        $query = "";
        for ($i = 0; $i < count($columns); $i++) {
            if (strlen($query) == 0)
                $query = $columns[$i];
            else
                $query = $query . ", " . $columns[$i];
        }
        $sql = "SELECT $query FROM $table";
        $result = mysqli_query($this->con, $sql);
        $row = mysqli_fetch_assoc($result);
//        echo $row["plaatserid"];

        $variables = array();
        for ($i = 0; $i < count($row); $i++) {
            array_push($variables, $row[$columns[$i]]);
        }

        for ($i = 0; $i < count($variables); $i++) {
            $this->qresult[$i] = $variables[$i];
        }
    }
    public function selectwhere(array $columns, $table, $columntocheck, $value)
    {
        if($this->con == null)
        {
            throw new ErrorException("No connection is set. Please use the connect function first!");
            return;
        }

        $query = "";
        for($i = 0; $i < count($columns); $i++)
        {
            if(strlen($query) == 0)
                $query = $columns[$i];
            else
                $query = $query . ", " . $columns[$i];
        }
        $sql = "SELECT $query FROM $table WHERE $columntocheck=$value";
        $result = mysqli_query($this->con, $sql);
        $row = mysqli_fetch_assoc($result);

        $variables = array();
        for($i = 0; $i < count($row); $i++)
            array_push($variables, $row[$columns[$i]]);

        for($i = 0; $i < count($variables); $i++)
            $this->qresult[$i] = $variables[$i];
    }

    public function selectorderby(array $columns, $table, $columnto_order, $ASCorDESC = "ASC")
    {
        if($this->con == null)
        {
            throw new ErrorException("No connection is set. Please use the connect function first!");
        }

        $query = "";
        for($i = 0; $i < count($columns); $i++)
        {
            if(strlen($query) == 0)
                $query = $columns[$i];
            else
                $query = $query . ", " . $columns[$i];
        }

        $sql = "";
        if($ASCorDESC === "ASC")
            $sql = "SELECT $query FROM $table ORDER BY $columnto_order ASC";
        else if($ASCorDESC === "DESC")
            $sql = "SELECT $query FROM $table ORDER BY $columnto_order DESC";

        $result = mysqli_query($this->con, $sql);
        $row = mysqli_fetch_assoc($result);

        $variables = array();

        for($i = 0; $i < count($row); $i++)
            array_push($variables, $row[$columns[$i]]);

        for($i = 0; $i < count($variables); $i++)
            $this->qresult[$i] = $variables[$i];
    }

    public function selectwhereorder($columns, $table, $columntocheck, $value, $columnto_order, $ASCorDESC)
    {
        if($this->con == null)
        {
            throw new ErrorException("No connection is set. Please use the connect function first!");
        }

        $query = "";
        for($i = 0; $i < count($columns); $i++)
        {
            if(strlen($query) == 0)
                $query = $columns[$i];
            else
                $query = $query . ", " . $columns[$i];
        }
        $sql = "";
        if($ASCorDESC === "ASC")
            $sql = "SELECT $query FROM $table WHERE $columntocheck=$value ORDER BY $columnto_order ASC";
        else
            $sql = "SELECT $query FROM $table WHERE $columntocheck=$value ORDER BY $columnto_order DESC";

        $result = mysqli_query($this->con, $sql);
        $row = mysqli_fetch_assoc($result);

        $variables = array();

        for($i = 0; $i < count($row); $i++)
            array_push($variables, $row[$columns[$i]]);

        for($i = 0; $i < count($variables); $i++)
            $this->qresult[$i] = $variables[$i];
    }
}

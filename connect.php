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
    private $sql;
    private $err = "No connection is set. Please use the connect function first!";

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
            throw new ErrorException($this->err);
            return;
        }
        $query = "";
        for ($i = 0; $i < count($columns); $i++) {
            if (strlen($query) == 0)
                $query = $columns[$i];
            else
                $query = $query . ", " . $columns[$i];
        }
        $this->sql = "SELECT $query FROM $table";
        $result = mysqli_query($this->con, $this->sql);
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
            throw new ErrorException($this->err);
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
        $this->sql = "SELECT $query FROM $table WHERE $columntocheck=$value";
        $result = mysqli_query($this->con, $this->sql);
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
            throw new ErrorException($this->err);
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

        if($ASCorDESC === "ASC")
            $this->sql = "SELECT $query FROM $table ORDER BY $columnto_order ASC";
        else if($ASCorDESC === "DESC")
            $this->sql = "SELECT $query FROM $table ORDER BY $columnto_order DESC";

        $result = mysqli_query($this->con, $this->sql);
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
            throw new ErrorException($this->err);
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
        if($ASCorDESC === "ASC")
            $this->sql = "SELECT $query FROM $table WHERE $columntocheck=$value ORDER BY $columnto_order ASC";
        else
            $this->sql = "SELECT $query FROM $table WHERE $columntocheck=$value ORDER BY $columnto_order DESC";

        $result = mysqli_query($this->con, $this->sql);
        $row = mysqli_fetch_assoc($result);

        $variables = array();

        for($i = 0; $i < count($row); $i++)
            array_push($variables, $row[$columns[$i]]);

        for($i = 0; $i < count($variables); $i++)
            $this->qresult[$i] = $variables[$i];
    }

    public function update($columns, $values, $table, $columntocheck, $value)
    {
        if($this->con == null)
        {
            throw new ErrorException($this->err);
        }

        if(count($columns) < count($values))
        {
            throw new ErrorException("To many values are given. Please make sure you have an equal amount of columns and values passing!");
            return;
        }
        else if(count($columns) > count($values))
        {
            throw new ErrorException("To many columns are given. Please make sure you have an equal amount of columns and values passing!");
            return;
        }

        $query = "";
        for($i = 0; $i < count($columns); $i++)
        {
            if(strlen($query) == 0)
            {
                $query = $columns[$i] . "=" . "'" . $values[$i] . "'";
            }
            else
            {
                $query = $query . ", " . $columns[$i] . "=" . "'" . $values[$i] . "'";
            }

            $this->sql = "UPDATE $table SET $query WHERE $columntocheck=$value";
            if(mysqli_query($this->con, $this->sql) != TRUE)
                throw new ErrorException("Something went wrong, please review this error: " . mysqli_error($this->con));
        }
    }

    public function insert($columns, $values, $table)
    {
        if($this->con == null)
        {
            throw new ErrorException($this->err);
            return;
        }

        if(count($columns) < count($values))
        {
            throw new ErrorException("To many values are given. Please make sure you have an equal amount of columns and values passing!");
            return;
        }
        else if(count($columns) > count($values))
        {
            throw new ErrorException("To many columns are given. Please make sure you have an equal amount of columns and values passing!");
            return;
        }

        $columnquery = "";
        for($i = 0; $i < count($columns); $i++)
        {
            if(strlen($columnquery) == 0)
                $columnquery = $columns[$i];
            else
                $columnquery = $columnquery . ", " . $columns[$i];
        }

        $valuequery = "";
        for($i = 0; $i < count($values); $i++)
        {
            if (strlen($valuequery) == 0)
                $valuequery = "'" . $values[$i] . "'";
            else
                $valuequery = $valuequery . ", " . "'" . $values[$i] . "'";

            $this->sql = "INSET INTO $table ($columnquery) VALUES ($valuequery)";

            if(mysqli_query($this->con, $this->sql != TRUE))
                throw new ErrorException("Something went wrong, please review this error: " . mysqli_error($this->con));
        }
    }

    public function delete($columntocheck, $value, $table)
    {
        if($this->con == null)
        {
            throw new ErrorException($this->err);
            return;
        }

        $this->sql = "DELETE FROM $table WHERE $columntocheck=$value";
        if(mysqli_query($this->con, $this->sql) != TRUE)
            throw new ErrorException("Something went wrong, please review this error: " . mysqli_error($this->con));
    }

    public function disconnect()
    {
        if($this->con == null)
        {
            throw new ErrorException($this->err);
            return;
        }

        mysqli_close($this->con);
    }
}
<?php

class database{
    private $host = "localhost";
    private $user = "root";
    private $pass = "";
    private $dbname = "scholarship_management_sys_db";
    private $conn;


    public function connectToDatabase(){
        $this->conn = null;

        try{
            $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->dbname);
            // check db connection
            if($this->conn->connect_error){
                
                echo json_encode([
                    "status"=>"error",
                    "message"=>"Connection Failed: ". $this->conn->error
                ]);

                throw new Exception("Connection Failed: ". $this->conn->connect_error);
            }
            // echo("Connection is VEYA");

            // set the charset
            $this->conn->set_charset("utf8mb4");
        }
        catch(Exception $e){

            echo json_encode([
                    "status"=>"error",
                    "message"=>"Database connection Error: ". $this->conn->error
                ]);

            die("Database connection Error: ".$e->getMessage());
        }

        return $this->conn;
    }

    // select///////////////////////////////////////////////////////////////
    public function select($query, $params = [], $types = "") {

        $stmt = $this->conn->prepare($query);

        if($stmt === false){
            echo json_encode([
                "status"=>"error",
                "message"=>"Select Failed". $this->conn->error
            ]);
            throw new Exception("Select Failed: ".$this->conn->error);
        }
        
        if($params){
            if($types === ""){

                // detect types
                $types = "";

                foreach($params as $param){
                    if(is_int($param)) $types .= "i";
                    elseif(is_double($param)) $types .= "d";
                    else $types .= "s";
                }
            }
            $stmt->bind_param($types, ...$params);
            
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $data;
    }
    

    // multitask exec fun////////////////////////////////////////////////////
    public function execute($query, $params = [], $types = ""){
        try{
            // prepare statement
            $stmt = $this->conn->prepare($query);

            if($stmt === false){
                echo json_encode([
                    "status"=>"error",
                    "message"=>"Prepare failed: ".$this->conn->error
                ]);
            }

            // check datatypes for bind_param
            if($params){
                 if($types === ""){
                    $types = "";

                    foreach($params as $param){
                        if(is_int($param)) $types .= "i";
                        elseif(is_double($param)) $types .= "d";
                        else $types .= "s";
                    }
                }
            // bind params
                $stmt->bind_param($types,...$params);
                }
        
            $stmt->execute();
            $rowsAffected = $stmt->affected_rows;
            $stmt->close();

            return $rowsAffected;
        }
        catch(Exception $e){
            echo json_encode([
                "status"=>"error",
                "message"=>"Exception error:". $e
            ]);
        }
    }

    public function close(){
        if($this->conn){
            $this->conn->close();
        }
    }
}

?>
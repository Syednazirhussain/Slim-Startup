<?php



class pdocrudhandler extends dbconnect{
    protected   $_pdo;
    private $_responseArr = array();
    public function __construct(){
        // connecting db
        $this->_pdo = $this->connect();
    }

    public function insert($table,$fieldArr = array()){
        try {
            $qry = '';
            $qry .= "insert into ".$table." (";
            $keys = array_keys($fieldArr);
            $values = array();
            for($i=0;$i<count($keys);$i++){
                ($i != count($keys)-1) ? $qry .= $keys[$i]." ," : $qry .= $keys[$i];
                $values[] = $fieldArr[$keys[$i]];
            }
            $qry .= ") values (";
            for($i=0;$i<count($keys);$i++){
                ($i != count($keys)-1) ? $qry .= "?, " : $qry .= "?";
            }
            $qry .= ")";
            $stmt = $this->_pdo->prepare($qry);
            $stmt->execute($values);
            if($stmt){
                $this->_responseArr['status'] = 'success';
                $this->_responseArr['rowsAffected'] = $stmt->rowCount();
                $this->_responseArr['lastInsertedId'] = $this->_pdo->lastInsertId();
            }
        }catch(PDOException $e){
            echo "Connection failed: " . $e->getMessage();
            $this->_responseArr['status'] = 'failure'.$e->getMessage();
        }
        return $this->_responseArr;
    }
    public function delete($table,$whereClause,$whereClasueValArr = array()){
        try{
            $qry = "delete from ".$table." ";
            $qry .= $whereClause;
            $stmt = $this->_pdo->prepare($qry);
            $stmt->execute($whereClasueValArr);
            if($stmt){
                $this->_responseArr['status'] = 'success';
                $this->_responseArr['rowsAffected'] = $stmt->rowCount();
            }
        }catch (PDOException $e){
            echo "Connection failed: " . $e->getMessage();
            $this->_responseArr['status'] = 'failure'.$e->getMessage();
        }
        return $this->_responseArr;
    }
    public function update($table,$fieldArr,$whereClause = '',$whereClasueValArr = array()){
        $qry = "update ".$table." set ";
        $keys = array_keys($fieldArr);
        $values = array();
        for($i=0;$i<count($keys);$i++){
            ($i != count($keys)-1) ? $qry .= $keys[$i]." = ?," : $qry .= $keys[$i]." = ?";
            $values[] = $fieldArr[$keys[$i]];
        }
        for($i=0;$i<count($whereClasueValArr);$i++){
            $values[] = $whereClasueValArr[$i];
        }
        $qry .= " ".$whereClause;
        try{
            $stmt = $this->_pdo->prepare($qry);
            $stmt->execute($values);
            if($stmt){
                $this->_responseArr['status'] = 'success';
                $this->_responseArr['rowsAffected'] = $stmt->rowCount();
            }
        }catch (PDOException $e){
            echo "Connection failed: " . $e->getMessage();
            $this->_responseArr['status'] = 'failure'.$e->getMessage();
        }
        return $this->_responseArr;
    }
    public function select($table,$colArr = array() ,$whereClause = '',$whereClauseArr = array()){
        try{
            $qry = '';
            if(empty($colArr) && $whereClause == '' && empty($whereClauseArr)){
                $qry = "select * from ".$table;
            }else if(!empty($colArr) && $whereClause == ''){
                $qry = "select ";
                for($i=0;$i<count($colArr);$i++){
                    ($i != count($colArr)-1) ? $qry .= $colArr[$i].", " : $qry .= $colArr[$i];
                }
                $qry .= " from ".$table;
            }else if($whereClause != '' && empty($colArr)){
                $qry = "select * from ".$table." ".$whereClause;
            }else if(!empty($colArr) && $whereClause != ''){
                $qry = "select ";
                for($i=0;$i<count($colArr);$i++){
                    ($i != count($colArr)-1) ? $qry .= $colArr[$i].", " : $qry .= $colArr[$i];
                }
                $qry .= " from ".$table." ".$whereClause;
            }
            $stmt = $this->_pdo->prepare($qry);
            $stmt->setFetchMode(PDO::FETCH_OBJ);
            ($whereClause == '') ? $stmt->execute() : $stmt->execute($whereClauseArr);
            $tempRes = $stmt->fetchAll();
            $result = $tempRes;
            $this->_responseArr['status'] = 'success';
            $this->_responseArr['rowsAffected'] = $stmt->rowCount();
            $this->_responseArr['result'] = $result;
        }catch (PDOException $e){
            echo "Connection failed: " . $e->getMessage();
            $this->_responseArr['status'] = 'failure'.$e->getMessage();
        }
        return $this->_responseArr;
    }
    public function customSelect($qry,$values = array()){
        try{
            $stmt = $this->_pdo->prepare($qry);
            $stmt->setFetchMode(PDO::FETCH_OBJ);
            $stmt->execute($values);
            $tempRes = $stmt->fetchAll();
            if(count($tempRes) != 0){
                $result =  $tempRes;
            }else{
                $result = "null";
            }
            $this->_responseArr['status'] = 'success';
            $this->_responseArr['rowsAffected'] = $stmt->rowCount();
            $this->_responseArr['result'] = $result;
        }catch (PDOException $e){
            echo "Connection failed: " . $e->getMessage();
            $this->_responseArr['status'] = 'failure'.$e->getMessage();
        }
        return $this->_responseArr;
    }
    public function truncate($table){
        try{
            $stmt = "truncate ".$table;
            $this->_pdo->exec($stmt);
            $this->_responseArr['status'] = 'success';
        }catch (PDOException $e){
            echo "Connection failed: " . $e->getMessage();
            $this->_responseArr['status'] = 'failure'.$e->getMessage();
        }
        return $this->_responseArr;
    }
    public  function executeqry($stmt){
        try{
            $this->_pdo->exec($stmt);
            $this->_responseArr['status'] = 'success';
        }catch (PDOException $e){
            echo "Connection failed: " . $e->getMessage();
            $this->_responseArr['status'] = 'failure'.$e->getMessage();
        }
        return $this->_responseArr;
    }
}
?>
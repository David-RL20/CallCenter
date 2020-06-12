<?php
    require_once('mysqlconection.php');
    require_once(__DIR__.'/../config/config.php');
    

    //attributes
class User{
    private $id;
    private $name;
    private $photo;
    private $password;

    //gettes and setters

    public function getId(){return $this->id;}
    public function setId($id){$this->id = $id;}

    public function getName(){return $this->name;}
    public function setName($name){$this->name = $name;}

    public function getPhoto(){return $this->photo;}
    public function setPhoto($photo){$this->photo = $photo;}

    
    public function setPassword($password){$this->password = $password;}

    //constructor

    public function __construct(){
        //get arguments
        $arguments=func_get_args();
        //empty eject
        if (func_num_args()==0) {
            $this->id='';
            $this->name='';
            $this->photo='';
            $this->password='';

        }

        if (func_num_args()==3) {
            $this->id=$arguments[0];
            $this->name=$arguments[1];
            $this->photo=$arguments[2];
            

        }



    }

    //represent the object as string
    public function toJson(){
        return json_encode(array(
            'id'=>$this->id,
            'name'=>$this->name,
            'photo'=> Config::getFileUrl('userPhotos').$this->photo

        ));
    }

    //get all
    public static function getAll(){
        $list=array();//array
        $query='select id,name,photo from users order by name';//query
        $connection= MySqlConnection::getConnection();
        $command=$connection->prepare($query);
        $command->bind_result($id,$name,$photo);
        $command->execute();

        //read result
        while ($command->fetch()) {
            array_push($list,new User($id,$name,$photo));
            
        }
        mysqli_stmt_close($command);
        $connection->Close();

        return $list;//return array
    }

    public static function getAllToJson() {
        $jsonArray = array(); //create JSON array
        //reaqd items
        foreach(self::getAll() as $item) {
            array_push($jsonArray, json_decode($item->toJson()));
        }
        return json_encode($jsonArray); // return JSON array
    }
}

?>
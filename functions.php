<?php

class Handler {

  # this function is able to accept 3 parameters, converting this into an array and returning it as an json array
  private function jsonify($message, $code, $time = null) {
    if(isset($time) && isset($code) && isset($message)) { 
      return json_encode(array('message' => $message, 'status_code' => $code, 'Time' => $time)); 
    }
    if(!isset($time)) {
      return json_encode(array('message' => $message, 'status_code' => $code));
    }    
  }

  # This method handles the ?ping parameter and is used to send a ping to the webserver who stores the ip address and the time
  public function handlePing($ip, $time) {
    $file = "storage/".$ip;                         # $file contains the storage/<ip_address>
    if(!file_exists($file)) {                       # checking if the storage/<ip_address> is not stored already
      $content = "".$ip.";".$time."";   # preparing the content of the storage/<ip_address>
      file_put_contents($file, $content);           # Inserting $content into $file
      echo $this->jsonify('IP Address successfully stored', 200);
      http_response_code(200);
    } else {
      echo $this->jsonify('IP Address already stored', 406);
      http_response_code(406);
    }
  }
  # This method handles the ?get=<specific_ip> parameter and is used to get a specific ip addresses from the storage
  public function handleGet($ip) {
    $file = "storage/".$ip;                             
    if(file_exists($file) && is_writable($file)) {                   # checking if the query_parameter_value.txt is stored
      $result = explode(';', file_get_contents($file)); # splitting the content of query_parameter_value.txt into ip and time
      echo $this->jsonify($result[0], 200, $result[1]);
      http_response_code(200);
    } else {
      echo $this->jsonify('IP Address not stored', 404);
      http_response_code(404);
    }
  }
  # This method handles the ?flush parameter and is used to remove all ip addresses from the storage
  public function handleFlush() {
    $files = scandir('storage/');        # $files contains every file in storage/ dir
    if(count($files) >= 3) {             # checking if at least 1 file is inside the dir (1=. 2=.. 3=first_dir)
      foreach($files as $file) {
        unlink("storage/".$file);        # removing every file from storage/
      }
      echo $this->jsonify("successfully flushed", 200);
      http_response_code(200);
    } else {
      echo $this->jsonify("Nothing found to flush.", 406);
      http_response_code(406);
    }
  }
  public function logRequest($ip, $time, $param) {
    $log_file = "logs/requests.log";
    if(file_exists($log_file) && is_writable($log_file)) {
      $content = "".$time." | ".$ip." accessed to ".$param."\n";
      file_put_contents($log_file, $content, FILE_APPEND);
    } else {
      echo $this->jsonify("Internal Server Error.", 500);
      http_response_code(500);
    }
   
  }
}
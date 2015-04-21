<?php
 
header("Content-Type: text/html");

$user = 'prasanna'; //--> API username
$pass = 'socket11'; //--> API password
 
$lang = 1; //--> Source Language Code; Here is 1 => C++
 
$code = $_GET['code']; //--> Source Code

 /*$code = '#include<stdio.h>
 int main(){printf("hello");}';  //--> Source Code*/
 
$input = '';
$run = true;
$private = false;
 
//create new SoapClient
$client = new SoapClient("http://ideone.com/api/1/service.wsdl" );
 
//create new submission
$result = $client->createSubmission( $user, $pass, $code, $lang, $input, $run, $private );
 
//if submission is OK, get the status
if ( $result['error'] == 'OK' ) {
 
    $status = $client->getSubmissionStatus( $user, $pass, $result['link'] );
 
    if ( $status['error'] == 'OK' ) {
 
        //check if the status is 0, otherwise getSubmissionStatus again
        while ( $status['status'] != 0 ) {
            sleep( 3 ); //sleep 3 seconds
            $status = $client->getSubmissionStatus( $user, $pass, $result['link'] );
        }
 
        //finally get the submission results
        $details = $client->getSubmissionDetails( $user, $pass, $result['link'], true, true, true, true, true );
       
        if ( $details['error'] == 'OK' ) {
            echo '<pre>'.$details['output'].'</pre>';           
        } else {
            //we got some error
            $res2 = var_dump( $details );
            echo $res2;
        }
       
        ///echo $details;
    } else {
        //we got some error
        var_dump( $status );
    }
} else {
    //we got some error
    var_dump( $result );
}

<?php

/*Name: Purnima Pathak
Identikey: pupa3066
Purpose: Authentication
Date: 3/06/18 */

include_once 'footer.php';
include_once 'hw7-lib.php';

$header = file_get_contents('header.php');
$footer = file_get_contents('footer.php');



if($message){
    echo $header.'<center>
    <form method=post action=add.php>
        <table><tr> <td> Username: </td> <td> <input type=text name=postUser>  </td> </tr>
            <tr> <td> Password: </td> <td> <input type=password name=postPass>  </td> </tr>
            <tr> <td colspan=2> <input type=submit name=submit value=Login> </td> </tr>
            <tr>'.$message.'</tr>
        </table>
    </form>'.$footer;
}else{
    echo $header.'<center>
    <form method=post action=add.php>
        <table><tr> <td> Username: </td> <td> <input type=text name=postUser>  </td> </tr>
            <tr> <td> Password: </td> <td> <input type=password name=postPass>  </td> </tr>
            <tr> <td colspan=2> <input type=submit name=submit value=Login> </td> </tr>
        </table>
    </form>'.$footer;
}


?>





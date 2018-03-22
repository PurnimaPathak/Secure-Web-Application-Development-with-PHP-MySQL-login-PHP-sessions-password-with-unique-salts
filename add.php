<?php
session_start();
/*Name: Purnima Pathak
Identikey: pupa3066
Purpose: Authentication
Date: 3/06/18 */

include_once 'footer.php';
include_once 'hw7-lib.php';


$header = file_get_contents('header.php');
$footer = file_get_contents('footer.php');
$db=connect();

if(($_SESSION['userid']==1)){
    if ($s) {
        icheck($s);
        switch ($s) {
            case 90:
            default:
                echo $header."<form method=post action=add.php> 
		<table> <tr> <td colspan=2> Add Users to Tolkien App </td> </tr> 
		<tr> <td> Username: </td> <td> <input type=text name=newUser> </td></tr>
		<tr> <td> Password: </td> <td> <input type=password name=newPass> </td></tr>
		<tr> <td> Email: </td> <td> <input type=text name=newEmail> </td></tr>
		<tr> <td colspan=2> <input type=hidden name=s value=91> <input type=submit name=submit value=submit> </td></tr>
		</table>
		</form> <br> <br> <a href=add.php?s=99> Logout </a> <br> <a href=add.php?s=90> Add New Users </a> <br><a href=add.php?s=92> Failed Login Table </a> <br><a href=add.php?s=93>Display Users</a><br>".$footer;
                break;
            case 91:
            if($newUser and $newPass and $newEmail) {
                addUser($db, $newUser, $newPass, $newEmail);
                echo $header . "Added  new User " . $newUser . "<br> <br> <a href=add.php?s=99> Logout </a> <br> <a href=add.php?s=90> Add New Users </a> <br><a href=add.php?s=92> Failed Login Table </a> <br><a href=add.php?s=93>Display Users</a><br>".$footer;
            }else{
                echo $header."<table><td>Enter all the inputs- Username, Password and Email</td></table>".$footer;
            }
                break;
            case 92:
                echo $header."Login Log<br>
		<table> </table><br> <br> <a href=add.php?s=99> Logout </a> <br> <a href=add.php?s=90> Add New Users </a> <br><a href=add.php?s=92> Failed Login Table </a> <br><a href=add.php?s=93>Display Users</a><br>".$footer;
                break;
            case 93:
                if ($stmt = mysqli_prepare($db, "Select username from users where userid <> 1")) {
                    mysqli_stmt_execute($stmt);
                    $last_insert_id_user = mysqli_insert_id($db);
                    mysqli_stmt_bind_result($stmt, $postUser);
                    echo $header . " Users<br><table>";
                    while (mysqli_stmt_fetch($stmt)) {
                        $postUser = htmlspecialchars($postUser);
                        echo "<td><tr><a href=add.php?s=94&postUser=$postUser>$postUser</a></tr></td><br>";
                    }
                }mysqli_stmt_close($stmt);
                echo "</table><br> <br> <a href=add.php?s=99> Logout </a> <br> <a href=add.php?s=90> Add New Users </a> <br><a href=add.php?s=92> Failed Login Table </a> <br><a href=add.php?s=93>Display Users</a><br>".$footer;
                break;
            case 94:
                echo $header.'<form><table><tr> <td>Update Password for user '.$postUser.'</td> </tr><br>
                                    <tr> <td> Username: </td> <td> <input type=text name=postUser> </td></tr><br>
                                    <tr> <td> Password: </td> <td> <input type=password name=newPass> </td></tr><br>
                                    <tr> <td colspan=2> <input type=submit name=submit value=submit> </td></tr>
                               </table><br> <br> <a href=add.php?s=99> Logout </a> <br> <a href=add.php?s=90> Add New Users </a> <br><a href=add.php?s=92> Failed Login Table </a> <br><a href=add.php?s=93>Display Users</a><br></form>'.$footer;

                break;
            case 99:
                session_destroy();
                header("Location: login.php");
                break;
        }
    }else if($newPass and $postUser){
        $newPass=$_GET['newPass'];
        updatePassword($db, $newPass,$postUser);
        echo $header.'<form><table><tr> <td>Updated Password for user '.$postUser.'</td> </tr><br>
                            </table><br> <br> <a href=add.php?s=99> Logout </a> <br> <a href=add.php?s=90> Add New Users </a> <br><a href=add.php?s=92> Failed Login Table </a> <br><a href=add.php?s=93>Display Users</a><br>
                      </form>'.$footer;

        exit;
    }else if ($characterName and $characterRace){
        $characterName = mysqli_real_escape_string($db, $characterName);
        $characterRace = mysqli_real_escape_string($db, $characterRace);
        $characterSide = mysqli_real_escape_string($db, $characterSide);
        if ($stmt = mysqli_prepare($db, "insert into characters set name=?, race=?, side=?")) {
            mysqli_stmt_bind_param($stmt, "sss", $characterName, $characterRace, $characterSide);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $characterName, $characterRace, $characterSide);
            mysqli_stmt_close($stmt);
        }
        mysqli_commit($db);
        mysqli_close($db);
        echo $header . '<form method=post action=add.php> 
                                <table> <tr> <td colspan=2> Add Picture to Character '. $characterName.'  </td> </tr>
                                <tr> <td> Character Picture URL </td> <td> <input type=text name=characterPicture value=""> </td> </tr>
                                <tr> <td colspan=2>
                                     <input type=hidden name=characterName value='.$characterName.'>
                                     <input type=submit name=submit value=submit> </td></tr>
                                </table> 
                                </form><br> <br> <a href=add.php?s=99> Logout </a> <br> <a href=add.php?s=90> Add New Users </a> <br><a href=add.php?s=92> Failed Login Table </a> <br>'.$footer;
    }else if ($characterPicture) {
        $characterName = mysqli_real_escape_string($db, $characterName);
        if ($stmt = mysqli_prepare($db, "select characterid from characters where name=?")) {
            mysqli_stmt_bind_param($stmt, "s", $characterName);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $cid);
            while (mysqli_stmt_fetch($stmt)) {
                $cid = htmlspecialchars($cid);
            }
            mysqli_stmt_close($stmt);
            $characterPicture= mysqli_real_escape_string($db, $characterPicture);
            $cid = mysqli_real_escape_string($db, $cid);
            if ($stmt = mysqli_prepare($db, "insert into pictures set url=?, characterid=?")) {
                mysqli_stmt_bind_param($stmt, "si", $characterPicture, $cid);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $characterPicture, $cid);
            }
            mysqli_stmt_close($stmt);
            echo $header . 'Added Picture for '.$characterName.'<br><form method=post action=add.php> 
		<table> <tr> <td colspan=2> Add  to Books </td> </tr>
		<tr> <td> Select Book </td> <td> <select name=bid> <option value="3"> The Fellowship of the Ring
            <option value="1"> The Hobbit
            <option value="5"> The Return of the King
            <option value="4"> The Two Towers
            </select> </td> </tr>
		<tr> <td>
		<input type=hidden name=cid value=' . $cid . '>
		<input type=hidden name=characterName value="">
		<input type=submit name=submit value="Add to Book">
		</td> <td> </td></tr>
		</table> 
		</form><br> <br> <a href=add.php?s=99> Logout </a> <br> <a href=add.php?s=90> Add New Users </a> <br><a href=add.php?s=92> Failed Login Table </a> <br><a href=add.php?s=93>Display Users</a><br>'.$footer;
            mysqli_close($db);
        }
    }else if ($bid) {
        $cid = mysqli_real_escape_string($db, $cid);
        $bid = mysqli_real_escape_string($db, $bid);
        if ($stmt = mysqli_prepare($db, "insert into appears set bookid=?, characterid=?")) {
            mysqli_stmt_bind_param($stmt, "ii", $bid, $cid);
            mysqli_stmt_execute($stmt);
            $last_insert_id = mysqli_insert_id($db);
            mysqli_stmt_bind_result($stmt, $cid);
            mysqli_stmt_close($stmt);
        }
        mysqli_commit($db);

        echo $header . "Added  to book " . $bid . "<br>
        <form method=post action=add.php> 
		<table> <tr> <td colspan=2> Add  to Books </td> </tr>";

        $last_insert_id = mysqli_real_escape_string($db, $last_insert_id);
        if ($stmt = mysqli_prepare($db, "select bookid, title from books where bookid <> (SELECT bookid from appears where appearsid=?)")) {
            mysqli_stmt_bind_param($stmt, "i", $last_insert_id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $bid, $title);
            echo "<tr> <td> Select Book </td> <td> <select name=bid> ";
            while (mysqli_stmt_fetch($stmt)) {
                $title = htmlspecialchars($title);
                $bid = htmlspecialchars($bid);

                echo "<option value=$bid> $title";
            }
            echo "</select> </td> </tr>
		<tr> <td>";
            mysqli_stmt_close($stmt);

            echo "<input type=hidden name=cid value='.$cid.'>
		<input type=hidden name=characterName value=''>
		<input type=submit name=submit value='Add to Book'>
		</td> <td> <a href=index.php?cid=$cid&s=3> Done </a> </td></tr>
		</table> 
		</form><br> <br> <a href=add.php?s=99> Logout </a> <br> <a href=add.php?s=90> Add New Users </a> <br><a href=add.php?s=92> Failed Login Table </a> <br><a href=add.php?s=93>Display Users</a><br>".$footer;
        }
    }else if(!$s){
        echo $header."<form method=post action=add.php> 
		<table> <tr> <td colspan=2> Add Character to Books </td> </tr>
		<tr> <td> Character Name </td> <td> <input type=text name=characterName value='$characterName'> </td> </tr>
		<tr> <td> Race </td> <td> <input type=text name=characterRace value='$characterRace'> </td> </tr>
		<tr> <td> Side </td> <td> <input type=radio name=characterSide value=good> Good  <input type=radio name=CharacterSide value=evil> Evil </td> </tr>
		<tr> <td colspan=2> <input type=submit name=submit value=submit> </td></tr>
		</table> 
		</form><br> <br> <a href=add.php?s=99> Logout </a> <br> <a href=add.php?s=90> Add New Users </a> <br><a href=add.php?s=92> Failed Login Table </a> <br><a href=add.php?s=93>Display Users</a><br>".$footer;

    }

}else if (!isset($_SESSION['authenticated']))
{
    $postPass =$_POST['postPass'];
    if ($postUser and $postPass) {
        authenticate($db, $postUser, $postPass);
    }else if(($postPass and !$postUser) or (!$postPass and $postUser)){
        header("Location:login.php?message=Enter both Username and Password");
        exit;
    }else{
        header("Location:login.php");
        exit;
    }
}else if(!($_SESSION['userid']==1)){
    if($s){
        icheck($s);
        switch ($s) {
            case 90:
                echo $header . "<form method=post action=add.php> 
                                    <table> <tr> <td colspan=2> Not Authorised to Access this page</td> </tr> 
                                    </table>
                                </form> <br> <br> <a href=add.php?s=99> Logout </a>" . $footer;
                break;
            case 99:
                session_destroy();
                header("Location: login.php");
                break;
        }
    }else if ($characterName and $characterRace){
        $characterName = mysqli_real_escape_string($db, $characterName);
        $characterRace = mysqli_real_escape_string($db, $characterRace);
        $characterSide = mysqli_real_escape_string($db, $characterSide);
        if ($stmt = mysqli_prepare($db, "insert into characters set name=?, race=?, side=?")) {
        mysqli_stmt_bind_param($stmt, "sss", $characterName, $characterRace, $characterSide);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $characterName, $characterRace, $characterSide);
        mysqli_stmt_close($stmt);
        }
        mysqli_commit($db);
        mysqli_close($db);
        echo $header . '<form method=post action=add.php>
            <table> <tr> <td colspan=2> Add Picture to Character '. $characterName.' </td> </tr>
                <tr> <td> Character Picture URL </td> <td> <input type=text name=characterPicture value=""> </td> </tr>
                <tr> <td colspan=2>
                        <input type=hidden name=characterName value='.$characterName.'>
                        <input type=submit name=submit value=submit> </td></tr>
            </table>
            </form><br> <br> <a href=login.php> Logout </a> <br>' . $footer;
    }else if ($characterPicture) {
        $characterName = mysqli_real_escape_string($db, $characterName);
        if ($stmt = mysqli_prepare($db, "select characterid from characters where name=?")) {
        mysqli_stmt_bind_param($stmt, "s", $characterName);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $cid);
        while (mysqli_stmt_fetch($stmt)) {
            $cid = htmlspecialchars($cid);
        }mysqli_stmt_close($stmt);

        $characterPicture= mysqli_real_escape_string($db, $characterPicture);
        $cid = mysqli_real_escape_string($db, $cid);
        if ($stmt = mysqli_prepare($db, "insert into pictures set url=?, characterid=?")) {
            mysqli_stmt_bind_param($stmt, "si", $characterPicture, $cid);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $characterPicture, $cid);
        }mysqli_stmt_close($stmt);

        echo $header . 'Added Picture for '.$characterName.' <br><form method=post action=add.php>
            <table> <tr> <td colspan=2> Add  to Books </td> </tr>
                <tr> <td> Select Book </td> <td> <select name=bid> <option value="3"> The Fellowship of the Ring
                            <option value="1"> The Hobbit
                            <option value="5"> The Return of the King
                            <option value="4"> The Two Towers
                        </select> </td> </tr>
                <tr> <td>
                        <input type=hidden name=cid value=' . $cid . '>
                        <input type=hidden name=characterName value="">
                        <input type=submit name=submit value="Add to Book">
                    </td> <td> </td></tr>
            </table>
        </form><br> <br> <a href=login.php> Logout </a> <br>' . $footer;
        mysqli_close($db);
        }
    }else if ($bid) {
        $cid = mysqli_real_escape_string($db, $cid);
        $bid = mysqli_real_escape_string($db, $bid);
        if ($stmt = mysqli_prepare($db, "insert into appears set bookid=?, characterid=?")) {
            mysqli_stmt_bind_param($stmt, "ii", $bid, $cid);
            mysqli_stmt_execute($stmt);
            $last_insert_id = mysqli_insert_id($db);
            mysqli_stmt_bind_result($stmt, $cid);
            mysqli_stmt_close($stmt);
        }mysqli_commit($db);
        echo $header . "Added  to book " . $bid . "<br>
        <form method=post action=add.php>
            <table> <tr> <td colspan=2> Add  to Books </td> </tr>";

            $last_insert_id = mysqli_real_escape_string($db, $last_insert_id);
            if ($stmt = mysqli_prepare($db, "select bookid, title from books where bookid <> (SELECT bookid from appears where appearsid=?)")) {
                mysqli_stmt_bind_param($stmt, "i", $last_insert_id);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $bid, $title);
                echo "<tr> <td> Select Book </td> <td> <select name=bid> ";
                        while (mysqli_stmt_fetch($stmt)) {
                            $title = htmlspecialchars($title);
                            $bid = htmlspecialchars($bid);
                            echo "<option value=$bid> $title";
                        }echo "</select> </td> </tr><tr> <td>";
                mysqli_stmt_close($stmt);

                echo "<input type=hidden name=cid value='.$cid.'>
                <input type=hidden name=characterName value=''>
                <input type=submit name=submit value='Add to Book'>
            </td> <td> <a href=index.php?cid=$cid&s=3> Done </a> </td></tr>
        </table>
    </form><br> <br> <a href=login.php> Logout </a> <br>" . $footer;
            }
    }else if(!$s){
        echo $header."<form method=post action=add.php> 
        <table> <tr> <td colspan=2> Add Character to Books </td> </tr>
		<tr> <td> Character Name </td> <td> <input type=text name=characterName value='$characterName'> </td> </tr>
		<tr> <td> Race </td> <td> <input type=text name=characterRace value='$characterRace'> </td> </tr>
		<tr> <td> Side </td> <td> <input type=radio name=characterSide value=good> Good  <input type=radio name=CharacterSide value=evil> Evil </td> </tr>
		<tr> <td colspan=2> <input type=submit name=submit value=submit> </td></tr>
		</table> 
		</form><br> <br> <a href=add.php?s=99> Logout </a> <br>";
    }
}







?>


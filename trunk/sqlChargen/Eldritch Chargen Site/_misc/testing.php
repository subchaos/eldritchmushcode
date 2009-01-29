<?
    $message    = "";
    $emailclass = "basictext";
    $username   = "";

    if ($_POST['process'] == 1) {

        $pattern = '/.*@.*\..*/';
        $email   = $_POST['email'];
        $urlname = urlencode($$_POST['username']);

        if (preg_match($pattern, $_POST['email']) > 0) {
            // Here's where you would store 
            // the data in a database...
            header(
              "location: thankyou.php?&username=$urlname");
        }
        $message    = "Please enter a valid email address.";
        $username   = $_POST['name'];
        $emailclass = "errortext";
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Modify Qualities and Drawbacks</title>

<style>
    .basictext {
        font-family: Arial, Helvetica, sans-serif; 
        font-size: 14px; color:#000066;
    }
    .errortext {
        font-family: Arial, Helvetica, sans-serif; 
        font-size: 14px; color:#C00000; font-weight: bold;
    }
</style>
<body>
<form action="SetQD.php" method="post">
    <? if ($message != "") {
        print '<span class="errortext">'.
            $message."<span><br>\n";
    } 
    ?>
    <span class="<?print $emailclass; ?>">
        Email address:</span>
    <input name="email" type="text" 
        class="<? print $emailclass; ?>"><br>
    
    <span class="basictext">Your name:</span>
    <input name="name" type="text" class="basictext" 
        value="<?print $username; ?>"><br>
    <input type="hidden" name="process" value="1">
    <input type="submit" name="Button1" value="Sign up!">
</form>
</body></html>
<!DOCTYPE html>

<html>

<head>
  <title>Hello!</title>
<?php
    $result = "NO SUBSEGMENT FOUND";
    $paragraph = "";
    $search_amt = "";
    $segment = "";
    if(isset($_POST['paragraph']) && isset($_POST['search_amt']) && isset($_POST['segment'])){
        $paragraph = $_POST["paragraph"];
        $search_amt = $_POST["search_amt"];
        $segment = $_POST["segment"];
        if($paragraph){
            $thisArr = explode(" ", $paragraph);
            $result = "";
            $tempStr = "";
            $count = 0;
            foreach ($thisArr as $word) {
                $word = preg_replace('/[^a-zA-Z_]/',"",$word);
                $thisArr2 = explode(",", $segment);
                $found = 0;
                foreach ($thisArr2 as $word2) {
                    $word2 = preg_replace('/[^a-zA-Z_]/',"",$word2);
                    if(strtolower($word) == strtolower($word2)) {
                        $tempStr .= $word." ";
                        $found = 1;
                        $count++;
                        break;
                    }
                }
                if(!$found && $count < $search_amt){
                    $count = 0;
                    $tempStr = "";
                }
                if($count == $search_amt) break;
            }
            if($tempStr) $result .= $tempStr;
        }
    }

?>
<style>
textarea, input, button {
    width:500px;
    height:50px;
    padding:5px;
}

</style>
</head>
<body>
    <form action="test.php" method="post">
        <table>
            <tr>
                <td><label for="paragraph">paragraph</label></td>
                <td>
                    <textarea name="paragraph"><?php echo $paragraph; ?></textarea>
                </td>
            </tr>
            <tr>
                <td><label for="search_amt">search_amt</label></td>
                <td>
                    <input type="number" name="search_amt" value="<?php echo $search_amt; ?>" />
                </td>
            </tr>
            <tr>
                <td><label for="segment">segment</label></td>
                <td>
                    <input type="text" name="segment" value="<?php echo $segment; ?>" />
                </td>
            </tr>
            <tr>
                <td><label for="result">result</label></td>
                <td>
                    <textarea name="result" disabled><?php echo $result; ?></textarea>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <button type="submit">submit</button>
                </td>
            </tr>
        </table>
    </form>


</body>
</html>
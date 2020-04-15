<!-- Final version -->
<html>
<head>
    <title>My friends book</title>
    <style>
    /* Style the header */
    header {
        background-color: #666;
        padding: 30px;
        text-align: center;
        font-size: 35px;
        color: white;
    }

    /* Style the footer */
    footer {
        background-color: #777;
        padding: 10px;
        text-align: center;
        color: white;
    }
    </style>
</head>
<body>
    <?php
        include('header.html');
    ?>
    <br>
    <!-- Form for adding names -->
    <form action="index.php" method="post">
    Name: <input type="text" name="name">
        <input type="submit" value="Add new friend">
    </form>

    <h1>My best friends:</h1>

    <?php

        $filename = 'friends.txt';
        // Add the name(s) into the file
        $file = fopen( $filename, "r");
        // Open and read the file
        $names = array();
        if ($file != false){
            while(!feof($file)){
                $name = trim(fgets($file));
                if ($name ===""){
                    continue;
                }
                array_push($names, $name);
            }
            fclose( $file );
        }
        // Get the string in the filter
        $nameFilter="";
        if (isset($_POST['nameFilter'])) {
            $nameFilter = $_POST['nameFilter'];
        } 

        // Get the checkbox status 
        $startingWith=FALSE;
        if (isset($_POST['startingWith'])){
            $startingWith = true;// $_POST['startingWith'];
        }

        if (isset($_POST['name']) && strlen($_POST['name'])>0) {
            $newFriendName = $_POST['name'];
            array_push($names, $newFriendName); 

            $file2 = fopen( $filename, "w" );

            for($j = 0; $j < count($names); $j++) { 
                fwrite( $file2, "$names[$j]\n" );
            }
            fclose( $file2 );
        }

        // Delete the name(s) from the file.
        if (isset($_POST['delete'])) {
            $toDelIndex = $_POST['delete'];

            unset($names[$toDelIndex]);
            $names = array_values($names);

            $file = fopen( $filename, "w" );
            for($j = 0; $j < count($names); $j++) { 
                fwrite( $file, "$names[$j]\n" );
            }
            fclose( $file );
        }

        echo "<ul>";
        // Echo all the names
        for ($i=0; $i < count($names); $i++) {
            $name = $names[$i];

            if ($startingWith==TRUE){
                if($nameFilter=="" || stripos($name, $nameFilter)===0){
                    echo "<li>
                            <form action=\"index.php\" method =\"post\">
                                $name
                                <button type='submit' name='delete' value='$i'>Delete</button>
                            </form>
                        </li>";
                }
            }
            else if ($nameFilter=="" || stripos($name, $nameFilter)!==FALSE) {
                echo "<li>
                    <form action=\"index.php\" method =\"post\">
                        $name
                        <button type='submit' name='delete' value='$i'>Delete</button>
                    </form>
                </li>";
            }
        }
        echo "</ul>";
    ?>

    <form action="index.php" method="post">
        <input type="text" name="nameFilter" value="<?=$nameFilter?>">
        <input type="checkbox" name="startingWith" checked=<?=$startingWith?> value="TRUE"> Only names starting with </input>
        <input type="submit" value="Filter list">
    </form>

    <?php
        include('footer.html');
    ?>

</body>
</html>
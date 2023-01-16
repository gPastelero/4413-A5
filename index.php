<?php
$expire = 1800; //30 minutes for cookies to expire
if(isset($_COOKIE['colOne']) && isset($_COOKIE['colThree']))
{
    $colOne = $_COOKIE['colOne'];
    $colThree = $_COOKIE['colThree'];
}
else
{
    $colOne = 'colOne';
    $colThree = 'colThree';
}
if(isset($_COOKIE['index']))
{
    $index = $_COOKIE['index'];    
}
else
    $index = 'index';
if (is_array($_POST) && !empty($_POST)) {
    if (isset($_POST['Colors'])) {
        if ($colOne == 'colOne')
        {
            $colOne = 'colOne2';
            $colThree = 'colThree2';
        }
        else
        {
            $colOne = 'colOne';
            $colThree = 'colThree';
        }
        setcookie("colOne", $colOne, time() + $expire);
        setcookie("colThree", $colThree, time() + $expire);
        //echo "<script>console.log(\"$_POST[Colors]\"); </script>";
    }
    else if(isset($_POST['Dark']))
    {
        if ($index == 'index')
            $index = 'indexDark';
        else
            $index = 'index';
        setcookie("index", $index, time() + $expire);
        //echo "<script>console.log(\"$_POST[Dark]\"); </script>";
    }
}
/*else
{
    echo "<script>console.log(\"No button pressed...\"); </script>";
}*/

echo "<html lang=\"en-US\">
<head>
    <title>
        WebTech Assign5
    </title>
    <link rel=\"stylesheet\" type=\"text/css\" href=\"styles/main.css\">
</head>
<body class=\"$index\">
    <div class=\"container\">
        <div class=\"container2\">
            <div class=\"floating\">
                <img src=\"imgs/roadrunner.png\" alt=\"img goes here\" class=\"imgSmall\">
            </div>
        </div>

        <div class=\"content\">
            <p class=\"titleBig\">
                Gabriel Pastelero <br>
                <span class=\"titleSmall\">Software Engineer</span>
            </p>
        </div>
    </div>
    <hr class=\"line\">
    <div>
        <table>
            <tr>
                <td class=\"$colOne\">
                    <!-- Menu -->
                    <p>
                        <span>Menu</span><br>
                        <hr>

                    <ul>
                        <li>
                            <a href=\"https://github.com/gPastelero\">Github</a>
                        </li>
                        <li>
                            <a href=\"courses.php\">Courses</a>
                        </li>
                        <li>
                            <a href=\"https://www.utsa.edu/\">UTSA</a>
                        </li>
                    </ul>
                    </p>

                </td>
                <td class=\"colTwo\">
                    <!-- About me -->

                    <p class=\"titleVSmall\">
                        About me
                    </p>
                    <img src=\"imgs/obamaPrism.jpg\" alt=\"img2 goes here\" class=\"imgMed\">
                    <p>My name is Gabriel Pastelero, and I am computer science major. I am currently a
                        senior, and will be graduating in winter 2022. I was born in the Philippines, but
                        moved to America after several years. I started living in New Jersey first, but then
                        soon moved to Texas. I have an interest in software
                        engineering, and I would like to purse a career in that
                        field. Besides that, I enjoy playing video games on PC.</p>


                    <p class=\"preventFloat\">My family comes from a mostly medical background, the majority of them
                        working as
                        nurses. When we moved to the US, we were some of the first in
                        our families to have done so. However, the trend of being in the medical field
                        continued. Both my mother and brother works as a nurse, and my father works as a
                        pharmacy technician. Being in this situation, I have no
                        other family members to rely on in walking the path of computer science. However,
                        the core values of the medical field have rubbed off on me, and
                        I\'d like to take some of those principles with me to the
                        field of computer science.</p>

                </td>
                <td class=\"$colThree\">
                    <!-- Enrolled Courses -->
                    <div>
                        <span>Enrolled courses</span>
                        <hr>

                        <ul>
                            <li>CS3793</li>
                            <li>CS4393</li>
                            <li>CS4413</li>
                            <li>CS4843</li>
                        </ul>
                        <p><br>Theme Toggles</p>
                        <hr>
                        <form action=\"index.php\" method=\"post\">
                            <input type=\"submit\" name = 'Colors' value=\"Colors\">
                        </form>
                        <form action=\"index.php\" method=\"post\">
                            <input type=\"submit\" name = 'Dark' value=\"Dark Mode\">
                        </form>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <div class=\"footNote\">
        Copyright 2022, Gabriel Pastelero
    </div>
</body>

</html>";
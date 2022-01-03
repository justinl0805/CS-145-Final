<nav class="navbar">
    <!--<ul id="profile">
        <li><a href="#">Profile</a></li>
    </ul>-->
    <ul id="nav">
        <li><a class="
        <?php if (PATH_PARTS['filename'] == "index"){
            print'activePage';
        }
        ?>" href="index.php">Home</a></li>
        <li><a class="
        <?php if (PATH_PARTS['filename'] == "about"){
            print'activePage';
        }
        ?>" href="about.php">About</a></li>
        <li><a class="
        <?php if (PATH_PARTS['filename'] == "contact"){
            print'activePage';
        }
        ?>" href="contact.php">Contact</a></li>
        <li class="dropdown">
            <button onclick="profileButton()" id="userProfileButton">Profile</button>
            <div id="profileDropdown" class="dropdown-content">
                <button onclick="profileButton()" id="profileButton">Back</button>
                <a href="profilePage.php">Settings</a>
                <a href="#about">Help</a>
                <?php
                if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
                print '<a href="logIn.php?logout=TRUE" id="logout">Log Out</a>';
                }else{
                    print '<a href="logIn.php">Log In</a>';
                }
                ?>
            </div>
            
        </li>
    </ul>
</nav>
<noscript><div id="jsDisabled"><b>JavaScript has been disabled.Please enable JavaScript to fully utilize this site.</b></div></noscript>
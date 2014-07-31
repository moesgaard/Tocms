<!DOCTYPE html>
<html>
<head>
    <title>TOCMS admin login</title>
    <link rel="stylesheet" href="Admin/css/normalize.css">
    <link rel="stylesheet" href="Admin/css/style.css">
</head>
<body>
    <section class="loginform cf">
        <form id="login" action="" method="POST" accept-charset="utf-8">
            <ul>
                <li>
                <label for="usermail"><?php echo _("Email");?></label>
                    <input type="email" name="username" placeholder="yourname@email.com" required>
                </li>
                <li>
                <label for="password"><?php echo _("Password"); ?></label>
                    <input type="password" name="password" placeholder="password" required></li>
                <li>
                <p class="login_btn" onclick="document.getElementById('login').submit()"><?php  echo _("Login");?></p>    
            </li>
            </ul>
        </form>
    </section>
</body>
</html>

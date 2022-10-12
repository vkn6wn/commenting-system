<?php require_once("config.php");?>
<!DOCTYPE html>
<html>
	<head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1"> 

        <meta name="author" content="Katie Nguyen">
        <meta name="description" content="Article with comments section">
        <meta name="keywords" content="article-blogspot-comments-home"> 
        
        <title>Blogspot Article</title>

        <link href="styles/style.css" rel="stylesheet" type="text/css">
        <link href="styles/comments.css" rel="stylesheet" type="text/css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="vote_scripts.js"></script>
    </head>
	<body>

        <!-- top navigation bar with logo and log in button -->
	    <nav class="navtop">
	    	<div>
                <h1>BLOGSPOT ARTICLES</h1>
                <!-- display log in or log out button depending on if user has logged in -->
                <?php
                    if(!isset($_SESSION['login_session'])){ 
                        echo '<a href="login.php" role="button" id="login_btn">Log In</a>';
                    }
                    else {
                        echo '<a href="logout.php" role="button" id="logout_btn">Log Out</a>';
                    }           
                ?>
	    	</div>
	    </nav>

        <!-- content of article -->
		<div class="content home">
            
            <!-- artitle title and description -->
            <h3 class="article-title">MacBook Pro document confirms 'anti-debris' keyboard redesign</h3>
            <h4 class="article-description">Apple hasn't publicly acknowledged there's an issue.</h4>

            <!-- article author and publishing information -->
            <div class="article-info">
                <div class="article-author">
                    <img id="author-image" src="./images/square-professional-headshot.jpg" alt="author headshot">
                    <div class="primary-author">S. Krishna</div>
                </div>
                <div class="article-other">
                    <p class="date">
                        <time datetime>July 19, 2018</time>
                    </p>
                    <a href="https://twitter.com/skrishna" target="_blank" class="social-media-handle">@shrishna</a>
                </div>
            </div>

            <!-- main article image -->
            <div class="article-image" style="text-align: center">
                <img src="./images/article-image.jpeg" alt="generic computer">
            </div>

            <!-- main text of article -->
			<p>When <a href="https://www.engadget.com/2018-07-13-ifixit-macbook-pro-keyboard-cover-up.html" target="_blank">iFixit tore down the new MacBook Pros</a>, it found silicone barriers protecting keyboard switches. While Apple claimed these were to make the keyboards quieter, others suspected that the membranes were a way for Apple to fix its troublesome keyboards. Now, an internal document obtained by <span class="italic">MacGénération</span> and <a href="https://www.macrumors.com/2018/07/19/apple-confirms-2018-mbp-keyboard-prevents-debris/" target="_blank" class="italic">MacRumors</a> confirms that the new feature is indeed a barrier to "prevent debris from entering the butterfly mechanism."</p>
			<p>Specifically, the US version of the MacBook Pro Service Readiness Guide apparently links to a separate document called "Butterfly Mechanism Keycap Replacement MacBook Pro (2018)." <span class="italic">MacRumors</span> reports that it contains the following language: "<span class="bold">Caution</span>: The keyboard has a membrane under the keycaps to prevent debris from entering the butterfly mechanism. Be careful not to tear the membrane. A torn membrane will result in a top case replacement."</p>
			<p>This isn't really a surprise. Apple is already facing a <a href="https://www.engadget.com/2018-05-12-apple-faces-class-action-lawsuit-over-macbook-keyboards.html" target="_blank">class-action lawsuit</a> over these keyboards, which are expensive and difficult to repair and prone to breakage. The company has also <a href="https://www.engadget.com/2018-06-22-apple-repair-sticky-macbook-macbook-pro-keyboards.html" target="_blank">instituted a service program</a> to repair or replace keyboards free of charge for certain models. While the company doesn't want to add credence to the lawsuits against it, it clearly is taking steps to address the reliability of its keyboards.</p>
			
		</div>
        
        <!-- start of commenting system -->
        <div class="comments"></div>

        <!-- JavaScript to insert commenting system format and logic from comments.php -->
        <script>
        const comments_page_id = 1; // unique id for each article page
        
        // start of using fetch API
        fetch("comments.php?page_id=" + comments_page_id).then(response => response.text()).then(data => {
            
            // selecting comments class
            document.querySelector(".comments").innerHTML = data;
            
            // selecting buttons in the comments class
            document.querySelectorAll(".comments .write_comment_btn, .comments .reply_comment_btn").forEach(element => {
                element.onclick = event => {
                    event.preventDefault();
                    document.querySelectorAll(".comments .write_comment").forEach(element => element.style.display = 'none');
                    document.querySelector("div[data-comment-id='" + element.getAttribute("data-comment-id") + "']").style.display = 'block';
                    document.querySelector("div[data-comment-id='" + element.getAttribute("data-comment-id") + "'] input[name='name']").focus();
                };
            });
            
            // selecting form to write comment
            document.querySelectorAll(".comments .write_comment").forEach(element => {
                element.onsubmit = event => {
                    event.preventDefault();
                    // fetching comment form data
                    fetch("comments.php?page_id=" + comments_page_id, {
                        method: 'POST',
                        body: new FormData(element)
                    }).then(response => response.text()).then(data => {
                        element.parentElement.innerHTML = data;
                    });
                    location.reload(true);
                };
            });

        });
        </script>

	</body>
</html>
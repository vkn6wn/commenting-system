<?php require_once("config.php");?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1"> 

    <meta name="author" content="Katie Nguyen">
    <meta name="description" content="Comments">
    <meta name="keywords" content="article-blogspot-comments"> 
    
    <title>Comments</title>

    <link href="styles/style.css" rel="stylesheet" type="text/css">
    <link href="styles/comments.css" rel="stylesheet" type="text/css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" 
    integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>
<body>
    <?php

    // function to show comments and comments replies
    function show_comments($comments, $parent_id = -1) {
        $html = '';
        if ($parent_id != -1) {
            // sort comment replies by "submit_date" column
            array_multisort(array_column($comments, 'submit_date'), SORT_ASC, $comments);
        }
        // iterate comments using foreach loop
        foreach ($comments as $comment) {
            if ($comment['parent_id'] == $parent_id) {
                // add each comment to the $html variable
                $html .= '
                <div class="comment">
                    <div>
                        <h3 class="name">' . htmlspecialchars($comment['name'], ENT_QUOTES) . '</h3>
                        <span class="date">' . date('F j, Y',strtotime($comment['submit_date'])) . '</span>
                    </div>
                    <p class="content">' . nl2br(htmlspecialchars($comment['content'], ENT_QUOTES)) . '</p>
                    <a class="reply_comment_btn" href="#" data-comment-id="' . $comment['id'] . '">Reply</a>
                    ' . show_write_comment_form($comment['id']) . '
                    <div class="replies">
                    ' . show_comments($comments, $comment['id']) . '
                    </div>
                </div>
                ';
            }
        }
        return $html;
    }

    // function creates the template for the write comment form
    function show_write_comment_form($parent_id = -1) {
        if(!isset($_SESSION['login_session'])){ 
            $html = '';
        }
        else {
            $html = '
            <div class="write_comment" data-comment-id="' . $parent_id . '">
                <form>
                    <input name="parent_id" type="hidden" value="' . $parent_id . '">
                    <input name="name" type="hidden" value="' . $_SESSION['username'] . '">
                    <textarea name="content" placeholder="Write your comment here..." required></textarea>
                    <button type="submit">Submit Comment</button>
                    <button id="cancel_btn"><a href="index.php" id="cancel_a">Cancel<a/></button>
                </form>
            </div>
            ';
        }
        return $html;
    }

    // check if Page ID exists to get corresponding comments
    if (isset($_GET['page_id'])) {
        // check if submitted comment form variable exists
        if (isset($_POST['content'])) {
            // if comment POST variable exists, insert new user submitted comment into the MySQL comments table
            $stmt = $pdo->prepare('INSERT INTO comments (page_id, parent_id, name, content, net_votes, submit_date) VALUES (?,?,?,?,?,NOW())');
            $stmt->execute([ $_GET['page_id'], $_POST['parent_id'], $_POST['name'], $_POST['content'], 0]);
            exit('Your comment has been submitted!');
        }

        // preset sort_option is descending order
        $sort_option = 'DESC';
        // get all comments by the Page ID ordered by sort_option
        $stmt = $pdo->prepare('SELECT * FROM comments WHERE page_id = ? ORDER BY submit_date ' . $sort_option);
        $stmt->execute([ $_GET['page_id'] ]);
        $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // get total number of comments
        $stmt = $pdo->prepare('SELECT COUNT(*) AS total_comments FROM comments WHERE page_id = ?');
        $stmt->execute([ $_GET['page_id'] ]);
        $comments_info = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        exit('No page ID specified');
    }
    ?>

    <!-- display comment header and write comment button-->
    <div class="comment_header">
        <h4 class="article-description" style="margin: 0px">Conversation  <span class="total"><?=$comments_info['total_comments']?> comments</span></h4>
       
        <div>
            <?php
            if(!isset($_SESSION['login_session'])){ 
                echo '<a href="login.php" role="button" id="comment_login_btn">Log In to Comment</a>';
            }
            else {
                echo '<a href="#" class="write_comment_btn" data-comment-id="-1">Write Comment</a>';
            }
            ?>
        </div>
    </div>
    
    <!-- display commenting section -->
    <h4 class="comment_description">Welcome to Blogspot comments! Please keep conversations courteous and on-topic.</h4>
    <?=show_write_comment_form()?>
    <?=show_comments($comments)?>

</body>
</html>
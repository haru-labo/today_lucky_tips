<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Today's&nbsp;happiness</title>
    <link rel="stylesheet" href="css/todays_happiness.css">
    <link href="https://fonts.googleapis.com/css?family=Kosugi+Maru" rel="stylesheet">
    <link rel="apple-touch-icon" type="image/png" href="img/favicons/apple-touch-icon-180x180.png">
    <link rel="icon" type="image/png" href="img/favicons/icon-192x192.png">
</head>
<body>
    <header>
        <h1>Today&#39;s&nbsp;Happiness</h1>
    </header>
    <article>
    <?php
        if(is_array($arr_valid['msg'])){
            print '<ul class="error-msg">';
            foreach ($arr_valid['msg'] as $msg) {
                print '<li>' . e($msg) . '</li>';
            }
            print '</ul>';
        }
    ?>
        <section class="share">
            <h2>今日の幸せをシェアしよう！</h2>
            <form method="post">
                <p class="form-label"><label for="name">お名前&#040;Your&nbsp;Name&#041;</label></p>
                <p class="form-name"><input type="text" id="name" name="name" placeholder="20文字以内" <?php print "value = '$in_name'"; ?>></p>
                <p class="form-label"><label for="tips">今日の幸せな出来事&#040;Today&#39;s&nbsp;Happiness&#41;</label></p>
                <p class="form-content"><textarea id="content" name="content" row="8" cols="13" wrap="soft" placeholder="100文字以内"><?php print $in_content; ?></textarea></p>
                <p><input id=submit type="submit" name="submit" value="シェア(Share)"></p>
            </form>
        </section>
        <section class="contents">
            <h3>幸せな出来事たち</h3>
            <?php
            if(is_array($data)){
                foreach ($data as $disp_array) {
                    print '<div class="content-wrapper">';
                        print '<p class="content">' . $disp_array[2] . '</p>';
                        print '<div class="date-name-wrapper">';
                            print '<p class="name">' . $disp_array[1] . '</p>';
                            print '<p class="input-date">' . $disp_array[0] . '</p>';
                        print '</div>';
                    print '</div>';
                }
            }
            ?>
        </section>
    </article>
</body>
</html>
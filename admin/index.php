<?php
require_once 'db.inc.php';
require_once '../util.inc.php';
require_once 'auth.inc.php';
session_start();
auth_confirm();

define('IMAGE_PATH', '../images/press/');

define('NUM_PER_PAGE', 5);







try {

    $pdo = db_init();

    $sql = 'SELECT COUNT(*) AS hits FROM news';
    $res = $pdo->query($sql)->fetch();
    $hits = $res['hits'];




    // $sql = ('SELECT * FROM news ORDER BY posted_at DESC');
    // $stmt = $pdo->query($sql);
    // $news = $stmt->fetchAll();

//初回訪問処理
    if (isset($_GET['p'])) {

        $page = $_GET['p'];
    } else {

        $page = 1;
    }


//選択されたページの記事を持ってくる処理
    $offset = ($page - 1) * 4;

    $sql = 'SELECT * FROM news LIMIT ' . $offset . ',' . NUM_PER_PAGE;

    $stmt = $pdo->query($sql);

    $newsList = $stmt->fetchAll();

} catch (PDOException $e) {

    header('Content-Type: text/plain; charset=UTF-8', true, 500);
    exit($e->getMessage());
}


$numPages = ceil($hits / 5);


$prevNum = $page - 1;
$nextNum = $page + 1;



?>



<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>お知らせ一覧 | Crescent Shoes 管理</title>
    <link rel="stylesheet" href="css/admin.css">
</head>

<body id="admin_index">
    <header class="pt-3 pb-4 mb-3">

        <div class="inner">
            <span><a href="index.php">Crescent Shoes 管理</a></span>
           <?=require_once 'account.parts.php';?>
        </div>
    </header>
    <div id="container">
        <main>
            <h1>お知らせ一覧</h1>

            <?php if ($page == 1) : ?>
                <ul class="pagination">

        <?php else : ?>
        <li class="page-item disabled">
            <a href="?p=<?= $prevNum ?>">前のページへ</a> |
            </li>
        <?php endif; ?>


        <?php for ($i = 1; $i <= $numPages; $i++) : ?>
            <?php if ($page == $i) : ?>
                <li class="page-item active">
                <?= $i ?> |
                </li>
            <?php else : ?>
                <li class="page-item active">
                <a href="?p=<?= $i ?>"><?= $i ?></a> |
                </li>
            <?php endif; ?>
        <?php endfor; ?>

        <?php if ($page == $numPages) : ?>
            次のページへ
        <?php else : ?>
            <li class="page-item">
            <a href="?p=<?= $nextNum ?>">次のページへ</a>
            </li>
        <?php endif; ?>
            <p>テーブルの全行数： <?= $hits ?></p>
            <p>必要なページ数： <?= $numPages ?></p>


            <table>
                <tr>
                    <th>日付</th>
                    <th>タイトル／お知らせ内容</th>
                    <th>画像（64✖️64）</th>
                    <th>編集</th>
                    <th>削除</th>
                </tr>



                <?php foreach ($newsList as $news) : ?>
                    <tr>
                        <td class="center"><?= $news['posted_at'] ?></td>
                        <td>
                            <span class="title"><?= $news['title'] ?> </span>
                            <?= $news['message'] ?>
                        </td>
                        <td class="center">

                            <?php if ($news['image']) : ?>
                                <img src="<?= IMAGE_PATH . $news['image'] ?>" width="64" height="64" alt="">
                            <?php else : ?>
                                <img src="../images/press.png" width="64" height="64" alt="">
                            <?php endif; ?>

                        </td>
                        <td class="center"><a href="news_edit.php?id=<?= $news['id'] ?>">編集</a></td>
                        <td class="center"><a href="news_delete.php?id=<?= $news['id'] ?>">削除</a></td>
                    </tr>
                <?php endforeach; ?>


        </main>
        <footer>
            <p>&copy; Crescent Shoes All rights reserved.</p>
        </footer>
    </div>
</body>

</html>
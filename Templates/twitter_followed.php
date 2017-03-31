<p>
    <span class="label label-default"> Вы подписаны на вот этих славных ребят </span>
    <span class="label label-primary"><?= count($twitterUser->followed) ?> </span>
</p>
<hr/>

<?php foreach($twitterUser->followed as $followed):?>
    <div class="twitter_user">
        <img class="img-responsive img-rounded"
             src="<?= str_replace("_normal.",".",$followed["profile_image_url"]) ?>" />
        <a href="https://twitter.com/<?=$followed["screen_name"] ?>" target="_blank"> <span class="label label-default"><?= $followed["screen_name"]?></span> </a>

        <blockquote>
            <p><?= $followed["description"]?></p>
            <footer><?=$followed["screen_name"]?></footer>
        </blockquote>
    </div>
<?php endforeach; ?>

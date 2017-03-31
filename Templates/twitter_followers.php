
<p>
    <span class="label label-default"> На вас подписаны вот эти славные ребята </span>
    <span class="label label-primary"><?= count($twitterUser->followers) ?> </span>
</p>
<hr/>

<?php foreach($twitterUser->followers as $follower):?>
    <div class="twitter_user">
        <img class="img-responsive img-rounded"
             src="<?= str_replace("_normal.",".",$follower["profile_image_url"]) ?>" />
        <a href="https://twitter.com/<?=$follower["screen_name"] ?>" target="_blank"> <span class="label label-default"><?= $follower["screen_name"]?></span> </a>
        <button type="button" class="btn btn-warning" data-toggle="tooltip" title="Забанить у*бка"
                onclick="ban_faggot(<?= $follower["id_str"] ?>, '<?= $follower["screen_name"]?>') id="bitch_<?=$follower["screen_name"]?>">
            /ban
        </button>
        <blockquote>
            <p><?= $follower["description"]?></p>
            <footer><?=$follower["screen_name"]?></footer>
        </blockquote>
    </div>
<?php endforeach; ?>
